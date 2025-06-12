<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\User;
use Session;

class VotingSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Gate::allows('manage-users')) {
                return $next($request);
            }

            abort(403, 'Anda Tidak memiliki Hak Akses');
        });
    }

    public function start()
    {
        // Aktifkan sesi voting
        Session::put('voting_session_active', true);

        // Generate token untuk user yang eligible
        $eligibleUsers = User::where('is_eligible', true)->get();

        foreach ($eligibleUsers as $user) {
            $user->token = strtoupper(Str::random(6));
            $user->save();
        }

        return redirect()->back()->with('status', 'Sesi Voting Dimulai dan Token Telah Dibuat');
    }

    public function end()
    {
        // Matikan sesi voting
        Session::forget('voting_session_active');

        // Reset semua user VOTER (tidak termasuk ADMIN)
        User::whereJsonContains('roles', 'VOTER')->update([
            'token' => null,
            'is_eligible' => false,
            'status' => 'BELUM',
            'candidate_id' => null
        ]);

        return redirect()->back()->with('status', 'Sesi Voting telah diakhiri, semua data voting sudah direset.');
    }
}
