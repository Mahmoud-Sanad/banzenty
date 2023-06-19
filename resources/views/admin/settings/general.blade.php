@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.settings.title') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.settings.update") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.settings.fields.reward_points_per_pound') }}</label>
                {{ Form::number('reward_points_per_pound', old('reward_points_per_pound', $reward_points_per_pound ?? null),
                    ['required', 'class' => 'form-control', 'min' => 0]
                ) }}
                @if($errors->has('reward_points_per_pound'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reward_points_per_pound') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.settings.fields.reward_points_per_pound_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.settings.fields.coupon_help_text') }}</label>
                <textarea name="coupon_help_text" class="form-control" rows="3">{{$coupon_help_text ?? ''}}</textarea>
                @if($errors->has('coupon_help_text'))
                    <div class="invalid-feedback">
                        {{ $errors->first('coupon_help_text') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.settings.fields.coupon_help_text_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.settings.fields.subscribe_message') }}</label>
                <div class="row">
                    <div class="col-lg-6">
                        <label><small>{{ __('Renew') }}</small></label>
                        {{ Form::text('subscribe_message[renew]', $subscribe_message['renew'] ?? null, ['required', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-6">
                        <label><small>{{ __('Cancel') }}</small></label>
                        {{ Form::text('subscribe_message[cancel]', $subscribe_message['cancel'] ?? null, ['required', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-6">
                        <label><small>{{ __('Send subscription request') }}</small></label>
                        {{ Form::text('subscribe_message[subscribe]', $subscribe_message['subscribe'] ?? null, ['required', 'class' => 'form-control']) }}
                    </div>
                    <div class="col-lg-6">
                        <label><small>{{ __('Subscription request pending') }}</small></label>
                        {{ Form::text('subscribe_message[request_pending]', $subscribe_message['request_pending'] ?? null, ['required', 'class' => 'form-control']) }}
                    </div>
                </div>
                <span class="help-block">{{ trans('cruds.settings.fields.subscribe_message_helper') }}</span>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('select').select2();
    });
</script>
@stop

