@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.plan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.plans.update", [$plan->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.plan.fields.name') }}</label>
                <div class="d-flex">
                    <input class="form-control col-md-6 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        type="text" name="name[en]" id="name" value="{{ old('name.en', $plan->getTranslation('name', 'en')) }}"
                        placeholder="English" required>
                    <input class="form-control col-md-6 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        type="text" name="name[ar]" id="name" value="{{ old('name.ar', $plan->getTranslation('name', 'ar')) }}"
                        placeholder="عربي" required>
                </div>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fuel_id">{{ trans('cruds.plan.fields.fuel') }}</label>
                <select class="form-control select2 {{ $errors->has('fuel') ? 'is-invalid' : '' }}" name="fuel_id" id="fuel_id" required>
                    @foreach($fuels as $fuel)
                        <option value="{{ $fuel->id }}" {{ old('fuel_id', $plan->fuel_id) == $fuel->id ? 'selected' : '' }}>{{ $fuel->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('fuel'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fuel') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.fuel_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.plan.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $plan->price) }}" step="0.01" required>
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="litres">{{ trans('cruds.plan.fields.litres') }}</label>
                <input class="form-control" type="number" name="litres" id="litres" disabled>
                <span class="help-block">{{ trans('cruds.plan.fields.litres_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.plan.fields.period') }}</label>
                {{ Form::select('period', Arr::prepend(App\Models\Plan::PERIOD_SELECT, trans('global.pleaseSelect'), null), old('period', $plan->period),
                    ['id' => 'period', 'class' => "form-control ".($errors->has('period') ? 'is-invalid' : ''), 'required']
                ) }}
                @if($errors->has('period'))
                    <div class="invalid-feedback">
                        {{ $errors->first('period') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.period_helper') }}</span>
            </div>
            <div class="form-group" id="services">
                <label class="required">{{ trans('cruds.plan.fields.services') }}</label>
                <div class="d-flex px-3">
                    <label class="col-md-4 m-1">{{ trans('global.name')}}</label>
                    <label class="col-md-3 m-1">{{ trans('global.discount_percent')}}</label>
                    <label class="col-md-3 m-1">{{ trans('global.limit')}}</label>
                </div>
                <div class="px-3" id="service-list">

                </div>
                <template id="service-template">
                    <div class="d-flex service">
                        {{ Form::select('[id]', $services , null, ['required', 'class' => 'form-control col-md-4 m-1 id']) }}
                        {{ Form::number('[discount]', null, ['required', 'class' => 'form-control col-md-3 m-1 discount', 'placeholder' => trans('global.discount_percent'), 'min' => 0, 'max' => 100]) }}
                        {{ Form::number('[limit]', null, ['class' => 'form-control col-md-3 m-1 limit', 'min' => 1, 'step' => 1]) }}
                        <button type="button" class="btn btn-danger m-1 remove-service"><i class="fa fa-trash"></i></button>
                    </div>
                </template>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add-service">
                        {{ trans('global.add') }}
                    </button>
                </div>
                @if($errors->has('services'))
                    <div class="invalid-feedback">
                        {{ $errors->first('services') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.services_helper') }}</span>
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

@javascript('services', old('services', $plan_services ?? [['id' => null, 'discount' => 0, 'limit' => null]]));
@javascript('fuels', $fuels)

@section('scripts')
    <script>
        $(function() {
            litres();
        })

        $(document).on('change', '#fuel_id, #price', () => litres());

        function litres(){
            let fuel_id = $('#fuel_id').val();
            let price = $('#price').val();
            if (fuel_id && price) {
                let fuel = fuels.find((f) => f.id == parseInt(fuel_id));
                $('#litres').val(Math.round(parseInt(price) * 100 / parseInt(fuel.price)) / 100);
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#add-service').click(function() {
                addService();
            });

            $(document).on('click', '.remove-service', function() {
                $(this).parent().remove();
            });

            services.forEach(function(service) {
                addService(service.id, service.discount, service.limit);
            })
        });

        var index = 0;

        function addService(id = null, discount = 0, limit = null)
        {
            let template = $('#service-template').html();
            let service = $(template);

            service.children('input,select').each(function() {
                $(this).attr('name', `services[${index}]` + $(this).attr('name'));
            });

            service.find('.id').val(id);
            service.find('.discount').val(discount);
            service.find('.limit').val(limit);

            $('#service-list').append(service);
            index++;
        }
    </script>
@endsection


