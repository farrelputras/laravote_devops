@extends('layouts.app')

@section('content')
<script>
    const userId = {{ auth()->id() }};
    function promptForToken(candidateId) {
        const token = prompt("Masukkan token Anda untuk memilih:");

        if (!token) {
            alert("Anda Tidak Berhak Memilih");
            return;
        }

        // Create and submit a hidden form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${userId}/pilih`;

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PUT';

        const candidate = document.createElement('input');
        candidate.type = 'hidden';
        candidate.name = 'candidate_id';
        candidate.value = candidateId;

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = 'token';
        tokenInput.value = token;

        form.appendChild(csrf);
        form.appendChild(method);
        form.appendChild(candidate);
        form.appendChild(tokenInput);

        document.body.appendChild(form);
        form.submit();
    }
</script>

<div class="container">
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if(Auth::user()->status === "BELUM")
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
              <button type="button"
                      class="btn btn-vote"
                      onclick="promptForToken({{ $candidate->id }})">
                Vote
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <h1 class="text-center mt-5">SUDAH MEMILIH</h1>
  @endif
</div>
@endsection
