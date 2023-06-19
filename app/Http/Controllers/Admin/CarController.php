<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCarRequest;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CarController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('car_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Car::with(['user'])->select(sprintf('%s.*', (new Car())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'car_show';
                $editGate = 'car_edit';
                $deleteGate = 'car_delete';
                $crudRoutePart = 'cars';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('plate_number', function ($row) {
                return $row->plate_number ? $row->plate_number : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('admin.cars.index');
    }

    public function create()
    {
        abort_if(Gate::denies('car_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.cars.create', compact('users'));
    }

    public function store(StoreCarRequest $request)
    {
        $car = Car::make($request->all());

        $car->plate_number = Car::NumbersToEnglish($car->plate_number);
        
        Validator::validate($car->only('digits', 'characters'), [
            'digits'            => 'required|numeric|digits_between:1,4',
            'characters'        => 'required|string|min:2|max:3|regex:/^[\x{0621}-\x{064A}]+$/u',
        ],[
            'characters.regex' => trans('api.plate_number_format'),
        ]);

        $car->save();

        return redirect()->route('admin.cars.index');
    }

    public function edit(Car $car)
    {
        abort_if(Gate::denies('car_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $car->load('user');

        return view('admin.cars.edit', compact('car', 'users'));
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $car->fill($request->all());

        Validator::validate($car->only('digits', 'characters'), [
            'digits'            => 'required|numeric|digits_between:1,4',
            'characters'        => 'required|string|min:2|max:3|regex:/^[\x{0621}-\x{064A}]+$/u',
        ],[
            'characters.regex' => trans('api.plate_number_format'),
        ]);

        $car->save();

        return redirect()->route('admin.cars.index');
    }

    public function show(Car $car)
    {
        abort_if(Gate::denies('car_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $car->load('user');

        return view('admin.cars.show', compact('car'));
    }

    public function destroy(Car $car)
    {
        abort_if(Gate::denies('car_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $car->delete();

        return back();
    }

    public function massDestroy(MassDestroyCarRequest $request)
    {
        Car::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
