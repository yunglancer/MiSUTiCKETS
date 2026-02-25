<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Inicio de Sesión - MisuTicket</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#FF6600",
                    },
                    fontFamily: {
                        "display": ["Be Vietnam Pro"]
                    },
                },
            },
        }
    </script>
</head>
<body class="font-display bg-white min-h-screen flex items-center justify-center p-4">

<div class="max-w-[450px] w-full">
    <div class="text-center mb-10">
        <h1 class="text-5xl font-black text-black tracking-tighter uppercase flex items-center justify-center gap-1">
            MISU<span class="text-primary">TICKETS</span>
        </h1>
        <p class="text-gray-400 mt-2 text-[10px] uppercase tracking-[0.3em] font-bold">Eventos 2026</p>
    </div>

    <div class="bg-white p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Bienvenido</h2>
            <p class="text-gray-500 text-sm mt-1">Ingresa tus credenciales para acceder a tus boletos.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest mb-2 ml-1" for="email">Correo electrónico</label>
                <div class="relative">
                    <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg">mail_outline</span>
                    <input class="w-full pl-12 pr-4 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-black text-sm transition-all outline-none" 
                           id="email" name="email" placeholder="ejemplo@correo.com" type="email" required autofocus/>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2 ml-1">
                    <label class="block text-[10px] font-black text-gray-800 uppercase tracking-widest" for="password">Contraseña</label>
                    <a class="text-[10px] font-bold text-primary hover:text-black uppercase tracking-tight transition-all" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="relative">
                    <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg">lock_outline</span>
                    <input class="w-full pl-12 pr-12 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-black text-sm transition-all outline-none" 
                           id="password" name="password" placeholder="••••••••" type="password" required/>
                </div>
            </div>

            <div class="flex items-center ml-1">
                <input class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4" id="remember_me" name="remember" type="checkbox"/>
                <label class="ml-2 text-sm text-gray-600 font-medium" for="remember_me">Mantener sesión iniciada</label>
            </div>

            <div class="pt-2">
                <button class="w-full bg-primary hover:bg-[#ff7a21] text-white text-xs font-black py-5 rounded-2xl uppercase tracking-[0.2em] transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2" type="submit">
                    Iniciar Sesión
                    <span class="material-icons text-lg">login</span>
                </button>
            </div>
        </form>
    </div>

    <p class="text-center mt-8 text-black text-sm font-bold uppercase tracking-widest">
        ¿No tienes una cuenta? 
        <a class="text-primary hover:underline transition-all ml-1" href="{{ route('register') }}">Regístrate ahora</a>
    </p>

</div>

</body>
</html>