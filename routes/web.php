<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\CategoryController;

// Prueba ultra simple (Puedes borrarla después)
Route::get('/vitrina', function () {
    return view('admin.events.show', ['nombreEvento' => 'Evento de Prueba']);
});

// ==========================================
// 🌍 1. RUTAS FRONT OFFICE (Públicas) -> JEAN / ELÍAS
// ==========================================

// La Landing Page (welcome)
Route::get('/', [StoreController::class, 'landing'])->name('home');

// El Catálogo Completo
// MODIFICACIÓN REALIZADA: Ahora apunta a EventController@list para que funcionen los filtros
Route::get('/eventos', [EventController::class, 'list'])->name('events.index');

// El Detalle del Evento
// Modificación: Esta ruta llamará a la función 'show' en StoreController
Route::get('/eventos/{id}', [StoreController::class, 'show'])->name('events.show');


// ==========================================
// 🏢 2. RUTAS BACK OFFICE (Panel de Admin) -> ÁNGEL / LUIS
// ==========================================
// NOTA: Todo este grupo está protegido. Solo entran usuarios logueados que sean SuperAdmin u Organizador.

Route::middleware(['auth', 'role:SuperAdmin|Organizador'])->prefix('admin')->name('admin.')->group(function () {
    
    // AQUÍ ESTÁ EL CAMBIO: El Dashboard principal ahora apunta a tu AdminController
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD de Eventos (Ángel)
    Route::resource('events', EventController::class);

    // CRUD de Recintos (Ángel)
    Route::resource('venues', VenueController::class);

    // CRUD de Categorías (Ángel)
    Route::resource('categories', CategoryController::class);

    // Ruta para obtener las zonas de un recinto vía AJAX (JSON)
    Route::get('venues/{venue}/zones-list', [VenueController::class, 'getZones'])->name('venues.zones');

    // Aquí Ángel colocará sus rutas del CRUD de eventos adicionales
    // Aquí el Programador Extra colocará las rutas de Venues y Categories.
   Route::get('/tickets/{id}/verify', [AdminController::class, 'verifyTicket'])->name('tickets.verify');
    Route::post('/tickets/{id}/mark-used', [AdminController::class, 'markTicketAsUsed'])->name('tickets.markUsed');

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
    
    // Tu panel (Movido a la zona segura)
    Route::get('/mi-panel', [ClientController::class, 'dashboard'])->name('client.dashboard');
    
    // LA RUTA PARA DESCARGAR EL PDF (Movida a la zona segura)
    Route::get('/mi-panel/ticket/{id}/descargar', [ClientController::class, 'downloadTicket'])->name('client.ticket.download');
    
    // LA PANTALLA DEL CAJERO (Muestra el formulario)
    Route::get('/checkout/{event}', [CheckoutController::class, 'show'])->name('checkout.show');
    
    // EL MOTOR DE COMPRAS (Procesa el formulario)
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

require __DIR__.'/auth.php';