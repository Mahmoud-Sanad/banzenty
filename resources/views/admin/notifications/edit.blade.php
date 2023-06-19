@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.notification.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.notifications.update", [$notification->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.notification.fields.title') }}</label>
                <div class="d-flex">
                    <input class="form-control col-md-6 {{ $errors->has('title') ? 'is-invalid' : '' }}"
                        type="text" name="title[en]" id="title" value="{{ old('title.en', $notification->getTranslation('title', 'en')) }}"
                        placeholder="English" required>
                    <input class="form-control col-md-6 {{ $errors->has('title') ? 'is-invalid' : '' }}"
                        type="text" name="title[ar]" id="name" value="{{ old('title.ar', $notification->getTranslation('title', 'ar')) }}"
                        placeholder="عربي" required>
                </div>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.notification.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="body">{{ trans('cruds.notification.fields.body') }}</label>
                <div class="d-flex">
                    <textarea class="form-control col-md-6 {{ $errors->has('body') ? 'is-invalid' : '' }}"
                        type="text" name="body[en]" id="body" placeholder="English" required
                        >{{ old('body.en', $notification->getTranslation('body', 'en')) }}</textarea>
                    <textarea class="form-control col-md-6 {{ $errors->has('body') ? 'is-invalid' : '' }}"
                        type="text" name="body[ar]" id="name" placeholder="عربي" required
                        >{{ old('body.ar', $notification->getTranslation('body', 'ar')) }}</textarea>
                </div>
                @if($errors->has('body'))
                    <div class="invalid-feedback">
                        {{ $errors->first('body') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.notification.fields.body_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="schedule">{{ trans('cruds.notification.fields.schedule') }}</label>
                <input class="form-control datetime {{ $errors->has('schedule') ? 'is-invalid' : '' }}" type="text" name="schedule" id="schedule" value="{{ old('schedule', $notification->schedule) }}">
                @if($errors->has('schedule'))
                    <div class="invalid-feedback">
                        {{ $errors->first('schedule') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.notification.fields.schedule_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="users">{{ trans('cruds.notification.fields.users') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('users') ? 'is-invalid' : '' }}" name="users[]" id="users" multiple>
                    @foreach($users as $id => $user)
                        <option value="{{ $id }}" {{ (in_array($id, old('users', [])) || $notification->users->contains($id)) ? 'selected' : '' }}>{{ $user }}</option>
                    @endforeach
                </select>
                @if($errors->has('users'))
                    <div class="invalid-feedback">
                        {{ $errors->first('users') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.notification.fields.users_helper') }}</span>
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
