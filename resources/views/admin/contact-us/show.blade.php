@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.contact_us.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contact.us.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.contact_us.fields.id') }}
                        </th>
                        <td>
                            {{ $contactUs->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact_us.fields.name') }}
                        </th>
                        <td>
                            {{ $contactUs->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact_us.fields.email') }}
                        </th>
                        <td>
                            {{ $contactUs->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact_us.fields.text') }}
                        </th>
                        <td>
                            {{ $contactUs->text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.contact_us.fields.created_at') }}
                        </th>
                        <td>
                            {{ $contactUs->created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.contact.us.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection