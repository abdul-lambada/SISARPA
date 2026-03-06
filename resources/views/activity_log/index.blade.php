@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('header', 'Audit Trail - Log Aktivitas Sistem')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-dark">
            <div class="card-body">
                <table id="log-table" class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th width="30px">No</th>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>IP Address</th>
                            <th width="50px">Data</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Perubahan Data</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <pre id="json-viewer" style="background: #f4f4f4; padding: 15px; border-radius: 5px; max-height: 400px; overflow: auto;"></pre>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(function () {
        $('#log-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('activity-logs.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'time', name: 'created_at'},
                {data: 'user_name', name: 'user.name'},
                {data: 'activity', name: 'activity'},
                {data: 'ip_address', name: 'ip_address'},
                {data: 'details', name: 'details', orderable: false, searchable: false},
            ],
            order: [[1, 'desc']]
        });
    });

    function viewDetails(id) {
        $.get("{{ url('activity-logs') }}/" + id, function(data) {
            $('#json-viewer').text(JSON.stringify(data, null, 4));
            $('#detailModal').modal('show');
        });
    }
</script>
@endpush
