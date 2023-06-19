@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.car.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.cars.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
            <label class="required" for="plate_number">{{ trans('cruds.car.fields.plate_number') }}</label>
            <input class="form-control {{ $errors->has('plate_number') ? 'is-invalid' : '' }}" type="text" id="plate_number" placeholder = '{{ trans("cruds.car.fields.placeholder") }}' oninput="validateArabicAlpha();" name="plate_number" onkeydown="checkKeycode(event); this.select()" required>
                @if($errors->has('plate_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('plate_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.car.fields.plate_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="user_id">{{ trans('cruds.car.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id">
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.car.fields.user_helper') }}</span>
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


<script language="JavaScript">
//-------------------- Arabic (Alphabets only) --------------------------
function validateArabicAlpha(){
  var textInput = document.getElementById("plate_number").value;
    textInput = textInput.replace(/[^\u0600-\u06FF]+$/g, "");
    document.getElementById("plate_number").value = textInput;
}

</script>