@extends('layouts.admin')

@section('content')
<div class="font-display">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="material-icons text-[#FF6600]">category</span>
                Categorías de Eventos
            </h1>
            <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Clasificación del catálogo</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-[#FF6600] hover:bg-slate-900 text-white text-[10px] font-black py-4 px-8 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#FF6600]/30 flex items-center gap-2">
            <span class="material-icons text-sm">add</span> Nueva Categoría
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0">
                <thead class="bg-slate-50/80 backdrop-blur-sm">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Visual</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Nombre</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Slug (URL)</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] border-b border-slate-100">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/30 transition-all group">
                        
                        
                        <td class="px-8 py-5">
                            <div class="w-12 h-12 rounded-[1.2rem] bg-orange-50 flex items-center justify-center transition-all duration-300">
                                <span class="material-icons text-[#FF6600] text-2xl">{{ $category->icon ?? 'label' }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="text-[13px] font-black text-slate-800 uppercase tracking-tight">{{ $category->name }}</div>
                            <div class="text-[9px] text-slate-400 font-bold uppercase mt-0.5 tracking-widest">ID: #{{ $category->id }}</div>
                        </td>

                        <td class="px-6 py-5">
                            <span class="text-[11px] font-medium text-slate-400 font-mono">/{{ $category->slug }}</span>
                        </td>

                        
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                   class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white flex items-center justify-center transition-all duration-300 border border-slate-100">
                                    <span class="material-icons text-base">edit</span>
                                </a>
                                
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all duration-300 border border-slate-100">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection