@extends('layouts.app')
@section('content')

<div class="w-100 d-flex justify-content-center mb-5">
    <h2> Data Deletion Request </h2>
</div>
<div class="w-100 d-flex justify-content-center">
    <h4> Please, enter your email below.</h4>
</div>
<div class="content d-flex justify-content-center">
    {{ Form::open(['route' => 'request-data-deletion', 'class' => 'col-lg-6']) }}
        <div class="form-group w-100">
            <div class="row justify-content-center">
                <div class="col-md-10 p-1">
                    {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control', 'required', 'placeholder' => 'email']) }}
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-primary" type="submit">Send</button>
            </div>
        </div>
    {{ Form::close() }}
</div>
@endsection
@section('scripts')
@parent

@endsection
