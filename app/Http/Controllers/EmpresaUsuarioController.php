<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\EmpresaUsuario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmpresaUsuarioController extends Controller
{
    public function index(Empresa $empresa)
    {
        $asignaciones = $empresa->usuarios()->get();
        return view('usuarios.index', compact('empresa', 'asignaciones'));
    }

    public function store(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'rol' => 'required|in:administrador,contador,asistente',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'No existe ningún usuario registrado con ese correo. Pídele que se registre primero en /register.',
            ]);
        }

        if ($empresa->usuarios()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Ese usuario ya tiene acceso a esta empresa.',
            ]);
        }

        $empresa->usuarios()->attach($user->id, ['rol' => $data['rol']]);

        return redirect()->route('empresas.usuarios.index', $empresa)
            ->with('ok', "Se dio acceso a {$user->name} como {$data['rol']}.");
    }

    public function destroy(Empresa $empresa, EmpresaUsuario $empresaUsuario)
    {
        // Evita que un administrador se quite a sí mismo y deje la empresa sin admins
        $quedanAdmins = $empresa->usuarios()
            ->wherePivot('rol', 'administrador')
            ->where('user_id', '!=', $empresaUsuario->user_id)
            ->exists();

        if ($empresaUsuario->rol === 'administrador' && ! $quedanAdmins) {
            throw ValidationException::withMessages([
                'rol' => 'No puedes quitar al único administrador de la empresa.',
            ]);
        }

        $empresaUsuario->delete();

        return redirect()->route('empresas.usuarios.index', $empresa)->with('ok', 'Acceso removido.');
    }
}
