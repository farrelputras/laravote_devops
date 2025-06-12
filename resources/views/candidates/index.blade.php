@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card section-card">
                    <div class="card-header section-header d-flex justify-content-between align-items-center">
                        {{-- Judul dengan font sedikit lebih besar --}}
                        <span style="font-size: 1.25rem;">
                            Candidate Data Management
                        </span>
                        {{-- Tombol --}}
                        <a href="{{ route('candidates.create') }}"
                           class="btn btn-sm"
                           style="background-color: #2A2F85; color: #FFFFFF; border: none;">
                          Add New Data
                        </a>
                    </div>
                    <div class="card-body section-body p-0">
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Foto Pasangan Calon</th>
                                    <th>Nama Ketua</th>
                                    <th>Nama Wakil</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            {{-- hanya tbody yang center-align & vertical middle --}}
                            <tbody class="text-center align-middle">
                                @foreach ($candidates as $candidate)
                                <tr>
                                    {{-- Kolom Foto dulu --}}
                                    <td>
                                        @if ($candidate->photo_paslon)
                                            <img src="{{ asset('storage/'.$candidate->photo_paslon) }}"
                                                 width="100" alt="Foto Pasangan">
                                        @endif
                                    </td>
                                    {{-- Baru Nama Ketua --}}
                                    <td>{{ $candidate->nama_ketua }}</td>
                                    {{-- Lalu Nama Wakil --}}
                                    <td>{{ $candidate->nama_wakil }}</td>
                                    {{-- Terakhir Action --}}
                                    <td>
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('candidates.edit', $candidate->id) }}"
                                           class="btn btn-sm"
                                           style="background-color: #0076C2; color: #FFFFFF; border: none;">
                                          Edit
                                        </a>
                                        {{-- Tombol Delete --}}
                                        <form action="{{ route('candidates.destroy', $candidate->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin hapus kandidat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm"
                                                    style="background-color: #FF2121; color: #FFFFFF; border: none;">
                                              Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">
                                        {{ $candidates->appends(Request::all())->links() }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
