<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyStationRequest;
use App\Http\Requests\StoreStationRequest;
use App\Http\Requests\UpdateStationRequest;
use App\Models\Company;
use App\Models\Fuel;
use App\Models\Service;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class StationController extends Controller
{
    use CsvImportTrait;

    public function __construct()
    {
        $this->authorizeResource(Station::class, 'station');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Station::with(['company', 'services', 'fuels'])->select(sprintf('%s.*', (new Station())->table));

            if(Gate::denies('station_access')) {
                $query->whereHas('users', fn($q) => $q->where('users.id', auth()->id()));
            }

            $table = Datatables::of($query->get());

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return view('partials.datatablesActions', [
                    'viewGate' => ['station_show', 'limited_station_show'],
                    'editGate' => ['station_edit', 'limited_station_edit'],
                    'deleteGate' => ['station_delete', 'limited_station_delete'],
                    'crudRoutePart' => 'stations',
                    'row' => $row
                ]);
            });

            $table->editColumn('name', fn ($row) => $row->name);

            $table->addColumn('company_name', fn ($row) => $row->company ? $row->company->name : '');

            $table->addColumn('service_names', fn ($row) => $row->services->pluck('name')->implode(', '));

            $table->addColumn('fuel_names', fn ($row) => $row->fuels->pluck('name')->implode(', '));

            $table->rawColumns(['actions', 'placeholder', 'company']);

            return $table->make(true);
        }

        $companies = Company::get();
        $services  = Service::get();
        $fuels     = Fuel::get();

        return view('admin.stations.index', compact('companies', 'services', 'fuels'));
    }

    public function create()
    {
        $companies = Company::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $services = Service::pluck('name', 'id')->skip(1);

        $fuels = Fuel::pluck('name', 'id');

        $users = Gate::allows('station_create')
            ? User::pluck('name', 'id')
            : User::where('id', '!=', auth()->id())->pluck('name', 'id');

        return view('admin.stations.create', compact('companies', 'fuels', 'services', 'users'));
    }

    public function store(StoreStationRequest $request)
    {
        $station = Station::create($request->all());
        $station->services()->sync(array_merge([1], $request->input('services', [])));
        $station->fuels()->sync($request->input('fuels', []));

        $user_ids = Gate::allows('station_create')
            ? $request->input('users', [])
            : array_merge($request->input('users', []), [auth()->id()]);

        $station->users()->sync($user_ids);

        return redirect()->route('admin.stations.index');
    }

    public function edit(Station $station)
    {
        $companies = Company::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $services = Service::pluck('name', 'id')->skip(1);

        $fuels = Fuel::pluck('name', 'id');

        $users = User::pluck('name', 'id');

        $station->load('company', 'services', 'fuels', 'users');

        return view('admin.stations.edit', compact('companies', 'fuels', 'services', 'station', 'users'));
    }

    public function update(UpdateStationRequest $request, Station $station)
    {
        $station->update($request->all());
        $station->services()->sync(array_merge([1], $request->input('services', [])));
        $station->fuels()->sync($request->input('fuels', []));
        $station->users()->sync($request->input('users', []));

        return redirect()->route('admin.stations.index');
    }

    public function show(Station $station)
    {
        $station->load('company', 'services', 'fuels', 'users');

        return view('admin.stations.show', compact('station'));
    }

    public function destroy(Station $station)
    {
        $station->delete();

        return back();
    }

    public function massDestroy(MassDestroyStationRequest $request)
    {
        $stations = Station::whereIn('id', $request->ids)->get();

        foreach ($stations as $station) {
            $this->authorize('delete', $station);
        }

        $stations->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function popularStations()
    { 
        $stations = Station::whereHas('fuels')->withCount('orders')
        ->OrderBy('orders_count','DESC')
        ->limit(5)
        ->get();

        return $stations;
    }  
}
