@extends('layouts.app')
@section('content')

    <div class="w-100 d-flex justify-content-center mb-5">
        <h2> Data Deletion Request </h2>
    </div>

    @if(session()->has('not_found'))
        <div class="w-100 d-flex justify-content-center">
            <h4> User does not exist.</h4>
        </div>
    @elseif(session()->has('success'))
        <div class="w-100 d-flex justify-content-center">
            <h4> Account deleted successfully.</h4>
        </div>
    @else
        <div class="w-100 d-flex justify-content-center">
            <h4> Enter the code sent to your email.</h4>
        </div>
        <div class="content d-flex justify-content-center">
            {{ Form::open(['route' => 'confirm-data-deletion', 'class' => 'col-lg-6']) }}
            {{ Form::hidden('email', request()->email) }}
                <div class="form-group w-100">
                    <div class="row justify-content-center">
                        <div class="col-md-10 p-1">
                            {{ Form::text('code', null, ['class' => 'form-control', 'required', 'placeholder' => 'code']) }}
                        </div>
                        @error('code')
                            <div class="text-danger">{{$errors->first('code')}}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary" type="submit">Send</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    @endif
@endsection
@section('scripts')
@parent

@endsection
