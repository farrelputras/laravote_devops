<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class VotingSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Gate::allows('manage-users')) return $next($request);

            abort(403, 'Anda Tidak memiliki Hak Akses');
        });
    }

    public function start()
    {
        // Set session to active
        \Session::put('voting_session_active', true);

        // Generate token only for eligible users
        $eligibleUsers = \App\User::where('is_eligible', true)->get();

        foreach ($eligibleUsers as $user) {
            $user->token = strtoupper(Str::random(6));
            $user->save();
        }

        return redirect()->back()->with('status', 'Sesi Voting Dimulai dan Token Telah Dibuat');
    }

    public function end()
    {
        // Reset session voting (jika ada)
        \Session::forget('voting_session_active');

        // Reset semua user VOTER (kecuali admin)
        \App\User::where('roles', '["VOTER"]')->update([
            'token' => null,
            'is_eligible' => 0,
            'status' => 'BELUM',
            'candidate_id' => null
        ]);
        return redirect()->back()->with('status', 'Sesi Voting telah diakhiri, semua data voting sudah direset.');
    }
}
