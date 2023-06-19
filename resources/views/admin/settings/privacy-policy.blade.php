@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.settings.privacy_policy') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.settings.privacy.update") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="d-flex">
                    <textarea class="form-control col-md-12 {{ $errors->has('text') ? 'is-invalid' : '' }}" name="text" required>
                        {{ old('text', $privacy_policy) }}
                    </textarea>
                </div>
                @if($errors->has('text'))
                    <div class="invalid-feedback">
                        {{ $errors->first('text') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.settings.privacy_policy_helper') }}</span>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>

        </form>
    </div>

</div>
@stop


@section('scripts')
@parent
<script src="https://cdn.tiny.cloud/1/{{config('services.tinyMCE.api_key')}}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea'
  });
</script>
@stop
