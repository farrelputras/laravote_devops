<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;            // ← Ditambahkan
use DataTables;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)   // ← Signature diubah untuk menerima Request
    {
        // Tambahan cek has('draw') agar DataTables JSON pasti dikembalikan
        if ($request->ajax() || $request->wantsJson() || $request->has('draw')) {
            $users = \App\User::query();
            return DataTables::of($users)
                ->addIndexColumn()
                // Tambahan kolom status dengan HTML badge
                ->addColumn('status', function ($u) {
                    $s = strtoupper($u->status);
                    if ($s === 'SUDAH') {
                        return "<span style=\"
                                    background-color:#1CDF50;
                                    color:#FFFFFF;
                                    padding:2px 6px;
                                    border-radius:4px;
                                    display:inline-block;
                                 \">{$u->status}</span>";
                    }
                    return "<span style=\"
                                    background-color:#FF6200;
                                    color:#FFFFFF;
                                    padding:2px 6px;
                                    border-radius:4px;
                                    display:inline-block;
                                 \">{$u->status}</span>";
                })
                ->addColumn('action', function ($u) {
                    return view('users.action', [
                        'users'       => $u,
                        'url_edit'    => route('users.edit',    $u->id),
                        'url_destroy' => route('users.destroy', $u->id),
                    ]);
                })
                // Izinkan rendering mentah untuk kolom status & action
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // Bukan AJAX/DataTables: tampilkan view HTML
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            "name"     => "required|min:5|max:100",
            "nik"      => "required|digits_between:16,16|unique:users",
            "phone"    => "required|digits_between:10,12",
            "address"  => "required|min:10|max:255",
            "email"    => "required|email|unique:users",
            "password" => "required|min:5|max:35",
        ])->validate();

        $new_user = new \App\User;
        $new_user->name     = $request->get('name');
        $new_user->nik      = $request->get('nik');
        $new_user->roles    = json_encode(['VOTER']);
        $new_user->address  = $request->get('address');
        $new_user->phone    = $request->get('phone');
        $new_user->email    = $request->get('email');
        $new_user->password = \Hash::make($request->get('password'));
        $new_user->status   = "BELUM";
        $new_user->save();

        return redirect()->route('users.create')
                         ->with('status', 'User successfully Created');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response('', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = \App\User::findOrFail($id);
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        \Validator::make($request->all(), [
            "name"    => "required|min:5|max:100",
            "nik"     => "required|digits_between:16,16",
            "phone"   => "required|digits_between:10,12",
            "address" => "required|min:10|max:255",
            "email"   => "required|email",
        ])->validate();

        $user = \App\User::findOrFail($id);
        $user->name    = $request->get('name');
        $user->nik     = $request->get('nik');
        $user->address = $request->get('address');
        $user->phone   = $request->get('phone');
        $user->email   = $request->get('email');
        $user->save();

        return redirect()->route('users.index')
                         ->with('status', 'User successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // DEMO
        if ($id === 'all') {
        \App\User::whereJsonDoesntContain('roles', 'ADMIN')->delete();
        return redirect()->route('users.index')->with('status', 'Semua user berhasil dihapus');
        }
        
        $user = \App\User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
                         ->with('status', 'User successfully Deleted');
    }

    public function toggleEligible($id)
    {
        $user = \App\User::findOrFail($id);
        $user->is_eligible = !$user->is_eligible;

        if (!$user->is_eligible) {
            $user->token = null;
        }

        $user->save();

        return redirect()->back();
    }
}
