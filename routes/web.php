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

// Prueba ultra simple
Route::get('/vitrina', function () {
    return view('admin.events.show', ['nombreEvento' => 'Evento de Prueba']);
});

// ==========================================
// 🌍 1. RUTAS FRONT OFFICE (Públicas) -> JEAN / ELÍAS
// ==========================================
Route::get('/', [StoreController::class, 'landing'])->name('home');
Route::get('/eventos', [StoreController::class, 'index'])->name('events.index');
Route::get('/eventos/{id}', [StoreController::class, 'show'])->name('events.show');


// ==========================================
// 🏢 2. RUTAS BACK OFFICE (Panel de Admin) -> ÁNGEL / LUIS
// ==========================================
Route::middleware(['auth', 'role:SuperAdmin|Organizador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('events', EventController::class);
    Route::resource('venues', VenueController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('venues/{venue}/zones-list', [VenueController::class, 'getZones'])->name('venues.zones');
    Route::get('/tickets/{id}/verify', [AdminController::class, 'verifyTicket'])->name('tickets.verify');
    Route::post('/tickets/{id}/mark-used', [AdminController::class, 'markTicketAsUsed'])->name('tickets.markUsed');
    
    // Taquilla Virtual
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


// ==========================================
// 🎟️ 4. RUTAS DEL CLIENTE (Usuarios normales) -> LUIS
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/mi-panel', [ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/mi-panel/ticket/{id}/descargar', [ClientController::class, 'downloadTicket'])->name('client.ticket.download');
    Route::get('/checkout/{event}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/summary', [CheckoutController::class, 'summary'])->name('checkout.summary');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});


// ==========================================
// 🌐 5. INSTITUCIONAL, SOPORTE Y CONTACTO -> JEAN
// ==========================================
Route::get('/preguntas-frecuentes', [\App\Http\Controllers\PageController::class, 'faq'])->name('pages.faq');
Route::get('/contacto', [\App\Http\Controllers\PageController::class, 'contact'])->name('pages.contact');
Route::post('/contacto', [\App\Http\Controllers\PageController::class, 'sendContact'])->name('pages.contact.send');
Route::get('/gracias', [\App\Http\Controllers\PageController::class, 'thanks'])->name('pages.thanks');
Route::get('/terminos-y-condiciones', [\App\Http\Controllers\PageController::class, 'terms'])->name('pages.terms');
Route::get('/politicas-de-privacidad', [\App\Http\Controllers\PageController::class, 'privacy'])->name('pages.privacy');

require __DIR__.'/auth.php';