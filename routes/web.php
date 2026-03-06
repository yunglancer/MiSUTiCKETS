<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StoreController;

// ==========================================
// 🌍 1. RUTAS FRONT OFFICE (Públicas) -> JEAN / ELÍAS
// ==========================================

// La Landing Page (welcome)
Route::get('/', [StoreController::class, 'landing'])->name('home');

// El Catálogo Completo
Route::get('/eventos', [StoreController::class, 'index'])->name('events.index');

// El Detalle del Evento
Route::get('/eventos/{id}', [StoreController::class, 'show'])->name('events.show');
// Tu panel
    Route::get('/mi-panel', [ClientController::class, 'dashboard'])->name('client.dashboard');
    
    // LA NUEVA RUTA PARA DESCARGAR EL PDF
    Route::get('/mi-panel/ticket/{id}/descargar', [ClientController::class, 'downloadTicket'])->name('client.ticket.download');

// ==========================================
// 🏢 2. RUTAS BACK OFFICE (Panel de Admin) -> ÁNGEL / LUIS
// ==========================================
// NOTA: Todo este grupo está protegido. Solo entran usuarios logueados que sean SuperAdmin u Organizador.

Route::middleware(['auth', 'role:SuperAdmin|Organizador'])->prefix('admin')->name('admin.')->group(function () {
    
    // El Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard'); // Asegúrate de que esta vista exista
    })->name('dashboard');

    // CRUD de Eventos (Ángel)
    Route::resource('events', EventController::class);

    // Aquí Ángel colocará sus rutas del CRUD de eventos adicionales
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


// ==========================================
// 🎟️ 4. RUTAS DEL CLIENTE (Usuarios normales) -> LUIS
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/mi-panel', [ClientController::class, 'dashboard'])->name('client.dashboard');
    
    // LA PANTALLA DEL CAJERO (Muestra el formulario)
    Route::get('/checkout/{event}', [CheckoutController::class, 'show'])->name('checkout.show');
    
    // EL MOTOR DE COMPRAS (Procesa el formulario)
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

require __DIR__.'/auth.php';