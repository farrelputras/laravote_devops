<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Candidate;

class ChoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek token
        if (!$user->token || !$user->is_eligible || $user->status === 'SUDAH') {
            return redirect()->route('home')->with('status', 'Anda Tidak Berhak Memilih');
        }

        $candidates = Candidate::all();
        return view('pilihan.index', compact('candidates'));
    }

    public function pilih(Request $request, $id)
    {
        $request->validate([
            'token' => 'required|string',
            'candidate_id' => 'required|exists:candidates,id'
        ]);

        $user = User::findOrFail($id);

        // Validasi token & status
        if (
            $user->token !== $request->token ||
            !$user->is_eligible ||
            $user->status === 'SUDAH'
        ) {
            return redirect()->route('home')->with('status', 'Anda Tidak Berhak Memilih');
        }

        $user->update([
            'status' => 'SUDAH',
            'candidate_id' => $request->candidate_id,
        ]);

        return redirect()->route('candidates.pilihan')->with('status', 'Pilihan Anda berhasil dicatat.');
    }
}
