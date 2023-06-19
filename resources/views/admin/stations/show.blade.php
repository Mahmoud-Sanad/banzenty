@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.station.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.stations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.id') }}
                        </th>
                        <td>
                            {{ $station->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.name') }}
                        </th>
                        <td>
                            {{ $station->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.company') }}
                        </th>
                        <td>
                            {{ $station->company->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.location') }}
                        </th>
                        <td>
                            <div id="location">
                                {{ $station->lat .",". $station->lng }}
                            </div>
                            <div style="max-width: 300px" id="map"></div>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.address') }}
                        </th>
                        <td>
                            {{ $station->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.working_hours') }}
                        </th>
                        <td>
                            {{ $station->working_hours }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.has_contract') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $station->has_contract ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.services') }}
                        </th>
                        <td>
                            @foreach($station->services as $key => $services)
                                <span class="label label-info">{{ $services->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.fuels') }}
                        </th>
                        <td>
                            @foreach($station->fuels as $key => $fuels)
                                <span class="label label-info">{{ $fuels->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.station.fields.users') }}
                        </th>
                        <td>
                            @foreach($station->users as $key => $users)
                                <span class="label label-info">{{ $users->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.stations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#stations_users" role="tab" data-toggle="tab">
                {{ trans('cruds.user.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="stations_users">
            @includeIf('admin.stations.relationships.users', ['users' => $station->users])
        </div>
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
    <script src="{{asset('vendor/leaflet-location-picker/leaflet-locationpicker.src.js')}}"></script>
    <script>
        $('#location').leafletLocationPicker({
            alwaysOpen: true,
            mapContainer: "#map",
            location: "{{ $station->lat .','. $station->lng }}",
            height: 200,
            layer: 'JAWG',
            event: 'change',
            map: {zoom: 12, zoomControl: false}
        });
    </script>
@endsection
