<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\FuelResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\StationResource;
use App\Models\Company;
use App\Models\Fuel;
use App\Models\Service;
use App\Models\Settings;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class StationController extends BaseController
{
    public function getFilters()
    {
        try {
            $services = Service::withCount('stations')->orderBy('stations_count', 'desc')->get();

            $fuels = Fuel::get();

            $companies = Company::withCount('stations')->orderBy('stations_count', 'desc')->get();

            return $this->apiResponse([
                'services' => ServiceResource::collection($services),
                'companies' => CompanyResource::collection($companies),
                'fuel_types' => FuelResource::collection($fuels),
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'service_ids'           => 'array',
                'service_ids.*'         => 'integer|exists:services,id',
                'company_ids'           => 'array',
                'company_ids.*'         => 'integer|exists:companies,id',
                'fuel_ids'              => 'array',
                'fuel_ids.*'            => 'integer|exists:fuels,id',
                'distance_min'          => 'integer|min:0',
                'distance_max'          => 'integer|min:0|gt:distance_min',
                'lat'                   => 'required_with:distance_min,distance_max|numeric|min:-90|max:90',
                'lng'                   => 'required_with:distance_min,distance_max|numeric|min:-180|max:180',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            $query = Station::query();

            if ($request->filled('service_ids')) {
                $query->whereHas('services', fn ($query) => $query->whereIn('services.id', $request->service_ids));
            }

            if ($request->filled('fuel_ids')) {
                $query->whereHas('fuels', fn ($query) => $query->whereIn('fuels.id', $request->fuel_ids));
            }

            if ($request->filled('company_ids')) {
                $query->whereIn('company_id', $request->company_ids);
            }

            if ($request->filled(['lat', 'lng'])) {
                $query->distance($request->lat, $request->lng)->orderBy('distance');
            }

            if ($request->filled('distance_min')) {
                $query->having('distance', '>', $request->distance_min);
            }

            if ($request->filled('distance_max')) {
                $query->having('distance', '<', $request->distance_max);
            }

            $stations = $query->take(10)->with('company', 'services')->get();

            return $this->apiResponse([
                'stations' => StationResource::collection($stations),
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function details($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id'           => 'required|exists:stations'
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            $station = Station::with(['company', 'services', 'fuels'])->find($id);

            return $this->apiResponse([
                'station' => new StationResource($station),
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function list(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lat'                   => 'numeric|min:-90|max:90',
                'lng'                   => 'numeric|min:-180|max:180',
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 400);
            }

            $stations = Station::with('company')->when(
                $request->filled(['lat', 'lng']),
                fn($q) => $q->distance($request->lat, $request->lng)->orderBy('distance')
            )->get(['id', 'name', 'company_id'])->map(function($station){
                return [
                    'id' => $station->id,
                    'name' => $station->name,
                    'icon' => $station->company->icon ? Arr::only($station->company->icon->getAttributes(), ['url', 'thumbnail', 'preview']) : null,
                ];

            });

            return $this->apiResponse(['stations' => $stations]);

        } catch(\Exception $e) {
            return $this->internalServerError($e);
        }
    }
}
