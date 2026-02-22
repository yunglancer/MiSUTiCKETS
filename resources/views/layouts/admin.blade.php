<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - MiSUTiCKETS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased flex h-screen">

    <nav class="w-64 bg-gray-800 text-white flex-shrink-0">
        <div class="p-4 text-xl font-bold border-b border-gray-700">MiSUTiCKETS</div>
        <ul class="mt-4">
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Dashboard</a></li>
            <li><a href="{{ route('admin.events.index') }}" class="block py-2.5 px-4 hover:bg-gray-700">Eventos</a></li>
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Recintos</a></li>
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Operaciones y Ventas</a></li>
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Seguridad</a></li>
            <li><a href="#" class="block py-2.5 px-4 hover:bg-gray-700">Soporte</a></li>
        </ul>
    </nav>

    <main class="flex-1 overflow-y-auto p-6">
        @yield('content')
    </main>

</body>
</html>