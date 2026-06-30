# Paso 0 — Instalar autenticación (Laravel Breeze)

Antes de copiar los archivos de roles, instala Breeze en tu proyecto
(esto requiere internet, así que lo corres tú en tu máquina):

    cd concar-app
    composer require laravel/breeze --dev
    php artisan breeze:install blade
    npm install
    npm run build

Esto te crea automáticamente: tabla `users`, vistas de login/registro,
y las rutas de autenticación (/login, /register, /logout, etc).

Después de esto, corre las migraciones de Breeze:

    php artisan migrate

Recién después de este paso, copia los archivos de este paquete
(roles y permisos por empresa) como se indica en INSTALAR.md.
