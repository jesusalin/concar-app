# ConCar Laravel — Base del proyecto

**IMPORTANTE: Esto es una actualización con LOGIN Y ROLES.** Sigue
los pasos en este orden exacto, son varios pasos nuevos:

## Paso 1 — Instalar autenticación (si no lo has hecho)

Lee y sigue PASO_0_AUTENTICACION.md primero. Instala Laravel Breeze.

## Paso 2 — Copiar archivos de este paquete

Copia estas carpetas DENTRO de tu proyecto, reemplazando lo que exista:
- database/migrations/*  -> database/migrations/
- app/Models/*           -> app/Models/  (incluye un User.php nuevo,
  reemplaza el que generó Breeze, ya tiene todo lo necesario)
- app/Http/Controllers/* -> app/Http/Controllers/
- app/Http/Middleware/*  -> app/Http/Middleware/  (carpeta nueva)
- resources/views/*      -> resources/views/
- routes/web.php         -> routes/web.php (reemplaza el archivo completo)

## Paso 3 — Registrar los middlewares

Sigue las instrucciones de REGISTRAR_MIDDLEWARE.md (3 líneas en
bootstrap/app.php).

## Paso 4 — Migrar la base de datos

    php artisan migrate

## Paso 5 — Probar

    php artisan serve

Ve a http://127.0.0.1:8000/register y crea tu primer usuario.
Luego crea tu primera empresa — automáticamente quedarás como
"administrador" de ella.

## Cómo funcionan los roles

Cada usuario tiene un rol DISTINTO por cada empresa (la misma
persona puede ser administrador en una empresa y solo lectura en
otra). Desde "Usuarios" (visible solo para administradores) puedes
dar acceso a otros usuarios YA REGISTRADOS en el sistema, indicando
su rol:

- **Administrador**: todo, incluyendo eliminar la empresa, configurar
  el mapeo de cuentas, y dar/quitar acceso a otros usuarios.
- **Contador**: puede registrar y eliminar asientos, compras, ventas,
  cuentas y terceros. No puede tocar Configuración ni Usuarios.
- **Asistente**: solo puede ver (reportes, asientos, etc), no puede
  crear ni eliminar nada.

## Qué incluye este paquete (actualizado)

- Empresas (multiempresa, como CONCAR)
- Plan de Cuentas (PCGE, con jerarquía de cuentas)
- Asientos contables manuales con validación de cuadre
- Libro Diario, Libro Mayor, Balance de Comprobación
- Terceros (clientes y proveedores)
- Configuración contable (mapeo de cuentas para automatizar asientos)
- Registro de Compras (genera asiento automático)
- Registro de Ventas (genera asiento automático)
- Login/registro (Breeze) + roles por empresa (administrador/contador/asistente)

## Siguiente paso sugerido

Después de probar los roles, lo que sigue en el plan es: Caja y
Bancos, y exportación de reportes/PLE.


