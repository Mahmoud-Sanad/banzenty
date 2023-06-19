<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Service;
use App\Models\Station;
use App\Models\ContactUs;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController
{
    public function index()
    {
        return view('home');
    }

    public function contactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'text' => 'required'
        ]);

        $validator->validate();

        ContactUs::create($validator->validated());

        return redirect()->route('thank-you');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function charts(Request $request)
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('id', 2);
        })
            ->orWhereDoesntHave('roles')
            ->withCount('orders')
            ->OrderBy('orders_count', 'DESC')
            ->limit(5)
            ->get();

        $stations = Station::whereHas('fuels')->withCount('orders')
            ->OrderBy('orders_count', 'DESC')
            ->limit(5)
            ->get();

        $services = Service::join('orders', 'services.id', 'orders.service_id')
            ->select(['name', 'service_id', DB::raw('sum(price) as total_price'), DB::raw('count(*) as orders_count')])
            ->groupBy('service_id', 'name')
            ->get();

        if ($request->has('date_from') && $request->has('date_to'))
            $services = Service::join('orders', 'services.id', 'orders.service_id')
                ->select(['name', 'service_id', DB::raw('sum(price) as total_price'), DB::raw('count(*) as orders_count')])
                ->whereBetween('orders.created_at', [$request->date_from, $request->date_to])
                ->groupBy('service_id', 'name')
                ->get();

        return view('charts', compact('users', 'stations', 'services'));
    }
    public function fleetChart(Request $request)
    {
        $current = $request->user();
        $users = User::where('fleet', $current->name)
            ->withCount([
                'orders as total_litres' => function ($query) {
                    $query->select(DB::raw('coalesce(sum(litres), 0)'));
                },
                'orders as total_money' => function ($query) {
                    $query->select(DB::raw('coalesce(sum(price), 0)'));
                }
            ])
            ->orderBy('total_litres', 'DESC')
            ->get();
        $myWallet = Wallet::where('user_id', $request->user()->id)->first();
        $request->user()->myWallet = $myWallet;
        return view('fleetChart', compact('users', 'myWallet'));
    }
}