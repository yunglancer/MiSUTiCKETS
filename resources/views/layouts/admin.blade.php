<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - MiSUTiCKETS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased flex h-screen">

    <nav class="w-64 bg-gray-800 text-white flex flex-col flex-shrink-0">
        <div class="p-4 text-xl font-bold border-b border-gray-700">MiSUTiCKETS</div>
        
        <ul class="mt-4 flex-1">
            <a href="{{ route('home') }}" class="block py-2.5 px-4 rounded transition duration-200 text-green-400 hover:bg-gray-800 hover:text-green-300 font-bold border-b border-gray-800 mb-2">
                🏠 Volver a la Tienda
            </a>
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Dashboard</a></li>
            <li><a href="{{ route('admin.events.index') }}" class="block py-2.5 px-4 hover:bg-gray-700">Eventos</a></li>
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Recintos</a></li>
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Operaciones y Ventas</a></li>
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Seguridad</a></li>
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Soporte</a></li>
        </ul>

        <div class="p-4 border-t border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left block py-2 px-4 rounded text-red-400 hover:bg-gray-700 hover:text-red-300 font-bold transition duration-200">
                    🚪 Cerrar Sesión
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-1 overflow-y-auto p-6">
        @yield('content')
    </main>

</body>

</html>