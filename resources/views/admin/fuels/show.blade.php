@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.fuel.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.fuels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.fuel.fields.id') }}
                        </th>
                        <td>
                            {{ $fuel->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.fuel.fields.name') }}
                        </th>
                        <td>
                            {{ $fuel->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.fuel.fields.price') }}
                        </th>
                        <td>
                            {{ $fuel->price }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.fuels.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection