@extends('layouts.admin') {{-- Ojo: Asegúrate de que este sea el nombre correcto de tu layout --}}

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Auditoría <span class="text-[#FF6600]">del Sistema</span></h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Monitor de seguridad y registro de actividades en tiempo real.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Fecha y Hora</th>
                        <th class="px-6 py-4">Usuario</th>
                        <th class="px-6 py-4">Acción</th>
                        <th class="px-6 py-4">Módulo (Registro)</th>
                        <th class="px-6 py-4">Dirección IP</th>
                        <th class="px-6 py-4 text-center">Detalles</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" x-data="{ expanded: null }">
                    @forelse($audits as $audit)
                        <tr class="hover:bg-slate-50 transition duration-150 cursor-pointer" @click="expanded = expanded === {{ $audit->id }} ? null : {{ $audit->id }}">
                            
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">
                                {{ $audit->created_at->format('d/m/Y h:i A') }}
                            </td>
                            
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-[#FF6600]/10 flex items-center justify-center text-[#FF6600] font-bold">
                                    {{ $audit->user ? substr($audit->user->name, 0, 1) : 'S' }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $audit->user ? $audit->user->name : 'Sistema' }}</span>
                            </td>
                            
                            <td class="px-6 py-4">
                                @if($audit->event === 'created')
                                    <span class="bg-emerald-100 text-emerald-700 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Creación</span>
                                @elseif($audit->event === 'updated')
                                    <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Edición</span>
                                @elseif($audit->event === 'deleted')
                                    <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">Eliminación</span>
                                @else
                                    <span class="bg-slate-100 text-slate-700 py-1 px-3 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $audit->event }}</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-700">{{ class_basename($audit->auditable_type) }}</span>
                                <span class="text-slate-400 text-xs ml-1">#{{ $audit->auditable_id }}</span>
                            </td>
                            
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                                {{ $audit->ip_address }}
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                <button type="button" class="text-slate-400 hover:text-[#FF6600] transition-colors" title="Ver cambios">
                                    <span class="material-icons text-xl" x-text="expanded === {{ $audit->id }} ? 'expand_less' : 'visibility'"></span>
                                </button>
                            </td>
                        </tr>

                        <tr x-show="expanded === {{ $audit->id }}" x-transition class="bg-slate-100/40" style="display: none;">
                            <td colspan="6" class="px-8 py-6 border-b border-slate-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                        <h4 class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <span class="material-icons text-sm">remove_circle_outline</span>
                                            Valores Anteriores (Antes)
                                        </h4>
                                        <ul class="space-y-2 text-xs font-mono text-slate-600">
                                            @forelse($audit->old_values as $key => $value)
                                                <li class="flex flex-col">
                                                    <span class="font-bold text-slate-400">{{ $key }}:</span>
                                                    <span class="break-words text-red-600 bg-red-50 px-2 py-1 rounded mt-1">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                </li>
                                            @empty
                                                <li class="text-slate-400 italic">No aplica (Es un registro nuevo)</li>
                                            @endforelse
                                        </ul>
                                    </div>

                                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                        <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <span class="material-icons text-sm">add_circle_outline</span>
                                            Nuevos Valores (Después)
                                        </h4>
                                        <ul class="space-y-2 text-xs font-mono text-slate-600">
                                            @forelse($audit->new_values as $key => $value)
                                                <li class="flex flex-col">
                                                    <span class="font-bold text-slate-400">{{ $key }}:</span>
                                                    <span class="break-words text-emerald-700 bg-emerald-50 px-2 py-1 rounded mt-1">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                </li>
                                            @empty
                                                <li class="text-slate-400 italic">No aplica (El registro fue eliminado)</li>
                                            @endforelse
                                        </ul>
                                    </div>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <span class="material-icons text-4xl text-slate-300 mb-2 block">security_update_good</span>
                                No hay registros de auditoría en el sistema todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($audits->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $audits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection