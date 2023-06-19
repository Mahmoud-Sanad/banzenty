<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Car;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Station;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class UsersController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = User::with(['roles', 'plans', 'stations', 'activeSubscription'])->whereHas('roles', function ($q) {
                $q->where('id', 1);
            })
                ->select(sprintf('%s.*', (new User())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'user_show';
                $editGate = 'user_edit';
                $deleteGate = 'user_delete';
                $crudRoutePart = 'users';

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

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : '';
            });

            $table->editColumn('roles', function ($row) {
                $labels = [];
                foreach ($row->roles as $role) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $role->title);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'roles']);

            return $table->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $plans = Plan::pluck('name', 'id');

        $stations = Station::pluck('name', 'id');

        return view('admin.users.create', compact('plans', 'roles', 'stations'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));
        $user->plans()->sync($request->input('plans', []));
        $user->stations()->sync($request->input('stations', []));
        if ($request->input('image', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $user->id]);
        }

        Wallet::create(['user_id' => $user->id]);
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $plans = Plan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $stations = Station::pluck('name', 'id');

        $subscription = $user->subscriptions()->latest()->first();

        $user->load('roles', 'plans', 'stations');

        return view('admin.users.edit', compact('plans', 'roles', 'stations', 'user', 'subscription'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
        $user->stations()->sync($request->input('stations', []));
        if ($request->input('image', false)) {
            if (!$user->image || $request->input('image') !== $user->image->file_name) {
                if ($user->image) {
                    $user->image->delete();
                }
                $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($user->image) {
            $user->image->delete();
        }

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show') && Gate::denies('feetManager'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles', 'stations');

        $subscription = $user->subscriptions()->latest()->first();

        $plansList = Plan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.users.show', compact('user', 'subscription', 'plansList'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('user_create') && Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new User();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function carOwners()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 2);
        })
            ->orWhereDoesntHave('roles')
            ->get();

        return view('admin.users.car-owners', compact('users'));
    }

    public function stationAdmins(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($request->ajax()) {
            $query = User::with(['roles', 'plans', 'stations'])->whereHas('roles', function ($q) {
                $q->where('id', 3);
            })->select(sprintf('%s.*', (new User())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'user_show';
                $editGate = 'user_edit';
                $deleteGate = 'user_delete';
                $crudRoutePart = 'users';

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

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : '';
            });

            $table->editColumn('roles', function ($row) {
                $labels = [];
                foreach ($row->roles as $role) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $role->title);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'roles']);

            return $table->make(true);
        }

        return view('admin.users.station-admins');
    }

    public function employees()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 4);
        })->get();

        return view('admin.users.employees', compact('users'));
    }

    public function popularUsers()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 2);
        })
            ->orWhereDoesntHave('roles')
            ->withCount('orders')
            ->OrderBy('orders_count', 'DESC')
            ->limit(5)
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $users
        ]);
    }
    public function myUsers(Request $request)
    {
        abort_if(Gate::denies('feetManager'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $current = $request->user();

        $users = User::where('fleet', $current->name)->get();

        foreach ($users as $user) {
            $car = Car::where('user_id', $user->id)->first();

            if ($car) {
                $user->car = $car->plate_number;
            }
        }

        return view('admin.fleets.fleet-manager', compact('users'));
    }
    public function addUserToFleet(Request $request)
    {
        abort_if(Gate::denies('feetManager'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $current = $request->user();
        Log::info($request->email);
        $userEmail = $request->email;
        $user = User::where('email', $userEmail)->first();
        if (!$user || $user->fleet != 'none') {
            $error = "Can't Do This Operation!";
            return redirect("/admin/fleet-owners/users")->withErrors($error);
        }
        $user->fleet = $current->name;
        $user->save();
        return redirect("/admin/fleet-owners/users");
    }
    public function removeUserFromFleet(Request $request)
    {
        abort_if(Gate::denies('feetManager'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $current = $request->user();
        $userEmail = $request->email;
        $user = User::where('email', $userEmail)->first();
        if (!$user || $user->fleet != $current->name) {
            $error = "Can't Do This Operation!";
            return redirect("/admin/fleet-owners/users")->withErrors($error);
        }
        $user->fleet = 'none';
        $user->save();
        return redirect("/admin/fleet-owners/users");
    }
    public function fleets(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 3);
        })->get();
        $fleet = "yes";
        return view('admin.users.car-owners', compact('users', 'fleet'));
    }

}