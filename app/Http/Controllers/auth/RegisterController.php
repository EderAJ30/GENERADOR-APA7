<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
  public function showRegistrationForm(): View
  {
    return view('auth.register');
  }

  public function register(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'nombre_usuario'  => ['required', 'string', 'max:100'],
      'paterno_usuario' => ['required', 'string', 'max:100'],
      'materno_usuario' => ['required', 'string', 'max:100'],
      'email'           => ['required', 'string', 'email', 'max:100', 'unique:usuarios,email'],
      'password'        => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $usuario = Usuario::create([
      'nombre_usuario'  => $validated['nombre_usuario'],
      'paterno_usuario' => $validated['paterno_usuario'],
      'materno_usuario' => $validated['materno_usuario'],
      'email'           => $validated['email'],
      'password'        => Hash::make($validated['password']),
      'rol_id'          => 2,
      'estatus'         => 1,
    ]);

    Auth::login($usuario);

    return redirect()->route('dashboard')->with('success', 'Cuenta creada exitosamente.');
  }
}
