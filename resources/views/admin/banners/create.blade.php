@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.banner.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.banners.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.banner.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="image">{{ trans('cruds.banner.fields.image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.banner.fields.image_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.banner.fields.target') }}</label>
                <div class="row">
                    @foreach(App\Models\Banner::TARGET_TYPES as $class => $type)
                        <div class="col-md-3 d-flex">
                            {{ Form::radio('target_type', $class, old('target_type') == $class, ['class' => 'radio-input']) }}
                            <div class="mx-1">{{trans('cruds.banner.fields.'.$type)}}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('target_type') }}</p>
            <div class="form-group">
                {{ Form::select('target_id', [], old('target_id'), ['id'=>'target_id', 'required', 'placeholder' => 'select','class' => 'form-control select2']) }}
                <p class="text-danger" style="margin-bottom: 0;">{{ $errors->first('target_id') }}</p>
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
    Dropzone.options.imageDropzone = {
    url: '{{ route('admin.banners.storeMedia') }}',
    maxFilesize: 5, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="image"]').remove()
      $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($banner) && $banner->image)
      var file = {!! json_encode($banner->image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
<script>
    function updateIds(selected = null)
    {
        let val = $('[name="target_type"]:checked').val();

        $.ajax({
            url: "{{route('admin.banners.target-ids', ['type' => ''])}}" + val,
            type:"GET",
            dataType:"json",
            success:function(data) {
                $('#target_id').empty();

                $.each(data.data, function(key, value){
                    $('#target_id').append('<option value="'+ key +'">' + value + '</option>');
                });

                if (selected) $('#target_id').val(selected);
            },
            error:function(){
                $('#target_id').empty();
            }
        });
    }

    $('[name="target_type"]').change(function(){
        updateIds();
    });

    $(function() {
        if ($('[name="target_type"]:checked').val()) {
            updateIds("{{old('target_id')}}");
        }
    });
</script>
@endsection

@section('styles')
    <style>
        .radio-input{
            width: 1em;
            height: 1em;
            margin-top: auto;
            margin-bottom: auto;
            margin-right: 1rem;
        }
    </style>
@stop
