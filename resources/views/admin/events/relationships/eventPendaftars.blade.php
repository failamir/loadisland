@can('pendaftar_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.pendaftars.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.pendaftar.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.pendaftar.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-eventPendaftars">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.no_tiket') }}
                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.nama') }}
                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.nik') }}
                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.no_hp') }}
                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.checkin') }}
                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.payment') }}
                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.status_payment') }}
                        </th>
                        <th>
                            {{ trans('cruds.pendaftar.fields.event') }}
                        </th>
                        <th>
                            {{ trans('cruds.event.fields.event_code') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftars as $key => $pendaftar)
                        <tr data-entry-id="{{ $pendaftar->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $pendaftar->id ?? '' }}
                            </td>
                            <td>
                                {{ $pendaftar->no_tiket ?? '' }}
                            </td>
                            <td>
                                {{ $pendaftar->nama ?? '' }}
                            </td>
                            <td>
                                {{ $pendaftar->nik ?? '' }}
                            </td>
                            <td>
                                {{ $pendaftar->email ?? '' }}
                            </td>
                            <td>
                                {{ $pendaftar->no_hp ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Pendaftar::CHECKIN_SELECT[$pendaftar->checkin] ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Pendaftar::PAYMENT_SELECT[$pendaftar->payment] ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Pendaftar::STATUS_PAYMENT_SELECT[$pendaftar->status_payment] ?? '' }}
                            </td>
                            <td>
                                {{ $pendaftar->event->nama_event ?? '' }}
                            </td>
                            <td>
                                {{ $pendaftar->event->event_code ?? '' }}
                            </td>
                            <td>
                                @can('pendaftar_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.pendaftars.show', $pendaftar->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('pendaftar_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.pendaftars.edit', $pendaftar->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('pendaftar_delete')
                                    <form action="{{ route('admin.pendaftars.destroy', $pendaftar->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('pendaftar_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.pendaftars.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 50,
  });
  let table = $('.datatable-eventPendaftars:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection