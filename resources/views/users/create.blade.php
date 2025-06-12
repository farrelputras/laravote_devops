@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      {{-- Card wrapper tetap utuh, hanya menambahkan class section-card --}}
      <div class="card section-card">
        {{-- Header: background kuning & teks baru --}}
        <div class="card-header section-header d-flex justify-content-between align-items-center"
             style="background-color: #F8B928; color: #2A2F85;">
          {{-- Teks diganti tanpa menghapus --}}
          <h3 class="card-title mb-0" style="font-size:1.25rem;">
            Add New User Data
          </h3>
          {{-- Tombol Back (opsional) --}}
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

          <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" name="name" type="text"
                     class="form-control {{ $errors->has('name')?'is-invalid':'' }}"
                     value="{{ old('name') }}" placeholder="Enter name">
              <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            </div>

            <div class="form-group">
              <label for="nik">NIK</label>
              <input id="nik" name="nik" type="text"
                     class="form-control {{ $errors->has('nik')?'is-invalid':'' }}"
                     value="{{ old('nik') }}" placeholder="Enter NIK">
              <div class="invalid-feedback">{{ $errors->first('nik') }}</div>
            </div>

            <div class="form-group">
              <label for="phone">Phone</label>
              <input id="phone" name="phone" type="text"
                     class="form-control {{ $errors->has('phone')?'is-invalid':'' }}"
                     value="{{ old('phone') }}" placeholder="Enter phone">
              <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
            </div>

            <div class="form-group">
              <label for="address">Address</label>
              <input id="address" name="address" type="text"
                     class="form-control {{ $errors->has('address')?'is-invalid':'' }}"
                     value="{{ old('address') }}" placeholder="Enter address">
              <div class="invalid-feedback">{{ $errors->first('address') }}</div>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" name="email" type="email"
                     class="form-control {{ $errors->has('email')?'is-invalid':'' }}"
                     value="{{ old('email') }}" placeholder="Enter email">
              <div class="invalid-feedback">{{ $errors->first('email') }}</div>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input id="password" name="password" type="password"
                     class="form-control {{ $errors->has('password')?'is-invalid':'' }}"
                     placeholder="Enter password">
              <div class="invalid-feedback">{{ $errors->first('password') }}</div>
            </div>

            {{-- Tombol Save: float ke kanan, warna baru, teks "Save" --}}
            <div class="form-group">
              <button type="submit"
                      class="btn btn-sm"
                      style="background-color: #2A2F85; color: #FFFFFF; border: none; float: right;">
                Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
