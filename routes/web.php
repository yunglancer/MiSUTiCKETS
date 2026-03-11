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

// ==========================================
// 🏢 2. RUTAS BACK OFFICE (Panel de Admin) -> ÁNGEL / LUIS
// ==========================================
Route::middleware(['auth', 'role:SuperAdmin|Organizador'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUDs de Ángel
    Route::resource('events', EventController::class);
    Route::resource('venues', VenueController::class);
    Route::resource('categories', CategoryController::class);

    // Ruta AJAX para zonas
    Route::get('venues/{venue}/zones-list', [VenueController::class, 'getZones'])->name('venues.zones');

    // Escáner y Verificación de Tickets
    Route::get('/tickets/{id}/verify', [AdminController::class, 'verifyTicket'])->name('tickets.verify');
    Route::post('/tickets/{id}/mark-used', [AdminController::class, 'markTicketAsUsed'])->name('tickets.markUsed');

    // 💰 TAQUILLA VIRTUAL (Aprobación de Pagos)
    Route::get('/pagos/pendientes', [AdminController::class, 'pendingOrders'])->name('orders.pending');
    Route::post('/pagos/{order}/aprobar', [AdminController::class, 'approveOrder'])->name('orders.approve');
    Route::post('/pagos/{order}/rechazar', [AdminController::class, 'rejectOrder'])->name('orders.reject');

});

// ==========================================
// ⚙️ 3. RUTAS DE PERFIL Y AUTH -> ELÍAS
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/// ==========================================
// 🎟️ 4. RUTAS DEL CLIENTE (Usuarios normales) -> LUIS
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Tu panel (Movido a la zona segura)
    Route::get('/mi-panel', [ClientController::class, 'dashboard'])->name('client.dashboard');
    
    // LA RUTA PARA DESCARGAR EL PDF (Movida a la zona segura)
    Route::get('/mi-panel/ticket/{id}/descargar', [ClientController::class, 'downloadTicket'])->name('client.ticket.download');
    
    // --- EL NUEVO FLUJO DE CHECKOUT DE 2 PÁGINAS ---
    
    // PASO 1: LA PANTALLA DEL SELECTOR DE ENTRADAS
    Route::get('/checkout/{event}', [CheckoutController::class, 'show'])->name('checkout.show');
    
    // PASO 2: EL CARRITO DE COMPRAS Y MÉTODO DE PAGO (¡Aquí está la nueva ruta!)
    Route::post('/checkout/summary', [CheckoutController::class, 'summary'])->name('checkout.summary');
    
    // PASO 3: EL MOTOR DE COMPRAS (Procesa el pago final en la BD)
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});
require __DIR__.'/auth.php';