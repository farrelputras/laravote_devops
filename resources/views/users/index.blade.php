{{-- resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card section-card">
                <div class="card-header section-header d-flex justify-content-between align-items-center">
                    {{-- Judul dengan font sedikit lebih besar --}}
                    <span style="font-size: 1.25rem;">
                        Users Data Management (Voters)
                    </span>
                    {{-- Tombol --}}
                    <div class="d-flex gap-2">
                    <!-- DEMO -->
                    <!-- <form action="{{ route('users.destroy', 'all') }}" method="POST" onsubmit="return confirm('Yakin hapus semua user (kecuali admin)?');" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-sm"
                                style="background-color: #2A2F85; color: #FFFFFF; border: none;">
                            Delete All Users
                        </button>
                    </form>     -->
                    <a href="{{ route('users.create') }}"
                        class="btn btn-sm"
                        style="background-color: #2A2F85; color: #FFFFFF; border: none; margin-left: 8px;">
                        Add New Data
                    </a>
                    </div>
                </div>
                <div class="card-body section-body p-0">
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- ditambah kelas table table-hover table-bordered mb-0 --}}
                    <table id="users_datatable"
                           class="display compact custom table table-hover table-bordered mb-0"
                           cellspacing="0"
                           width="100%">
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

    /* align Show entries and Search on one line */
    .dt-top {
      display: flex !important;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1rem;
    }
    .dt-top .dataTables_filter {
      margin: 0 !important;
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
          type: 'GET',
      },
      columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'name',        name: 'name' },
          { data: 'nik',         name: 'nik' },
          { data: 'phone',       name: 'phone' },
          { data: 'address',     name: 'address' },
          { data: 'email',       name: 'email' },
          { data: 'status',      name: 'status' },
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
                  return data ? `<span>${data}</span>` : '';
              }
          },
          { data: 'action', name: 'action', orderable: false }
      ],
      dom: '<"dt-top"lfr>t<"bottomcustom"ip>',
      pagingType: 'simple_numbers',   // show ‹ 1 › style pagination
      language: {
        paginate: {
          previous: '<',
          next: '>'
        }
      },
      order: [[0, 'desc']]
  });
});
</script>
@endpush
