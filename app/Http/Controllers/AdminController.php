<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Calculamos el dinero total recaudado
        // Fíjate que ahora dice 'total_amount'
        $totalRevenue = Order::sum('total_amount');

        // 2. Contamos cuántas entradas se han emitido
        $ticketsSold = Ticket::count();

        // 3. Contamos cuántos eventos están publicados actualmente
        $activeEvents = Event::where('status', 'Published')->count();

        // 4. Traemos las últimas 5 compras para mostrarlas en una tabla rápida
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // 5. Retornamos la vista que te pasé en el mensaje anterior
        return view('admin.dashboard', compact('totalRevenue', 'ticketsSold', 'activeEvents', 'recentOrders'));
    }
}