{{-- resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card section-card">
        <div class="card-header section-header d-flex justify-content-between align-items-center">
          {{-- Judul --}}
          <span style="font-size:1.25rem;">Users Data Management (Voters)</span>

          {{-- Tombol Add --}}
          <a href="{{ route('users.create') }}"
             class="btn btn-sm"
             style="background-color:#2A2F85;color:#FFFFFF;border:none;">
            Add New Data
          </a>
        </div>
        <div class="card-body section-body p-0">
          @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
          @endif

          <table id="users_datatable"
                 class="display compact custom table table-hover table-bordered mb-0"
                 cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>No Telp</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
  $('#users_datatable').DataTable({
    responsive: true,
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ route('users.index') }}",
      type: 'GET'
    },
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'name',        name: 'name' },
      { data: 'nik',         name: 'nik' },
      { data: 'phone',       name: 'phone' },
      { data: 'address',     name: 'address' },
      { data: 'email',       name: 'email' },
      { data: 'status',      name: 'status' },
      { data: 'action',      name: 'action', orderable: false, searchable: false }
    ],
    dom: '<"dt-top"lf>t<"bottomcustom"ip>',
    pagingType: 'simple_numbers',      // gunakan angka + arrow
    language: {
      paginate: {
        previous: '&lt;',              // teks Previous diganti "<"
        next: '&gt;'                   // teks Next diganti ">"
      }
    },
    order: [[0, 'desc']]
  });
});
</script>
@endpush
