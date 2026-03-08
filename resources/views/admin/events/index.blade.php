@extends('layouts.admin')

@section('content')
<div class="font-display">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">event_note</span>
                Lista de Eventos
            </h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Catálogo general</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="bg-[#FF6600] hover:bg-slate-900 text-white text-xs font-black py-3 px-6 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center gap-2">
            <span class="material-icons text-sm">add</span> Crear Evento
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-2xl mb-6 flex items-center gap-3 text-sm font-bold">
            <span class="material-icons">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Evento</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Categoría</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Lugar</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Fecha</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Estado</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($events as $event)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                                    @if($event->image_path)
                                        <img src="{{ asset('storage/' . $event->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <span class="material-icons text-lg">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800 leading-tight flex items-center gap-2">
                                        {{ $event->title }}
                                        @if($event->is_featured)
                                            <span class="material-icons text-[#FF6600] text-[16px]" title="Evento Destacado">stars</span>
                                        @endif
                                    </div>
                                    <div class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mt-0.5">ID: #{{ $event->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-xs text-slate-500 font-medium">{{ $event->category ? $event->category->name : 'N/A' }}</td>
                        <td class="px-6 py-5 whitespace-nowrap text-xs text-slate-500 font-medium">{{ $event->venue ? $event->venue->name : 'N/A' }}</td>
                        <td class="px-6 py-5 whitespace-nowrap text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-black uppercase tracking-widest rounded-full 
                                {{ $event->status === 'Published' ? 'bg-emerald-100 text-emerald-700' : ($event->status === 'Cancelled' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $event->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-3">
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 hover:bg-[#FF6600] hover:text-white flex items-center justify-center transition-colors">
                                <span class="material-icons text-[16px]">edit</span>
                            </a>
                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este evento permanentemente?')">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors">
                                    <span class="material-icons text-[16px]">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="w-20 h-20 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <span class="material-icons text-4xl text-slate-300">event_busy</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2">No hay eventos creados</h3>
                            <p class="text-slate-500 text-sm">Empieza a llenar tu catálogo creando tu primer evento.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection