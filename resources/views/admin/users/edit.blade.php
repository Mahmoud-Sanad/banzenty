@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone">{{ trans('cruds.user.fields.phone') }}</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required>
                @if($errors->has('phone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.phone_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fleet">fleet</label>
                <input class="form-control {{ $errors->has('fleet') ? 'is-invalid' : '' }}" type="text" name="fleet" id="fleet" value="{{ old('fleet', $user->fleet) }}" required>
                @if($errors->has('fleet'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fleet') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.phone_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password">
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="image">{{ trans('cruds.user.fields.image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.image_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <div class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="plans">{{ trans('cruds.user.fields.plans') }}</label>
                <div class="row justify-content-between">
                    <div class="col">
                        {{ Form::select('plan_id', $plans, $subscription->plan_id ?? null, ['id' => 'plan_id', 'class' => 'form-control']) }}
                    </div>
                    <div class="d-flex pr-2">
                        <button type="button" class="btn btn-primary mx-1" id="subscribe">
                            Subscribe
                        </button>
                        <button type="button" class="btn btn-warning mx-1" id="renew" @unless($subscription) style="display: none" @endunless>
                            Renew
                        </button>
                        <button type="button" class="btn btn-danger mx-1" id="cancel" @unless($subscription?->status == 1) style="display: none" @endunless>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="stations">{{ trans('cruds.user.fields.stations') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('stations') ? 'is-invalid' : '' }}" name="stations[]" id="stations" multiple>
                    @foreach($stations as $id => $station)
                        <option value="{{ $id }}" {{ (in_array($id, old('stations', [])) || $user->stations->contains($id)) ? 'selected' : '' }}>{{ $station }}</option>
                    @endforeach
                </select>
                @if($errors->has('stations'))
                    <div class="invalid-feedback">
                        {{ $errors->first('stations') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.stations_helper') }}</span>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
            method: "POST",
            beforeSend: function() {
                $('button').prop('disabled', true);
            },
            error: function() {
                Swal.fire({
                    icon: 'error', title: 'Oops...', position: 'top-end',
                    text: 'Something went wrong!', toast: true,
                })
            },
            success: function(data, message, xhr) {
                Swal.fire({
                    icon: 'success', title: 'Success', position: 'top-end',
                    text: xhr.status == 204 ? 'No change required.' : '',
                    timer: 2000, toast: true,
                })
            },
            complete: function() {
                $('button').prop('disabled', false);
            }
        });

        $('#subscribe').click(function(){
            let plan_id = $('#plan_id').val();

            if(!plan_id) { console.log('blllaaah'); return; }

            $.ajax({
                url: "{{route('admin.subscription.attach', [$user->id, 0])}}" + plan_id,
            });
        });

        $('#renew').click(function(){
            $.ajax({
                url: "{{route('admin.subscription.renew', $user->id)}}",
            });
        });

        $('#cancel').click(function(){
            $.ajax({
                url: "{{route('admin.subscription.cancel', $user->id)}}",
            });
        });

    </script>
<script>
    Dropzone.options.imageDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
    maxFilesize: 4, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 4,
      width: 2048,
      height: 2048
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
@if(isset($user) && $user->image)
      var file = {!! json_encode($user->image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
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
@endsection
