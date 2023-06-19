@extends('layouts.app')
@section('content')

<div class="w-100 d-flex justify-content-center">
    <h2> Contact Banzenty </h2>
</div>
<div class="content d-flex justify-content-center">
    {{ Form::open(['route' => 'contact-us.send', 'class' => 'col-lg-6']) }}
        <div class="form-group w-100">
            <div class="row">
                <div class="col-md-6 p-1">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => 'name']) }}
                </div>
                <div class="col-md-6 p-1">
                    {{ Form::email('email', null, ['class' => 'form-control', 'required', 'placeholder' => 'email']) }}
                </div>
                <div class="col-12 p-1">
                    {{ Form::textarea('text', null, ['class' => 'form-control', 'required', 'placeholder' => 'message']) }}
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
