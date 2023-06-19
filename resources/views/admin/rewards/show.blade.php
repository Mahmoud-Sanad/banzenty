@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.reward.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rewards.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.reward.fields.id') }}
                        </th>
                        <td>
                            {{ $reward->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reward.fields.name') }}
                        </th>
                        <td>
                            {{ $reward->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reward.fields.description') }}
                        </th>
                        <td>
                            {{ $reward->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reward.fields.service') }}
                        </th>
                        <td>
                            {{ $reward->service->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reward.fields.points') }}
                        </th>
                        <td>
                            {{ $reward->points }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reward.fields.image') }}
                        </th>
                        <td>
                            @if($reward->image)
                                <a href="{{ $reward->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $reward->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.rewards.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection