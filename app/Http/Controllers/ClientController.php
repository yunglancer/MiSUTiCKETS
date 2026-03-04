<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        // 1. Identificamos quién es el usuario logueado
        $user = Auth::user();

        // 2. Buscamos sus tickets y de paso nos traemos los datos del evento y la orden
        // 'latest()' los ordena del más nuevo al más viejo
        $tickets = $user->tickets()->with(['event', 'order'])->latest()->get();

        // 3. Buscamos su historial de compras
        $orders = $user->orders()->latest()->get();

        // 4. Mandamos todo eso a tu vista del panel
        return view('client.dashboard', compact('tickets', 'orders'));
    }
}
