<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
   public function store(Request $request): RedirectResponse
{
    // 1. Validar que vengan los datos correctos
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        'document_id' => ['required', 'string', 'max:20', 'unique:'.User::class], // Validación de cédula
        'phone' => ['required', 'string', 'max:20'], // Validación de teléfono
    ]);

    // 2. Crear al usuario en la base de datos
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'document_id' => $request->document_id,
        'phone' => $request->phone,
    ]);

    // 3. Asignar el rol de Cliente automáticamente (Asumiendo que usas Spatie)
    $user->assignRole('Cliente');

    event(new Registered($user));

    // 4. Iniciar sesión automáticamente
    Auth::login($user);

    // 5. Redirección Inteligente: Mandarlo a su panel (que crearemos en el paso 4)
    return redirect()->route('client.dashboard');
}
}