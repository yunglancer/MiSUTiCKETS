# Misutickets - Proyecto Programación IV

## 🚀 Guía de Instalación para el Equipo

Para empezar a trabajar en el proyecto, sigue estos pasos exactos en tu terminal:

1. **Clonar el repositorio:**
   `git clone https://github.com/yunglancer/MiSUTiCKETS.git`
2. **Entrar a la carpeta:**
   `cd misutickets`
3. **Instalar dependencias de PHP y Frontend:**
   `composer install`
   `npm install`
4. **Configurar variables de entorno:**
   - Copia el archivo `.env.example` y renómbralo a `.env`.
   - Abre el `.env` y asegúrate de que diga `DB_CONNECTION=sqlite` (para no tener que instalar MySQL localmente por ahora).
5. **Generar la llave de la app:**
   `php artisan key:generate`
6. **Ejecutar migraciones y datos de prueba:**
   `php artisan migrate:fresh --seed`
7. **Levantar los servidores:**
   Abre dos terminales. En una ejecuta `php artisan serve` y en la otra `npm run dev`.

**Credenciales de acceso de prueba:**
- Email: `admin@misutickets.com`
- Password: `password123`