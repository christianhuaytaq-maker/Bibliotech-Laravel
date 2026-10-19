<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() { $usuarios = User::orderBy('id')->get(); return view('admin.users', compact('usuarios')); }
    public function create() { return view('admin.users_form'); }

    public function store(Request $request) {
        $request->validate(['nombres' => 'required', 'apellidos' => 'required', 'email' => 'required|email|unique:usuarios', 'documento' => 'required|unique:usuarios', 'rol' => 'required', 'password' => 'required|min:6']);
        User::create(['nombres' => $request->nombres, 'apellidos' => $request->apellidos, 'email' => $request->email, 'documento' => $request->documento, 'rol' => $request->rol, 'estado' => 'activo', 'password_hash' => Hash::make($request->password)]);
        return redirect()->route('admin.usuarios')->with('ok', 'Usuario creado.');
    }

    public function edit($id) { $usuario = User::findOrFail($id); return view('admin.users_form', compact('usuario')); }
    public function update(Request $request, $id) { $usuario = User::findOrFail($id); $usuario->update($request->only(['nombres', 'apellidos', 'email', 'documento', 'rol', 'estado'])); return redirect()->route('admin.usuarios')->with('ok', 'Usuario actualizado.'); }
    public function destroy($id) { User::findOrFail($id)->delete(); return redirect()->route('admin.usuarios')->with('ok', 'Usuario eliminado.'); }
}
