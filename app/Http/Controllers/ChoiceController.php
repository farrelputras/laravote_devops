<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Gate::allows('manage-pilihan')) return $next($request);

            abort(403, 'Anda Tidak memiliki Hak Akses');
        });
    }

    public function pilihan()
    {
        $user = auth()->user();

        // Check if token is null
        if (is_null($user->token)) {
            return redirect()->back()->with('status', 'Sesi voting belum dimulai atau Anda belum diizinkan memilih.');
        }

        $candidate = \App\Candidate::paginate(2);
        return view('pilihan.index', ['candidates' => $candidate]);
    }

    public function pilih(Request $request, $id)
    {
        $user = \App\User::findOrFail($id);

        $inputToken = $request->input('token');

        // Check 1: Token is required
        if (!$inputToken) {
            return redirect()->back()->with('status', 'Anda Tidak Berhak Memilih');
        }

        // Check 2: Token does not match
        if ($user->token !== $inputToken) {
            return redirect()->back()->with('status', 'Token Tidak Sesuai');
        }

        // Check 3: Already voted
        if ($user->status === 'SUDAH') {
            return redirect()->back()->with('status', 'Anda Sudah Memilih');
        }

        // All checks passed → allow voting
        $user->candidate_id = $request->input('candidate_id');
        $user->status = 'SUDAH';
        $user->save();

        return redirect()->route('candidates.pilihan')->with('status', 'Pilihan Anda berhasil dicatat.');
    }
}
