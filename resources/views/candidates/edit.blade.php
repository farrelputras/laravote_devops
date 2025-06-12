@extends('layouts.app')
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card section-card">

                    {{-- Header --}}
                    <div class="card-header section-header d-flex justify-content-between align-items-center"
                         style="background: #F8B928; color: #2A2F85; font-size: 1.25rem;">
                        <span>Edit Candidates Data</span>
                        <a href="{{ route('candidates.index') }}"
                           class="btn btn-sm"
                           style="background-color: #2A2F85; color: #FFFFFF; border: none;">
                            Back
                        </a>
                    </div>

                    <div class="card-body section-body">
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form enctype="multipart/form-data"
                              action="{{ route('candidates.update', ['id' => $candidate->id]) }}"
                              method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nama_ketua">Nama Ketua</label>
                                <input type="text"
                                       name="nama_ketua"
                                       id="nama_ketua"
                                       value="{{ old('nama_ketua', $candidate->nama_ketua) }}"
                                       class="form-control {{ $errors->has('nama_ketua') ? 'is-invalid' : '' }}"
                                       placeholder="Masukkan Nama Ketua">
                                @if ($errors->has('nama_ketua'))
                                    <div class="invalid-feedback">{{ $errors->first('nama_ketua') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="nama_wakil">Nama Wakil</label>
                                <input type="text"
                                       name="nama_wakil"
                                       id="nama_wakil"
                                       value="{{ old('nama_wakil', $candidate->nama_wakil) }}"
                                       class="form-control {{ $errors->has('nama_wakil') ? 'is-invalid' : '' }}"
                                       placeholder="Masukkan Nama Wakil">
                                @if ($errors->has('nama_wakil'))
                                    <div class="invalid-feedback">{{ $errors->first('nama_wakil') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="visi">Visi</label>
                                <textarea name="visi"
                                          id="visi"
                                          class="ckeditor form-control {{ $errors->has('visi') ? 'is-invalid' : '' }}"
                                          placeholder="Tulis visi">{{ old('visi', $candidate->visi) }}</textarea>
                                @if ($errors->has('visi'))
                                    <div class="invalid-feedback">{{ $errors->first('visi') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="misi">Misi</label>
                                <textarea name="misi"
                                          id="misi"
                                          class="ckeditor form-control {{ $errors->has('misi') ? 'is-invalid' : '' }}"
                                          placeholder="Tulis misi">{{ old('misi', $candidate->misi) }}</textarea>
                                @if ($errors->has('misi'))
                                    <div class="invalid-feedback">{{ $errors->first('misi') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="program_kerja">Program Kerja</label>
                                <textarea name="program_kerja"
                                          id="program_kerja"
                                          class="ckeditor form-control {{ $errors->has('program_kerja') ? 'is-invalid' : '' }}"
                                          placeholder="Tulis program kerja">{{ old('program_kerja', $candidate->program_kerja) }}</textarea>
                                @if ($errors->has('program_kerja'))
                                    <div class="invalid-feedback">{{ $errors->first('program_kerja') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="photo_paslon">Foto Pasangan Calon</label><br>
                                @if ($candidate->photo_paslon)
                                    <img src="{{ asset('storage/'.$candidate->photo_paslon) }}"
                                         width="100" class="mb-2" alt="Foto Paslon">
                                @else
                                    <p>No Photo</p>
                                @endif
                                <input type="file"
                                       name="photo_paslon"
                                       id="photo_paslon"
                                       class="form-control {{ $errors->has('photo_paslon') ? 'is-invalid' : '' }}">
                                @if ($errors->has('photo_paslon'))
                                    <div class="invalid-feedback">{{ $errors->first('photo_paslon') }}</div>
                                @endif
                            </div>

                            {{-- Tombol Save --}}
                            <div class="form-group text-right">
                                <button type="submit"
                                        class="btn"
                                        style="background-color: #2A2F85; color: #FFFFFF; border: none;">
                                    Update
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
