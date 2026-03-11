<?php

namespace App\Models;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
    // 1. Traemos los usuarios con sus roles
    $users = User::with('roles')->latest()->paginate(10);
    
    // 2. Traemos todos los roles disponibles para el selector
    $roles = Role::all(); 
    
    // CORRECCIÓN: Pasamos los nombres de las variables como strings
    return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        // Spatie: Sincroniza el rol (elimina los anteriores y pone el nuevo)
        $user->syncRoles($request->role);

        return back()->with('success', "Rol de {$user->name} actualizado a {$request->role}");
    }
}