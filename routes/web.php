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
use App\Http\Controllers\Admin\UserController;

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
Route::middleware(['auth', 'role:SuperAdmin|Organizador|Validador'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard compartido
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // --- EVENTOS: Ambos pueden ver, crear y editar (filtramos por dueño en el Controller) ---
    Route::resource('events', EventController::class);

    // --- RECINTOS Y CATEGORÍAS: Acceso de lectura para todos, gestión solo SuperAdmin ---
    // 1. Rutas de lectura (Abiertas para Organizador y SuperAdmin)
    Route::get('venues', [VenueController::class, 'index'])->name('venues.index');
    Route::get('venues/{venue}', [VenueController::class, 'show'])->name('venues.show');
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('venues/{venue}/zones-list', [VenueController::class, 'getZones'])->name('venues.zones');

    // 2. Rutas de escritura (SOLO SuperAdmin)
    Route::middleware(['role:SuperAdmin'])->group(function () {
        // Venues (excepto index y show)
        Route::get('venues/create', [VenueController::class, 'create'])->name('venues.create');
        Route::post('venues', [VenueController::class, 'store'])->name('venues.store');
        Route::get('venues/{venue}/edit', [VenueController::class, 'edit'])->name('venues.edit');
        Route::put('venues/{venue}', [VenueController::class, 'update'])->name('venues.update');
        Route::delete('venues/{venue}', [VenueController::class, 'destroy'])->name('venues.destroy');

        // Categorías (excepto index)
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Gestión de Usuarios (Solo SuperAdmin)
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

       // Busca la ruta que pegaste y déjala EXACTAMENTE así:
Route::get('/auditoria', [\App\Http\Controllers\AuditController::class, 'index'])->name('audits.index');
    });

    // --- TAQUILLA VIRTUAL: Solo SuperAdmin y Validador ---
    Route::middleware(['role:SuperAdmin|Validador'])->group(function () {
        Route::get('/pagos/pendientes', [AdminController::class, 'pendingOrders'])->name('orders.pending');
        Route::post('/pagos/{order}/aprobar', [AdminController::class, 'approveOrder'])->name('orders.approve');
        Route::post('/pagos/{order}/rechazar', [AdminController::class, 'rejectOrder'])->name('orders.reject');
        
        // Validación física de tickets
        Route::get('/tickets/{id}/verify', [AdminController::class, 'verifyTicket'])->name('tickets.verify');
        Route::post('/tickets/{id}/mark-used', [AdminController::class, 'markTicketAsUsed'])->name('tickets.markUsed');
        
    });
  
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