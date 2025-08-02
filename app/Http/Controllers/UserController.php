<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where("role", "STAFF")->get();
        return view("report.dashboard.user.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view("report.dashboard.user.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required",
            "password" => "required",
        ]);
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "role" => "STAFF",
        ]);
        return redirect()->route("user.index")->with("success", "Berhasil menambahkan user");
    }

    public function reset($id)
    {
        $user = User::findOrFail($id);
        $newPassword = substr($user->email, 0, 4);

        $user->password = bcrypt($newPassword);
        $user->save();

        return redirect()->route('user.index')->with('success', 'Password berhasil direset!');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->responses) {
            return redirect()->route('user.index')->with('failed', 'Pengguna tidak dapat dihapus karena pernah merespon.');
        }
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
