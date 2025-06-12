{{-- resources/views/users/action.blade.php --}}
<form
    onsubmit="return confirm('Yakin Hapus Data User Ini ?')"
    action="{{ $url_destroy }}"
    method="post"
    class="d-flex justify-content-center align-items-center"  {{-- centering --}}
    style="gap:8px; margin:0;"                               {{-- jarak antar tombol --}}
>
    {{-- Tombol Edit --}}
    <a
        href="{{ $url_edit }}"
        class="btn btn-sm btn-outline-warning py-0"
        style="
          font-size: 0.9em;
          background-color: #0076C2 !important;
          color: #FFFFFF !important;
          border: none !important;
          padding: 4px 8px;
        "
    >
      Edit
    </a>

    @csrf
    @method('DELETE')

    {{-- Tombol Delete --}}
    <button
        type="submit"
        class="btn btn-sm btn-outline-danger py-0"
        style="
          font-size: 0.9em;
          background-color: #FF2121 !important;
          color: #FFFFFF !important;
          border: none !important;
          padding: 4px 8px;
        "
    >
      Delete
    </button>
</form>
