@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.fuel.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.fuels.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.fuel.fields.name') }}</label>
                <div class="d-flex">
                    <input class="form-control col-md-6 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        type="text" name="name[en]" value="{{ old('name.en', '') }}"
                        placeholder="English" required>
                    <input class="form-control col-md-6 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        type="text" name="name[ar]" value="{{ old('name.ar', '') }}"
                        placeholder="عربي" required>
                </div>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.fuel.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.fuel.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', '') }}" step="0.01" required>
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.fuel.fields.price_helper') }}</span>
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
