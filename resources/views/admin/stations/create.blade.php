@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.station.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.stations.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>{{ trans('cruds.station.fields.name') }}</label>
                <div class="d-flex">
                    <input class="form-control col-md-6 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        type="text" name="name[en]" value="{{ old('name.en', '') }}"
                        placeholder="English">
                    <input class="form-control col-md-6 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        type="text" name="name[ar]" value="{{ old('name.ar', '') }}"
                        placeholder="عربي">
                </div>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.station.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="company_id">{{ trans('cruds.station.fields.company') }}</label>
                <select class="form-control select2 {{ $errors->has('company') ? 'is-invalid' : '' }}" name="company_id" id="company_id" required>
                    @foreach($companies as $id => $entry)
                        <option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('company'))
                    <div class="invalid-feedback">
                        {{ $errors->first('company') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.station.fields.company_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="location">{{ trans('cruds.station.fields.location') }}</label>
                <input class="form-control {{ $errors->has('lat') || $errors->has('lng') ? 'is-invalid' : '' }}"
                    type="text" name="location" id="location" value="{{ old('location', '') }}" required>
                @if($errors->has('lat') || $errors->has('lng'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lat') }}{{ $errors->first('lng') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.station.fields.location_helper') }}</span>
            </div>
            <div class="col-md-6" id="map"></div>

            <div class="form-group">
                <label for="address">{{ trans('cruds.station.fields.address') }}</label>
                <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', '') }}">
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.station.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="working_hours">{{ trans('cruds.station.fields.working_hours') }}</label>
                <input class="form-control {{ $errors->has('working_hours') ? 'is-invalid' : '' }}" type="number" name="working_hours" id="working_hours" value="{{ old('working_hours', '') }}">
                @if($errors->has('working_hours'))
                    <div class="invalid-feedback">
                        {{ $errors->first('working_hours') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.station.fields.working_hours_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('has_contract') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="has_contract" value="0">
                    <input class="form-check-input" type="checkbox" name="has_contract" id="has_contract" value="1" {{ old('has_contract', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_contract">{{ trans('cruds.station.fields.has_contract') }}</label>
                </div>
                @if($errors->has('has_contract'))
                    <div class="invalid-feedback">
                        {{ $errors->first('has_contract') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.station.fields.has_contract_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="services">{{ trans('cruds.station.fields.services') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('services') ? 'is-invalid' : '' }}" name="services[]" id="services" multiple>
                    @foreach($services as $id => $service)
                        <option value="{{ $id }}" {{ in_array($id, old('services', [])) ? 'selected' : '' }}>{{ $service }}</option>
                    @endforeach
                </select>
                @if($errors->has('services'))
                    <div class="invalid-feedback">
                        {{ $errors->first('services') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.station.fields.services_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fuels">{{ trans('cruds.station.fields.fuels') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('fuels') ? 'is-invalid' : '' }}" name="fuels[]" id="fuels" multiple required>
                    @foreach($fuels as $id => $fuel)
                        <option value="{{ $id }}" {{ in_array($id, old('fuels', [])) ? 'selected' : '' }}>{{ $fuel }}</option>
                    @endforeach
                </select>
                @if($errors->has('fuels'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fuels') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.station.fields.fuels_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="users">{{ trans('cruds.station.fields.users') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('users') ? 'is-invalid' : '' }}" name="users[]" id="users" multiple>
                    @foreach($users as $id => $user)
                        <option value="{{ $id }}" {{ in_array($id, old('users', [])) ? 'selected' : '' }}>{{ $user }}</option>
                    @endforeach
                </select>
                @if($errors->has('users'))
                    <div class="invalid-feedback">
                        {{ $errors->first('users') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.station.fields.users_helper') }}</span>
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

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
        integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
        crossorigin=""/>
    <link rel="stylesheet" href="{{asset('vendor/leaflet-location-picker/leaflet-locationpicker.src.css')}}" />
@endsection

@section('scripts')
    <script> window.JAWG_ACCESS_TOKEN = "{{config('services.jawg.access_token')}}" </script>
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
    <script src="{{asset('vendor/leaflet-location-picker/leaflet-locationpicker.min.js')}}"></script>
    <script>
        $('#location').leafletLocationPicker({
            alwaysOpen: true,
            mapContainer: "#map",
            location: "{{old('location', '30,31.5')}}",
            height: 300,
            layer: 'JAWG',
            map: {zoom: 12, zoomControl: false}
        });
    </script>
@endsection
