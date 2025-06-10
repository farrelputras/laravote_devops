@extends('layouts.app')

@section('content')
<script>
    function promptForToken(candidateId) {
        const token = prompt("Masukkan token Anda untuk memilih:");

        if (!token) {
            alert("Anda Tidak Berhak Memilih");
            return;
        }

        // Create and submit a hidden form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${{{ auth()->id() }}}/pilih`;

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
    <div class="row">
        <div class="col-md-12">
            @if(session('status'))
            <div class="alert alert-success">
                {{session('status')}}
            </div>
            @endif
            @if(Auth::user()->status == "BELUM")
            <form enctype="multipart/form-data" action="{{route('users.pilih',['id'=>Auth::user()->id])}}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT" class="form-control">
                <div class="card-group">
                    @foreach ($candidates as $candidate)
                    <div class="card">
                        <h1 align="center">{{$candidate->id}}</h1>
                        <img class="card-img-top" src="{{asset('storage/'.$candidate->photo_paslon)}}" alt="Card image cap">
                        <div class="card-body">
                            <h5 align="center" class="card-title">{{$candidate->nama_ketua}} dan {{$candidate->nama_wakil}}</h5>
                        </div>
                        <div class="form-group" align="center">
                            <button type="button" class="btn btn-primary" onclick="promptForToken({{ $candidate->id }})">PILIH</button>

                        </div>
                    </div>

                    @endforeach
                </div>
            </form>
            @else
            <h1 align="center">SUDAH MEMILIH</h1>
            @endif
        </div>
    </div>
</div>
@endsection