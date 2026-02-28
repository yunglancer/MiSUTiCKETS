<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Panel de Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-primary mb-4">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                    <p>Cédula: {{ Auth::user()->document_id }}</p>
                    <p>Teléfono: {{ Auth::user()->phone }}</p>
                    
                    <div class="mt-8 p-4 bg-gray-100 rounded border border-gray-200 border-dashed text-center">
                        <span class="text-gray-500 font-bold">🎟️ Aquí pronto aparecerá la lista de tus entradas compradas.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>