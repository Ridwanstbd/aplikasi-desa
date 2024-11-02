@extends('layouts.app')

@section('content')
<section class="section">
    <div class="pagetitle">
        <h2>Pengklaiman Voucher</h2>
        <x-anchor href="{{route('sync.user.claims')}}" label="Sinkronkan Spreadsheet" class="btn btn-primary my-2"/>
        <x-anchor href="https://docs.google.com/spreadsheets/d/16Ip6knyDd5aQTsX3yndJy0k34D0unWI-lGEbMEeSMDA/edit?usp=sharing" target="_blank" label="lihat Spreadsheet" class="btn btn-success my-4"/>
    </div>
    <x-card title="Daftar Pengklaiman Voucher" :button="['id' => 'export-excel','class' => 'btn btn-sm btn-primary rounded-pill', 'label' => 'Export Excel']" >
        <table class="table table-bordered table-striped" id="user-claims-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Voucher Name</th>
                    <th>User Name</th>
                    <th>User WhatsApp</th>
                    <th>Created At</th>
                </tr>
            </thead>
        </table>
    </x-card>
    <div class="card">
        <div class="card-body">
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(function() {
    var table = $('#user-claims-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('user-claims.index') }}',
            data: function (d) {
                // Store the current DataTables state
                window.lastDataTablesState = d;
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'voucher_name', name: 'voucher_name' },
            { data: 'user_name', name: 'user_name' },
            { data: 'user_whatsapp', name: 'user_whatsapp' },
            { data: 'created_at', name: 'created_at' }
        ]
    });

    // Export button click handler
    $('#export-excel').on('click', function() {
        // Check if we have a stored DataTables state
        if (window.lastDataTablesState) {
            // Convert the state to a query string
            var queryString = $.param(window.lastDataTablesState);

            // Redirect to export route with the current table state
            window.location.href = '{{ route('user-claims.export') }}?' + queryString;
        } else {
            // Fallback to basic export if no state is available
            window.location.href = '{{ route('user-claims.export') }}';
        }
    });
});
</script>
@endpush
