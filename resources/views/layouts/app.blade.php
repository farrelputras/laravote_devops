<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name','Laravote') }}</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">

  <!-- DataTables CSS -->
  <link rel="stylesheet"
        href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">

  <style>
    /* Custom font styling for DataTables controls */
    .custom {
      font-size: 12px;
      font-family: Arial;
    }
    .topcustom, .bottomcustom {
      font-size: 12px;
      font-family: Arial;
      width: 100%;
    }

    /* Navbar custom */
    .navbar-laravel {
      background-color: #2C2E83;
    }
    .navbar-laravel .navbar-brand,
    .navbar-laravel .nav-link {
      color: #ffffff !important;
    }
    .navbar-laravel .nav-link:hover {
      color: #dddddd !important;
    }

    /* Login-card styling */
    .login-card {
      width: 644px;
      margin: 2rem auto;
      border: .5px solid rgba(0,0,0,0.66);
      border-radius: 7.6px;
      background: #fff;
    }
    .login-card .card-header {
      padding: 19px 33px;
      background: #F8B928;
      color: #2C2E83;
      font-weight: 600;
      border-bottom: none;
      border-top-left-radius: 7.6px;
      border-top-right-radius: 7.6px;
    }
    .login-card .card-body {
      padding: 16px 33px;
    }
    .login-card .form-control {
      width: 539px;
      max-width: 100%;
      height: 41px;
      border: .4px solid #000;
      border-radius: 13.5px;
      background: #FFF;
      padding: 0 .75rem;
      box-sizing: border-box;
    }

    /* Login buttons */
    .btn-login {
      width: 120px;
      padding: 10px 15px;
      background: #0076C2;
      color: #fff;
      border: none;
      border-radius: 10px;
      margin-right: 10px;
    }
    .btn-google {
      display: flex;
      align-items: center;
      width: 180px;
      padding: 10px 15px;
      background: #2A2F85;
      color: #fff;
      border: none;
      border-radius: 10px;
    }
    .btn-google .its-logo {
      height: 1em;
      margin-right: 8px;
    }

    /* Shared section/table styling */
    .section-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      background: #fff;
      margin-bottom: 1.5rem;
    }
    .section-header {
      background: #F8B928;
      color: #2C2E83;
      font-weight: 600;
      padding: .75rem 1.25rem;
      border-radius: 8px 8px 0 0;
    }
    .section-body {
      padding: 1rem 1.25rem;
    }
    .table tbody td {
      vertical-align: middle;
    }

    /* Hide DataTables “Processing…” overlay */
    .dataTables_processing {
      display: none !important;
    }

    /* DataTables top controls */
    .dt-top {
      display: flex !important;
      align-items: center;
      width: 100%;
      margin-top: 1rem;
    }
    .dt-top .dataTables_length { margin: 0; }
    .dt-top .dataTables_filter { margin-left: auto; }

    /* DataTables footer info & pagination */
    .bottomcustom {
      display: flex !important;
      justify-content: space-between;
      align-items: center;
      padding: .75rem 1.25rem;
    }

    /* Voting-page cards */
    .vote-card .card {
      display: flex;
      flex-direction: column;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 1rem;
    }
    .vote-card .card-header {
      border-bottom: none;
      padding: 1rem;
      text-align: center;
    }
    .vote-card .candidate-number {
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: .5rem;
    }
    .vote-card .candidate-names {
      font-size: 1rem;
      margin-bottom: 1rem;
    }
    .vote-card .card-body {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: flex-end;
      padding: 0;
    }
    .vote-card .card-body img {
      margin: auto;
      display: block;
    }
    .vote-card .card-footer {
      padding: 0;
    }
    .vote-card .btn-vote {
      width: 100%;
      border-radius: 0;
      background: #3490DC !important;
      color: #fff !important;
      font-weight: 600;
      text-transform: uppercase;
      border: none;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-md navbar-dark navbar-laravel">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name','Laravote') }}
      </a>
      <button class="navbar-toggler" type="button"
              data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
            @if(Route::has('register'))
              <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">Register</a>
              </li>
            @endif
          @else
            @if(Auth::user()->roles=='["ADMIN"]')
              <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">User Management</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('candidates.index') }}">Candidate Management</a>
              </li>

              <!-- START Voting Session -->
              <li class="nav-item">
                <form method="POST" action="{{ route('voting.session') }}" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-link nav-link">
                    Start Voting Session
                  </button>
                </form>
              </li>
              <!-- END Voting Session -->
              <li class="nav-item">
                <form method="POST"
                      action="{{ route('voting.session.end') }}"
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to end and reset the voting session?')">
                  @csrf
                  <button type="submit" class="btn btn-link nav-link text-danger">
                    End Voting Session
                  </button>
                </form>
              </li>

            @elseif(Auth::user()->roles=='["VOTER"]')
              <li class="nav-item">
                <a class="nav-link" href="{{ route('candidates.pilihan') }}">Voting</a>
              </li>
            @endif
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                @if(auth()->user()->photo)
                  <img src="{{ auth()->user()->photo }}"
                       width="32" height="32" style="margin-right:6px;">
                @endif
                {{ Auth::user()->name }}
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item"
                   href="{{ route('logout') }}"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                  Logout
                </a>
                <form id="logout-form"
                      action="{{ route('logout') }}"
                      method="POST" style="display:none;">
                  @csrf
                </form>
              </div>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    @yield('content')
  </main>

  <!-- JS: jQuery & Bootstrap & DataTables -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
          crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

  @stack('scripts')
  @yield('scripts')
</body>
</html>
