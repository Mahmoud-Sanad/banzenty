<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\SubscriptionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionRequestController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('subscription_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SubscriptionRequest::with(['user', 'plan'])->select('subscription_requests.*');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');

            $table->addColumn('actions', 'admin.subscription-requests.partials.actions');

            $table->editColumn('type', function ($row) {
                return SubscriptionRequest::TYPES[$row->type] ?? '';
            });

            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('plan_name', function ($row) {
                return $row->plan ? $row->plan->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('admin.subscription-requests.index');
    }

    public function update(Request $request, SubscriptionRequest $subscription_request)
    {
        abort_if(Gate::denies('subscription_request_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controller = new SubscriptionController();

        $actions = [
            1 => 'attach',
            2 => 'renew',
            3 => 'cancel',
        ];

        $controller->callAction(
            $actions[$subscription_request->type],
            [$subscription_request->user, $subscription_request->plan]
        );

        $subscription_request->update(['status' => 1]);
        $subscription_request->delete();

        dispatch(function () use($subscription_request) {
            $notification = Notification::create([
                'title' => [
                    'ar' => 'تم قبول طلب الاشتراك الذي ارسلته',
                    'en' => 'Your request for subscription has been accepted'
                ],
                'body' => [
                    'ar' => '',
                    'en' => '',
                ],
                'type' => 2,
            ]);
            $notification->users()->attach($subscription_request->user_id);
            $notification->send();
        })->afterResponse();

        return redirect()->route('admin.subscription-requests.index');
    }

    public function destroy(SubscriptionRequest $subscription_request)
    {
        abort_if(Gate::denies('subscription_request_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subscription_request->update(['status' => 2]);
        $subscription_request->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('subscription_request_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        SubscriptionRequest::whereIn('id', $request->ids)->get()->each(function($r) {
            $r->update(['status' => 2]);
            $r->delete();
        });

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
