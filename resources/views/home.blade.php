@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if(Gate::allows('limited_request_create'))
                    <div class="card-body row">
                        <div class="col-xl-4 col-lg-6 col-md-7 p-2">
                            <a href="{{route('admin.orders.qr-scanner')}}"
                                class="btn btn-secondary d-flex align-content-start rounded p-3 w-100">
                                <img src="{{url('assets/qrcode.svg')}}" alt="qr-code">
                                <span class="my-auto h5 ml-2" style="color: black">{{trans('Scan Qr Code')}}</span>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-7 p-2">
                            <a href="{{route('admin.orders.create')}}"
                                class="btn btn-danger d-flex align-content-start rounded p-3 w-100">
                                <img src="{{url('assets/plus.svg')}}" alt="plus">
                                <span class="my-auto h5 ml-2">{{trans('Add new request')}}</span>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-7 p-2">
                            <a href="{{route('admin.orders.index')}}"
                                class="btn btn-dark d-flex align-content-start rounded p-3 w-100">
                                <img src="{{url('assets/history.svg')}}" alt="history">
                                <span class="my-auto h5 ml-2">{{trans('Resquests history')}}</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection
