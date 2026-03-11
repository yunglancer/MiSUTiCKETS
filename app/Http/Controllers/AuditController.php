<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit; // El modelo que viene con el paquete

class AuditController extends Controller
{
    public function index()
    {
        // Traemos todos los registros de auditoría ordenados por los más recientes
        // E incluimos la relación 'user' para saber quién hizo el cambio
        $audits = Audit::with('user')->latest()->paginate(20);

        return view('admin.audits.index', compact('audits'));
    }
}