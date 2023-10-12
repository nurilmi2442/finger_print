<?php

namespace App\Http\Controllers;
use App\Models\Datausers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{

    public function pageUsers(Request $request)
    {
       $users = Datausers::all();

        if ($request->ajax()) {
            return response()->json(['data' =>$users, 'message' => 'Berhasil di dapat']);
        }

        return Inertia::render('Finger/Users', [
            'users' =>$users
        ]);
    }

    public function simpanUsers(Request $request)
    {


        if ($request->input('id')) {
           $users = Datausers::where("id", $request->input('id'))->first();
           $users->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => md5($request->input('password')),
            ]);
        } else {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|min:6',
            ]);
            $users = Datausers::create(
                [
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => $validatedData['password'],
                ]
            );
        }

        return response()->json(['message' => 'Data user berhasil disimpan']);
    }

    public function hapusUsers(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
        ]);

      $users= Datausers::findOrFail($validatedData['id']);

      $users->delete();

        return response()->json(['message' => 'Data proyek berhasil dihapus']);
    }
}
