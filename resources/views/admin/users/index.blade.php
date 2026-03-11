@extends('layouts.admin')

@section('content')
<div class="font-display">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
            <span class="material-icons text-[#FF6600]">shield</span>
            Seguridad y Usuarios
        </h1>
        <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold mt-1">Control de acceso y roles de plataforma</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl mb-6 flex items-center gap-3 text-[11px] font-black uppercase tracking-widest">
            <span class="material-icons text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
        <table class="min-w-full border-separate border-spacing-0">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Usuario</th>
                    <th class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Email / Documento</th>
                    <th class="px-6 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Rol Actual</th>
                    <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Asignar Nuevo Rol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50/50 transition-all">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs border border-slate-200 uppercase">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <span class="text-[13px] font-black text-slate-800 uppercase tracking-tight">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-slate-600">{{ $user->email }}</span>
                            <span class="text-[9px] font-medium text-slate-400 uppercase tracking-widest">ID: {{ $user->document_id ?? 'No registrado' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-center">
                        @foreach($user->roles as $role)
                            <span class="px-3 py-1 bg-[#FF6600]/10 text-[#FF6600] text-[9px] font-black uppercase tracking-widest rounded-lg border border-[#FF6600]/20">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </td>
                    <td class="px-8 py-5 text-right">
                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="inline-flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="this.form.submit()" 
                                class="appearance-none bg-slate-50 border border-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-widest rounded-xl px-5 py-3 pr-10 focus:ring-2 focus:ring-[#FF6600]/20 focus:border-[#FF6600] transition-all cursor-pointer">
                                <option value="" disabled selected>Asignar Rango</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection