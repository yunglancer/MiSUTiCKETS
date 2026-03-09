@extends('layouts.admin')

@section('content')
<div class="font-display">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">event_note</span>
                Lista de Eventos
            </h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Gestión centralizada de catálogo</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="bg-[#FF6600] hover:bg-slate-900 text-white text-[10px] font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center gap-2">
            <span class="material-icons text-sm">add</span> Crear Nuevo Evento
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl mb-6 flex items-center gap-3 text-[11px] font-black uppercase tracking-widest animate-fade-in-down">
            <span class="material-icons text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead class="bg-slate-50/80 backdrop-blur-sm">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Info. Evento</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Categoría / Lugar</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Programación</th>
                        <th class="px-6 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Estado</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($events as $event)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        {{-- Columna: Título e Imagen --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 border-2 border-white shadow-sm flex-shrink-0 group-hover:shadow-md transition-all">
                                    @if($event->image_path)
                                        <img src="{{ asset('storage/' . $event->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <span class="material-icons text-xl">image_not_supported</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-[13px] font-black text-slate-800 leading-tight mb-1 flex items-center gap-2 uppercase tracking-tight">
                                        {{ $event->title }}
                                        @if($event->is_featured)
                                            <span class="flex h-2 w-2 rounded-full bg-[#FF6600] animate-pulse" title="Destacado"></span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest bg-slate-50 px-2 py-0.5 rounded">#{{ $event->id }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Columna: Categoría y Lugar --}}
                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-icons text-slate-300 text-[14px]">sell</span>
                                    <span class="text-[11px] font-bold text-slate-600 uppercase">{{ $event->category->name ?? 'Sin Categoría' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="material-icons text-slate-300 text-[14px]">location_on</span>
                                    <span class="text-[11px] font-medium text-slate-400 uppercase tracking-tight">{{ $event->venue->name ?? 'Por definir' }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Columna: Fecha --}}
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-slate-700 uppercase">{{ \Carbon\Carbon::parse($event->event_date)->isoFormat('LL') }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($event->event_date)->format('H:i') }} hrs</span>
                            </div>
                        </td>

                        {{-- Columna: Estado (Badges mejorados) --}}
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusClasses = [
                                    'Published' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'Draft'     => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'Cancelled' => 'bg-rose-50 text-rose-600 border-rose-100'
                                ];
                                $label = ['Published' => 'Publicado', 'Draft' => 'Borrador', 'Cancelled' => 'Cancelado'];
                            @endphp
                            <span class="px-3 py-1.5 border {{ $statusClasses[$event->status] ?? 'bg-slate-50 text-slate-400' }} text-[9px] font-black uppercase tracking-[0.15em] rounded-xl">
                                {{ $label[$event->status] ?? $event->status }}
                            </span>
                        </td>

                        {{-- Columna: Acciones --}}
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="p-2.5 rounded-xl bg-slate-50 text-slate-400 hover:bg-[#FF6600] hover:text-white transition-all duration-300 shadow-sm">
                                    <span class="material-icons text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar evento?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 rounded-xl bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white transition-all duration-300 shadow-sm">
                                        <span class="material-icons text-[18px]">delete_outline</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-24 text-center">
                            <div class="w-24 h-24 mx-auto bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6">
                                <span class="material-icons text-5xl text-slate-200">festival</span>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight mb-2">No hay eventos activos</h3>
                            <p class="text-slate-400 text-xs font-medium max-w-xs mx-auto mb-8 tracking-wide">Tu catálogo de eventos está vacío actualmente. Comienza por crear una experiencia inolvidable.</p>
                            <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 text-[#FF6600] text-[11px] font-black uppercase tracking-widest hover:text-slate-900 transition-colors">
                                <span class="material-icons text-sm">add</span> Crear el primer evento
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection