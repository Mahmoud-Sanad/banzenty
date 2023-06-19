<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPlanRequest;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Fuel;
use App\Models\Plan;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PlanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('plan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $plans = Plan::with(['fuel'])->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        abort_if(Gate::denies('plan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fuels = Fuel::get();

        $services = Service::pluck('name', 'id')->skip(1)->prepend(trans('global.pleaseSelect'), '');

        return view('admin.plans.create', compact('fuels', 'services'));
    }

    public function store(StorePlanRequest $request)
    {
        $plan = Plan::create($request->all());

        $services = collect($request->input('services', []))->filter(fn($s) => $s['id'] && $s['discount']);

        if ($services->isNotEmpty()) {
            $services = $services->keyBy('id')->map(
                fn($s) => ['discount' => $s['discount'], 'limit' => $s['limit']]
            );
            $plan->services()->attach($services);
        }
        return redirect()->route('admin.plans.index');
    }

    public function edit(Plan $plan)
    {
        abort_if(Gate::denies('plan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fuels = Fuel::get();
        $services = Service::pluck('name', 'id')->skip(1)->prepend(trans('global.pleaseSelect'), '');

        $plan_services = $plan->services->map(
            fn($s) => ['id' => $s->id, 'discount' => $s->pivot->discount, 'limit' => $s->pivot->limit]
        );

        return view('admin.plans.edit', compact('fuels', 'plan', 'services', 'plan_services'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->all());

        $services = collect($request->input('services', []))
        ->filter(fn($s) => $s['id'] && $s['discount'])
        ->keyBy('id')
        ->map(fn($s) => ['discount' => $s['discount'], 'limit' => $s['limit']]);

        $plan->services()->sync($services);

        return redirect()->route('admin.plans.index');
    }

    public function show(Plan $plan)
    {
        abort_if(Gate::denies('plan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $plan->load('fuel');

        return view('admin.plans.show', compact('plan'));
    }

    public function destroy(Plan $plan)
    {
        abort_if(Gate::denies('plan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $plan->delete();

        return back();
    }

    public function massDestroy(MassDestroyPlanRequest $request)
    {
        Plan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
