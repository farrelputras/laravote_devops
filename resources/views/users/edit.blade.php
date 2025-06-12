@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card section-card">

                    {{-- Header --}}
                    <div class="card-header section-header d-flex justify-content-between align-items-center"
                         style="background: #F8B928; color: #2A2F85; font-size: 1.25rem;">
                        <span>Edit Users Data (Voters)</span>
                        <a href="{{ route('users.index') }}"
                           class="btn btn-sm"
                           style="background-color: #2A2F85; color: #FFFFFF; border: none;">
                            Back
                        </a>
                    </div>

                    <div class="card-body section-body">
                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form action="{{ route('users.update', ['id' => $user->id]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', $user->name) }}"
                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       placeholder="Masukkan nama lengkap">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input type="text"
                                       name="nik"
                                       id="nik"
                                       value="{{ old('nik', $user->nik) }}"
                                       class="form-control {{ $errors->has('nik') ? 'is-invalid' : '' }}"
                                       placeholder="Masukkan NIK anda">
                                @if ($errors->has('nik'))
                                    <div class="invalid-feedback">{{ $errors->first('nik') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <textarea name="address"
                                          id="address"
                                          rows="4"
                                          class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                          placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                                @if ($errors->has('address'))
                                    <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text"
                                       name="phone"
                                       id="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                       placeholder="Masukkan nomor telepon">
                                @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       placeholder="Masukkan email">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
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
