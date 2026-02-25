<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

// ==========================================
// 🌍 1. RUTAS FRONT OFFICE (Públicas) -> JEAN
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Aquí Jean agregará la ruta para ver el detalle de un evento (ej. /evento/{slug})


// ==========================================
// 🏢 2. RUTAS BACK OFFICE (Panel de Admin) -> ÁNGEL / LUIS
// ==========================================
// NOTA: Todo este grupo está protegido. Solo entran usuarios logueados que sean SuperAdmin u Organizador.

Route::middleware(['auth', 'role:SuperAdmin|Organizador'])->prefix('admin')->name('admin.')->group(function () {
    
    // El Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // CRUD de Eventos (Ángel)
    Route::resource('events', EventController::class);

    // Aquí Ángel colocará sus rutas del CRUD de eventos (Ej: Route::resource('events', EventController::class);)
    // Aquí el Programador Extra colocará las rutas de Venues y Categories.

});


// ==========================================
// ⚙️ 3. RUTAS DE PERFIL Y AUTH -> ELÍAS
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';