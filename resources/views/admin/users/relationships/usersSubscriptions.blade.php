<div class="card">
    <div class="card-header">
        {{ trans('cruds.subscription.title') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-usersSubscriptions">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.plan.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.plan.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.plan.fields.fuel') }}
                        </th>
                        <th>
                            {{ trans('cruds.plan.fields.litres') }}
                        </th>
                        <th>
                            {{ trans('cruds.plan.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.subscription.fields.renew_at') }}
                        </th>
                        <th>
                            {{ trans('cruds.subscription.fields.remaining') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.plans') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-entry-id="{{ $user->activeSubscription->plan_id }}">
                        <td>

                        </td>
                        <td>
                            {{ $user->activeSubscription->plan_id ?? '' }}
                        </td>
                        <td>
                            {{ $user->activeSubscription->plan->name ?? '' }}
                        </td>
                        <td>
                            {{ $user->activeSubscription->plan->fuel->name ?? '' }}
                        </td>
                        <td>
                            {{ $user->activeSubscription->plan->litres ?? '' }}
                        </td>
                        <td>
                            {{ $user->activeSubscription->plan->price ?? '' }}
                        </td>
                        <td>
                            {{ $user->activeSubscription->renew_at ?? '' }}
                        </td>
                        <td>
                            {{ $user->activeSubscription->remaining ?? '' }}
                        </td>
                        <td>
                            <div class="form-group">
                                <div class="row justify-content-between">
                                    <div class="col">
                                        {{ Form::select('plan_id', $plansList, $subscription->plan_id ?? null, ['id' => 'plan_id', 'class' => 'form-control']) }}
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

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent

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
@endsection