<!DOCTYPE html>

<html lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Inicio de Sesión - MisuTicket</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#ee8c2b",
                        "background-light": "#f8f7f6",
                        "background-dark": "#221910",
                    },
                    fontFamily: {
                        "display": ["Be Vietnam Pro"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center p-4">
<div class="max-w-[450px] w-full">
<!-- Brand/Logo Section -->
<div class="text-center mb-10">
<h1 class="text-3xl font-bold text-black dark:text-white flex items-center justify-center gap-2">
<span class="bg-primary p-1 rounded-lg text-white">
<span class="material-icons">confirmation_number</span>
</span>
                MisuTicket
            </h1>
<p class="text-gray-500 dark:text-gray-400 mt-2 text-sm uppercase tracking-widest font-semibold">Eventos 2026</p>
</div>
<!-- Main Login Card -->
<div class="bg-white dark:bg-zinc-900 shadow-xl rounded-xl p-8 border border-gray-100 dark:border-zinc-800">
<div class="mb-8">
<h2 class="text-2xl font-bold text-gray-900 dark:text-white">Bienvenido</h2>
<p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Ingresa tus credenciales para acceder a tus boletos.</p>
</div>
<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf
<!-- Email Field -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="email">Correo electrónico</label>
<div class="relative">
<span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">mail_outline</span>
<input class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 rounded-lg focus:ring-primary focus:border-primary text-gray-900 dark:text-white transition-all" id="email" name="email" placeholder="ejemplo@correo.com" type="email"/>
</div>
</div>
<!-- Password Field -->
<div>
<div class="flex justify-between items-center mb-1">
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="password">Contraseña</label>
<a class="text-xs font-semibold text-primary hover:underline transition-all" href="#">¿Olvidaste tu contraseña?</a>
</div>
<div class="relative">
<span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">lock_outline</span>
<input class="w-full pl-10 pr-12 py-3 bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 rounded-lg focus:ring-primary focus:border-primary text-gray-900 dark:text-white transition-all" id="password" name="password" placeholder="••••••••" type="password"/>
<button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors" type="button">
<span class="material-icons text-lg">visibility</span>
</button>
</div>
</div>
<!-- Remember Me -->
<div class="flex items-center">
<input class="rounded text-primary focus:ring-primary h-4 w-4 border-gray-300 dark:border-zinc-700 dark:bg-zinc-800" id="remember" type="checkbox"/>
<label class="ml-2 text-sm text-gray-600 dark:text-gray-400" for="remember">Mantener sesión iniciada</label>
</div>
<!-- Submit Button -->
<button class="w-full bg-primary hover:bg-[#d67b22] text-white font-bold py-3.5 rounded-lg transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2" type="submit">
                    Iniciar Sesión
                    <span class="material-icons text-lg">login</span>
</button>
</form>
<!-- Social Logins -->
<div class="mt-8">
<div class="relative flex items-center justify-center mb-6">
<div class="border-t border-gray-200 dark:border-zinc-700 w-full"></div>
<span class="absolute bg-white dark:bg-zinc-900 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">O continúa con</span>
</div>
<div class="grid grid-cols-2 gap-4">
<button class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition-all font-medium text-gray-700 dark:text-gray-300 text-sm">
<img alt="Google icon" class="w-4 h-4" data-alt="Google logo icon for social login" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCYqLwAuqvfiwsU514G9-f8PqKUB10XVv8nTbWJeQuWCQn2f640-gUudr6pyqCVA8EjJVXqF_jvcIRt8n2bhGPx9JgyaIeMUyIkJ5ZBsMiTfVllTaKBR5UX5b50IiUHbs7yWLSoDiSPkd0ovC-lrmh1b3RGWAMU6LFgBI0OWQGP0VSArVFrjjnqcAb1FlqHP_4Xmj7qrwdsltSZxe3_4N0GCNmOjTIdT1LiegKYY08RlvM2q2R5X7csKfttTFVTzlTHbAaEd7HjayjB"/>
                        Google
                    </button>
<button class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition-all font-medium text-gray-700 dark:text-gray-300 text-sm">
<img alt="Facebook icon" class="w-4 h-4" data-alt="Facebook logo icon for social login" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDVDESHfldR4ce8u6nANcKT64YdlQqFPRRkCSqnVLAdX4L2769eyEwB94Xqq6f_RDKcM2qnAyLDftlnIwGlozH1c7H5rVEaSNzHljL-qox8QNinEiyvhPWuTk5DF9PRCWXUhMFYc1JOCKmDLlfHK3VxfpOr1o-D7D3E56MweqI5-6PSHOx-A04czKb5veGimutUgLylfGueoNz4u5ytiH6AHur20fjIlr39M3Zq-F5hf9I9Es3IYJtvCSfKkzmBXnkQZ8vxXsr8atlh"/>
                        Facebook
                    </button>
</div>
</div>
</div>
<!-- Register Link Section -->
<p class="text-center mt-8 text-gray-600 dark:text-gray-400 text-sm">
            ¿No tienes una cuenta? 
            <a class="text-primary font-bold hover:underline transition-all ml-1" href="#">Regístrate ahora</a>
</p>
<!-- Global Navigation / Footer -->
<div class="mt-12 flex justify-center items-center gap-6 text-xs text-gray-400 dark:text-gray-600">
<a class="hover:text-primary transition-colors" href="#">Términos y condiciones</a>
<span>•</span>
<a class="hover:text-primary transition-colors" href="#">Política de privacidad</a>
<span>•</span>
<a class="hover:text-primary transition-colors" href="#">Ayuda</a>
</div>
</div>
<!-- Decorative background elements (optional, subtle) -->
<div class="fixed top-0 right-0 -z-10 w-96 h-96 bg-primary/5 rounded-full blur-3xl -mr-48 -mt-48"></div>
<div class="fixed bottom-0 left-0 -z-10 w-64 h-64 bg-primary/5 rounded-full blur-2xl -ml-32 -mb-32"></div>
</body></html>