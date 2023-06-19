<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\UserResource;
use App\Models\Car;
use App\Models\Fuel;
use App\Models\Order;
use App\Models\Service;
use App\Models\Settings;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::with(['user', 'service', 'station'])->select(sprintf('%s.*', (new Order())->table));

            if (Gate::denies('request_access')) {
                $query->whereIn('station_id', auth()->user()->stations->pluck('id'));
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = ['request_show', 'limited_request_show'];
                $editGate = ['request_edit', 'limited_request_edit'];
                $deleteGate = ['request_delete', 'limited_request_delete'];
                $crudRoutePart = 'orders';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    )
                );
            });

            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('service_name', function ($row) {
                return $row->service ? $row->service->name : '';
            });

            $table->addColumn('station_name', function ($row) {
                return $row->station ? $row->station->name : '';
            });
            $table->addColumn('fuel', function ($row) {
                return $row->station ? $row->fuel->name : '';
            });
            $table->rawColumns(['actions', 'placeholder', 'user', 'service', 'station', 'fuel']);

            return $table->make(true);
        }

        $users = User::get();
        $services = Service::get();
        $stations = Station::get();

        return view('admin.orders.index', compact('users', 'services', 'stations'));
    }
    public function myRequests(Request $request)
    {
        abort_if(Gate::denies('feetManager'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $current = $request->user();
        if ($request->ajax()) {
            $query = Order::with(['user', 'service', 'station'])
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->where('users.fleet', $current->name)
                ->select(sprintf('%s.*', (new Order())->table));

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = ['request_show', 'limited_request_show'];
                $editGate = ['request_edit', 'limited_request_edit'];
                $deleteGate = ['request_delete', 'limited_request_delete'];
                $crudRoutePart = 'orders';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    )
                );
            });

            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('service_name', function ($row) {
                return $row->service ? $row->service->name : '';
            });

            $table->addColumn('station_name', function ($row) {
                return $row->station ? $row->station->name : '';
            });
            $table->addColumn('fuel', function ($row) {
                return $row->station ? $row->fuel->name : '';
            });
            $table->rawColumns(['actions', 'placeholder', 'user', 'service', 'station', 'fuel']);


            return $table->make(true);
        }
        abort_if(Gate::denies('feetManager'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $users = User::where('fleet', $current->name)->get();
        $services = Service::get();
        $stations = Station::get();
        return view('admin.orders.myRequests', compact('users', 'services', 'stations'));
    }
    public function create(Request $request)
    {
        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if (Gate::denies('request_edit')) {
            $stations = auth()->user()->stations->pluck('name', 'id');
        } else {
            $stations = Station::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        if ($request->has('user')) {
            $user = User::where('external_id', $request->input('user'))->first();
        } else {
            $user = null;
        }

        return view('admin.orders.create', compact('stations', 'user'));
    }

    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();

        $input = $request->all();

        if ($request->litres && $request->fuel_id) {
            $input['price'] = $request->litres * Fuel::find($request->fuel_id)->price;
        }

        $user = User::findOrFail($request->user_id);

        $subscription = $user->activeSubscription;

        $order = Order::make($input)->attachSubscription($subscription);
        $order->save();

        $has_discount = $order->subscription_id && !$order->from_subscription;

        if (!$has_discount) {
            $points = Settings::getValue('reward_points_per_pound') * $request->input('price', 0);
            $user->increment('points', (int) $points);
        }

        DB::commit();

        return redirect()->route('admin.orders.index');
    }

    public function edit(Order $order)
    {
        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $services = $order->station->services->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        if (Gate::denies('request_edit')) {
            $stations = auth()->user()->stations->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        } else {
            $stations = Station::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        $order->load('user', 'service', 'station');

        return view('admin.orders.edit', compact('order', 'services', 'stations', 'users'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $input = $request->all();

        DB::beginTransaction();

        if ($request->litres && $request->fuel_id) {
            $input['price'] = $request->litres * Fuel::find($request->fuel_id)->price;
        }

        if ($request->service_id != 1) {
            $input['litres'] = null;
        }

        $user = User::findOrFail($request->user_id);

        $order->fill($input)->attachSubscription($user->activeSubscription)->save();

        DB::commit();

        return redirect()->route('admin.orders.index');
    }

    public function show(Order $order)
    {
        $order->load('user', 'service', 'station');

        return view('admin.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        $order->attachSubscription(null)->delete();

        return back();
    }

    public function massDestroy(MassDestroyOrderRequest $request)
    {
        $orders = Order::with('station')->whereIn('id', $request->ids)->get();

        foreach ($orders as $order) {
            $this->authorize('delete', $order);
        }

        $orders->each(function (Order $order) {
            $order->attachSubscription(null)->delete();
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function stationServices($id)
    {
        $station = Station::findOrFail($id);

        return response()->json([
            'services' => $station->services->map->only('id', 'name'),
            'fuels' => $station->fuels->map->only('id', 'name', 'price'),
        ]);
    }

    public function findUserByCarPlate(Request $request)
    {
        $car_plate_digits = Car::NumbersToEnglish($request->input('digits'));
        $request->merge(['digits' => (int) $car_plate_digits]);

        $request->validate([
            'digits' => 'required|numeric|digits_between:1,4',
            'characters' => 'required|string|min:2|max:3|regex:/^[\x{0621}-\x{064A}]{2,3}$/u',
        ]);
        Log::info($request->digits . $request->characters);
        $car = Car::firstWhere('plate_number', $request->digits . $request->characters);

        if (!$car)
            return response(status: 400);

        return response()->json([
            'user' => new UserResource($car->user->load('activeSubscription.plan')),
        ]);
    }
}
