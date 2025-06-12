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
                <th>Eligible?</th>
                <th>Token</th>
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
<style>
  #users_datatable td,
  #users_datatable th {
    vertical-align: middle;
  }
  #users_datatable td.eligible-checkbox,
  #users_datatable th.eligible-checkbox {
    text-align: center !important;
  }
</style>
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
        { data: 'DT_RowIndex',  name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name',         name: 'name' },
        { data: 'nik',          name: 'nik' },
        { data: 'phone',        name: 'phone' },
        { data: 'address',      name: 'address' },
        { data: 'email',        name: 'email' },
        { data: 'status',       name: 'status' },
        {
          data: 'is_eligible',
          name: 'is_eligible',
          orderable: false,
          searchable: false,
          className: 'eligible-checkbox',
          render: function(data, type, row) {
            return `
              <form method="POST" action="/users/${row.id}/toggle-eligible">
                @csrf
                @method('PUT')
                <input type="checkbox" onchange="this.form.submit()" ${data ? 'checked' : ''}>
              </form>
            `;
          }
        },
        {
          data: 'token',
          name: 'token',
          orderable: false,
          searchable: false,
          className: 'text-center',
          render: function(data) {
            return `<span>${data}</span>`;
          }
        },
        { data: 'action', name: 'action', orderable: false, searchable: false }
      ],
      dom: '<"dt-top"lf>t<"bottomcustom"ip>',
      pagingType: 'simple_numbers',
      language: {
        paginate: {
          previous: '&lt;',
          next: '&gt;'
        }
      },
      order: [[0, 'desc']]
    });
  });
</script>
@endpush
