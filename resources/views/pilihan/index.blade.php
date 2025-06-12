@extends('layouts.app')

@section('content')
<div class="container">

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if(Auth::user()->status === "BELUM")
    <form method="POST" action="{{ route('users.pilih', Auth::user()->id) }}">
      @csrf
      @method('PUT')

      <div class="row vote-cards">
        @foreach($candidates as $candidate)
          <div class="col-md-6 mb-4 vote-card">
            <div class="card h-100">
              {{-- Header with number & names --}}
              <div class="card-header text-center">
                <div class="candidate-number">{{ $candidate->id }}</div>
                <div class="candidate-names">
                  {{ $candidate->nama_ketua }} &amp; {{ $candidate->nama_wakil }}
                </div>
              </div>

              {{-- Image --}}
              <div class="card-body p-0">
                @if($candidate->photo_paslon)
                  <img src="{{ asset('storage/'.$candidate->photo_paslon) }}"
                       class="img-fluid" alt="Photo Paslon">
                @endif
              </div>

              {{-- Vote button flush at bottom --}}
              <div class="card-footer">
                <button type="submit"
                        name="candidate_id"
                        value="{{ $candidate->id }}"
                        class="btn btn-vote">
                  Vote
                </button>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </form>
  @else
    <h1 class="text-center mt-5">SUDAH MEMILIH</h1>
  @endif

</div>
@endsection
