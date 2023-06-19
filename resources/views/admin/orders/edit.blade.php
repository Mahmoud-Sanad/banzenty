@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.order.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.orders.update", [$order->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.order.fields.user') }}</label>
                <div class="row" style="max-width: 24rem">
                    <div class="col-3 pr-0">
                      <input name="plate_number_digits" type="number" placeholder="{{trans('cruds.car.fields.digits')}}"
                        class="form-control rounded-pill plate" id="digits" value="{{old('plate_number_digits', $order->user->car?->digits)}}">
                    </div>
                    <div class="col-3 pr-0">
                      <input name="plate_number_characters" type="text" placeholder="{{trans('cruds.car.fields.characters')}}"
                        class="form-control rounded-pill plate" id="characters" value="{{old('plate_number_characters', $order->user->car?->characters)}}">
                    </div>
                    <div class="col-6 h5 my-auto" id="user_label">{{$order->user->name}}</div>
                </div>
                <input type="hidden" name="user_id" value="{{old('user_id', $order->user_id)}}" id="user_id" required>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.user_helper') }}</span>
            </div>
            <div class="form-group rounded bg-success text-white px-1" id="subscription" style="display:none">
                <label> {{trans('cruds.user.active_subscription')}} </label>
                <div class="d-flex">
                    <div id="subscription_name"></div>
                    <div id="remaining" class="mx-2"></div>
                </div>
                <ul id="services"></ul>
            </div>
            <div class="form-group">
                <label class="required" for="station_id">{{ trans('cruds.order.fields.station') }}</label>
                <select class="form-control select2 {{ $errors->has('station') ? 'is-invalid' : '' }}" name="station_id" id="station_id" required>
                    @foreach($stations as $id => $entry)
                        <option value="{{ $id }}" {{ (old('station_id') ? old('station_id') : $order->station->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('station'))
                    <div class="invalid-feedback">
                        {{ $errors->first('station') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.station_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="service_id">{{ trans('cruds.order.fields.service') }}</label>
                <select class="form-control select2 {{ $errors->has('service') ? 'is-invalid' : '' }}" name="service_id" id="service_id" required>
                    @foreach($services as $id => $entry)
                        <option value="{{ $id }}" {{ (old('service_id') ? old('service_id') : $order->service->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('service'))
                    <div class="invalid-feedback">
                        {{ $errors->first('service') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.service_helper') }}</span>
            </div>
            <div class="form-group" @if(old('service_id', $order->service_id) != 1) style="display:none" @endif>
                <label class="required" for="fuel_id">{{ trans('cruds.order.fields.fuel') }}</label>
                <select class="form-control select2 {{ $errors->has('service') ? 'is-invalid' : '' }}" name="fuel_id" id="fuel_id">
                    @foreach($order->station->fuels as $fuel)
                        <option value="{{ $fuel->id }}" {{ old('fuel_id', $order->fuel_id) == $fuel->id ? 'selected' : '' }} price="{{ $fuel->price }}">
                            {{ $fuel->name }}
                        </option>
                    @endforeach
                </select>
                @if($errors->has('fuel_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fuel_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.fuel_helper') }}</span>
            </div>
            <div class="form-group" @if(old('service_id', $order->service_id) != 1) style="display:none" @endif>
                <label for="litres">{{ trans('cruds.order.fields.litres') }}</label>
                <input class="form-control {{ $errors->has('litres') ? 'is-invalid' : '' }}" type="number" name="litres" id="litres" value="{{ old('litres', $order->litres) }}" step="0.01">
                @if($errors->has('litres'))
                    <div class="invalid-feedback">
                        {{ $errors->first('litres') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.litres_helper') }}</span>
            </div>
            <div class="form-group" style="display: none">
                <label for="from_subscription">
                    {{ trans('cruds.order.fields.from_subscription') }}
                </label>
                <input class="form-control" type="number" name="from_subscription" id="from_subscription" disabled>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.order.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $order->price) }}" step="0.01" required>
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.order.fields.price_helper') }}</span>
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
    <script>
        $('#service_id').change(function(){
            if($(this).val() == 1){
                $('#litres').prop('disabled', false).parent().show();
                $('#fuel_id').prop('disabled', false).parent().show();
            }else{
                $('#litres').prop('disabled', true).parent().hide();
                $('#fuel_id').prop('disabled', true).parent().hide();
            }
            updatePrice();
        });

        $('#litres').keyup(() => updatePrice());
        $('#fuel_id').change(() => updatePrice());

        function updatePrice() {
            if ($('#service_id').val() == 1) {
                let price = parseFloat($('#litres').val()) * parseFloat($('#fuel_id').find(':selected').attr('price'));
                if (subscription) {
                    var from_subscription = Math.min(price, subscription.remaining);
                    $('#from_subscription').val(from_subscription).parent().show();
                } else {
                    $('#from_subscription').val(0).parent().hide();
                }
                $('#price').val(price - (from_subscription ?? 0)).prop('readonly', true);
            } else {
                $('#price').prop('readonly', false);
                $('#from_subscription').val(0).parent().hide();
            }
        }
    </script>
    <script>
        function updateServices(value = null) {
            let station_id = $('#station_id').val();
            if (station_id) {
                $.ajax({
                    url: "{{url('admin')}}" + `/stations/${station_id}/services`,
                    success: function (data) {
                        $('#service_id').empty();
                        data.services.forEach(service => {
                            $('#service_id').append(
                                `<option value="${service.id}" ${service.id == parseInt(value) ? 'selected' : ''}>${service.name}</option>`
                            );
                        });
                        $('#fuel_id').empty();
                        data.fuels.forEach(fuel => {
                            $('#fuel_id').append(
                                `<option value="${fuel.id}" ${fuel.id == parseInt(value) ? 'selected' : ''} price="${fuel.price}">
                                    ${fuel.name}
                                </option>`
                            );
                        });
                    },
                    complete: function () {
                        $('#service_id').trigger('change');
                    }
                });
            }
        }

        $('#station_id').change(function () {
            let service_id = $('#service_id').val();
            updateServices(service_id);
        });
    </script>
    <script>
        var subscription = null;

        var plate_number = null;
        $('.plate').keyup(function () {
            let digits = $('#digits').val();
            let characters = $('#characters').val().replaceAll(' ', '');

            if (digits.length == 0 || characters.length < 2) return;

            let query = `?digits=${digits}&characters=${characters}`

            if(plate_number == null) {
                plate_number = query;
                queryUser(query);
            }else{
                plate_number = query;
            }
        });

        function queryUser(query)
        {
            $.ajax({
                url: "{{route('admin.orders.user')}}" + query,
                beforeSend: function() {
                    $('#user_id').val('');
                    $('#user_label').text('');
                    $('#loading').show();
                },
                success: function(data){
                    $('#user_id').val(data.user.id);
                    $('#user_label').text(data.user.name);
                    if (data.user.active_subscription) {
                        subscription = data.user.active_subscription;
                        $('#subscription_name').text(subscription.plan.name);
                        $('#remaining').text(subscription.remaining + " {{trans('global.EGP')}}");
                        $('#services').empty();
                        subscription.plan.services.forEach(service => {
                            let usage_remaining = service.limit - service.used;
                            $('#services').append(
                                `<li ${usage_remaining < 1 && service.limit ? 'style="text-decoration:line-through"' : ''}>
                                    ${service.name} ${service.discount}% ${service.limit ? '('+usage_remaining+')' : ''}
                                </li>`
                            );
                        });
                        $('#subscription').show();
                        updatePrice();
                    }
                },
                error: function() {
                    clearSubscription();
                },
                complete: function() {
                    if(plate_number == query) {
                        plate_number = null;
                        $('#loading').hide();
                    }else{
                        queryUser(plate_number);
                    }
                }
            })
        }

        function clearSubscription()
        {
            subscription = null;
            $('#subscription').hide();
            updatePrice();
        }

        $(function() {
            $('#digits').trigger('keyup');
        })
    </script>
@endsection
