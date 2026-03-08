@extends('layouts.admin')

@section('content')
<div class="font-display">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">stadium</span>
                Lista de Recintos
            </h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Gestión de locaciones y capacidad</p>
        </div>
        <a href="{{ route('admin.venues.create') }}" class="bg-[#FF6600] hover:bg-slate-900 text-white text-[10px] font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center gap-2">
            <span class="material-icons text-sm">add_location</span> Nuevo Recinto
        </a>
    </div>

    {{-- Alerta de Éxito --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl mb-6 flex items-center gap-3 text-[11px] font-black uppercase tracking-widest animate-fade-in-down shadow-sm">
            <span class="material-icons text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Alerta de Error --}}
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-100 text-rose-600 px-6 py-4 rounded-2xl mb-6 flex items-center gap-3 text-[11px] font-black uppercase tracking-widest animate-fade-in-down shadow-sm">
            <span class="material-icons text-lg">report_problem</span>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead class="bg-slate-50/80 backdrop-blur-sm">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Recinto</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Ciudad</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Dirección</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Capacidad</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($venues as $venue)
                    <tr class="hover:bg-slate-50/30 transition-all group">
                        {{-- Nombre e Icono Visual --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-[1.1rem] bg-orange-50 flex items-center justify-center transition-all duration-300 group-hover:scale-105">
                                    <span class="material-icons text-[#FF6600] text-xl">place</span>
                                </div>
                                <div>
                                    <div class="text-[13px] font-black text-slate-800 uppercase tracking-tight">{{ $venue->name }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold uppercase mt-0.5 tracking-widest">ID: #{{ $venue->id }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="text-[11px] font-bold text-slate-600 uppercase tracking-wide">{{ $venue->city }}</div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="text-[11px] text-slate-400 font-medium max-w-[200px] truncate italic">{{ $venue->address }}</div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-orange-50 border border-orange-100/50 shadow-sm shadow-orange-200/20">
                                <span class="material-icons text-[#FF6600] text-sm">groups</span>
                                <span class="text-[11px] font-black text-[#FF6600] tracking-tight">
                                    {{ number_format($venue->capacity) }}
                                </span>
                                <span class="text-[8px] font-bold text-orange-300 uppercase tracking-tighter ml-0.5">Max</span>
                            </div>
                        </td>

                        {{-- Botones de Accion Unificados --}}
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.venues.edit', $venue->id) }}" 
                                   class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white flex items-center justify-center transition-all duration-300 border border-slate-100 shadow-sm">
                                    <span class="material-icons text-base">edit</span>
                                </a>
                                
                                <form action="{{ route('admin.venues.destroy', $venue->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este recinto?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all duration-300 border border-slate-100 shadow-sm">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="w-20 h-20 mx-auto bg-slate-50 rounded-[2rem] flex items-center justify-center mb-4 text-slate-200">
                                <span class="material-icons text-4xl">location_off</span>
                            </div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">No hay recintos registrados</h3>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-1">Define las sedes de tus eventos</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($venues->hasPages())
            <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100">
                {{ $venues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection