# BookLedger — Sistema de Gestión de Biblioteca

Sistema web de gestión bibliotecaria construido con Laravel: catálogo de libros y ejemplares, socios, préstamos, devoluciones, multas, reservas y personal, con panel de administración (KPIs y heatmap de actividad) y una API REST autenticada con Sanctum para consumir catálogo y circulación desde otras aplicaciones.

## Características

- **Catálogo**: libros (ISBN), ejemplares con estado (`Disponible`, `Prestado`, `Mantenimiento`, `Perdido`), categorías con subcategorías, editoriales y autores con tipo de autoría.
- **Circulación**: préstamos con reglas de negocio (máximo de ejemplares por socio, bloqueo por multas pendientes), devoluciones que calculan mora y generan multas automáticas por mora, daño o pérdida, y cambio de estado del socio a `Sancionado` hasta pagar.
- **Reservas**: lista de espera por ISBN con notificaciones al liberarse ejemplares.
- **Panel de administración**: tarjetas KPI filtrables por mes y heatmap construido con la actividad real del sistema (préstamos, devoluciones, multas y reservas).
- **Roles y permisos**: roles `Administrador` y `Bibliotecario` con permisos por módulo (`spatie/laravel-permission`), aplicados igual en web y en API.
- **API REST**: endpoints para socios (lectores) y para personal (bibliotecarios/administradores).

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel 13 (PHP ^8.3), Laravel Jetstream / Fortify, Laravel Sanctum |
| Autorización | spatie/laravel-permission |
| Frontend | Blade + Livewire, Tailwind CSS, Vite |
| Base de datos | MySQL (producción) / SQLite en memoria (tests) |
| Pruebas | PHPUnit (tests Feature/Unit) |

## Requisitos

- PHP 8.3 o superior con extensiones `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`.
- Composer 2.x
- Node.js 20+ y npm
- MySQL 8 (o MariaDB compatible). Los tests usan SQLite en memoria, no requieren configuración.

## Instalación

### Opción A — Instalador web (recomendada)

1. **Instalar dependencias de PHP y compilar assets**:

   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   npm install
   npm run build
   ```

2. **Levantar el servidor** (ver [Levantar el servidor](#levantar-el-servidor)) y abrir el asistente:

   ```
   http://127.0.0.1:8000/install
   ```

3. El asistente tiene tres pasos:
   - **Paso 1**: comprobación de requisitos del servidor y entorno.
   - **Paso 2**: configuración de base de datos (host, nombre, credenciales) y creación del superadministrador.
   - **Paso 3**: proceso de instalación: crea la base de datos, importa el esquema SQL, escribe el `.env` y crea el archivo de bloqueo `storage/installed`.

4. Al terminar, el instalador queda **bloqueado permanentemente** (mientras exista `storage/installed` cualquier ruta `/install` redirige al login). Inicia sesión con el superadministrador creado en el paso 2.

> Si necesitas volver a instalar desde cero, elimina `storage/installed` bajo tu responsabilidad: el sistema quedará accesible solo para el instalador.

### Opción B — Instalación manual

1. Instalar dependencias y compilar assets (mismos comandos del paso 1 de la opción A).
2. Configurar `.env` con tu base de datos MySQL:

   ```env
   APP_NAME="Sistema de Gestion de Biblioteca"
   APP_URL=http://127.0.0.1:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistema_biblioteca
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. Crear la base de datos `sistema_biblioteca` (o la que hayas elegido) y ejecutar:

   ```bash
   php artisan migrate --force     # esquema completo
   php artisan db:seed             # roles/permisos, usuarios demo, bibliotecarios y catálogo de libros
   php artisan storage:link        # necesario para las portadas de libros (disco "public")
   ```

4. Opcional: importar el catálogo completo desde datos de scraping (libros, autores, editoriales, categorías y portadas):

   ```bash
   php artisan libros:import-scraped
   ```

### Credenciales de demostración (creadas por `db:seed`)

| Usuario | Contraseña | Rol / uso |
|---|---|---|
| `admin@sgb.test` | `12345678` | Administrador (panel y API de personal) |
| `biblio@sgb.test` | `12345678` | Bibliotecario, vinculado a un registro de bibliotecario (necesario para prestar/devolver) |
| `socio@sgb.test` | `12345678` | Socio de prueba (API de socios) |

> **Nota**: los préstamos y devoluciones (web o API) se sellan con la ficha de empleado (`bibliotecarios`) vinculada a la cuenta (`bibliotecarios.user_id`), que es quien figura como responsable de la operación. Las cuentas con rol `Administrador` que aún no tengan ficha reciben una automáticamente en su primera operación de circulación, sellada con el nombre de la cuenta; cualquier otro usuario necesita una ficha vinculada manualmente.

## Levantar el servidor

```bash
# Backend + cola + logs + Vite en un solo comando
composer run dev

# O por separado:
php artisan serve          # http://127.0.0.1:8000
npm run dev                # assets con HMR (en desarrollo)
npm run build              # compilar assets para producción
```

La URL por defecto es `http://127.0.0.1:8000` (ajusta `APP_URL` y el puerto de `serve` si cambias algo).

## Ejecutar las pruebas

```bash
php artisan test
```

La suite usa SQLite en memoria (`phpunit.xml`), no toca tu base de datos local.

## Módulos del panel web

Rutas principales (requieren sesión y permisos): `/dashboard` (panel), `/libros`, `/ejemplares`, `/categorias`, `/editoriales`, `/autores`, `/socios`, `/prestamos`, `/devoluciones`, `/multas`, `/bibliotecarios`, `/reservas`.

## API REST

Todas las respuestas son JSON. La autenticación usa tokens Bearer de Sanctum; cada endpoint de personal exige además el permiso spatie del módulo correspondiente (el mismo que aplica el panel web).

### Autenticación de personal

```bash
curl -X POST http://127.0.0.1:8000/api/staff/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"admin@sgb.test","password":"12345678"}'
```

Respuesta: `{ "message", "token", "usuario": { id, name, email, roles } }`. Usa el token en las peticiones siguientes:

```bash
curl -H "Authorization: Bearer <TOKEN>" -H "Accept: application/json" http://127.0.0.1:8000/api/libros
```

### Endpoints de personal (staff)

| Método | Ruta | Permiso | Descripción |
|---|---|---|---|
| POST | `/api/staff/login` | público | Genera token de personal (roles Administrador/Bibliotecario) |
| POST | `/api/staff/logout` | token | Revoca el token en uso |
| GET | `/api/actividad` | `prestamos.ver` | Actividad diaria real (préstamos, devoluciones, multas, reservas). `?fecha=YYYY-MM-DD` limita al mes |
| GET | `/api/libros` | `libros.ver` | Listado paginado. Filtros: `buscar`, `categoria`, `editorial`, `autor`, `per_page` |
| GET | `/api/libros/{isbn}` | `libros.ver` | Libro con categoría, editorial, autores y ejemplares |
| GET | `/api/categorias` | `categorias.ver` | Categorías con subcategorías y conteo de libros |
| GET | `/api/autores` | `autores.ver` | Autores con conteo de libros |
| GET | `/api/editoriales` | `editoriales.ver` | Editoriales con conteo de libros |
| GET | `/api/ejemplares` | `ejemplares.ver` | Ejemplares. Filtros: `estado`, `isbn`, `per_page` |
| GET | `/api/socios` | `socios.ver` | Socios paginados. Filtro: `estado` |
| GET | `/api/prestamos` | `prestamos.ver` | Préstamos paginados con socio, bibliotecario y detalles |
| POST | `/api/prestamos` | `prestamos.crear` | **Prestar libros** (ver ejemplo) |
| GET | `/api/prestamos/{id}` | `prestamos.ver` | Detalle de un préstamo con multas |
| GET | `/api/devoluciones` | `devoluciones.ver` | Devoluciones paginadas |
| POST | `/api/devoluciones` | `devoluciones.crear` | **Devolver ejemplares** (ver ejemplo) |
| GET | `/api/devoluciones/{id}` | `devoluciones.ver` | Detalle de una devolución |
| GET | `/api/multas` | `multas.ver` | Multas paginadas. Filtro: `estado` (`Pendiente`/`Pagado`) |
| PATCH | `/api/multas/{id}/pagar` | `multas.editar` | Marca la multa pagada y reactiva al socio si corresponde |
| GET | `/api/reservas` | `reservas.ver` | Reservas paginadas. Filtro: `estado` |

#### Prestar libros

```bash
curl -X POST http://127.0.0.1:8000/api/prestamos \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"Codigo_Socio": 1, "ejemplares": [1, 2]}'
```

Valida socio activo, sin multas pendientes, cupo máximo de ejemplares y disponibilidad de cada ejemplar. Los ejemplares pasan a `Prestado` y se notifica al socio.

#### Devolver ejemplares

```bash
curl -X POST http://127.0.0.1:8000/api/devoluciones \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{
        "detalles": {
          "1": { "devolver": true, "estado": "Buen estado" },
          "2": { "devolver": true, "estado": "Dañado" }
        },
        "Observaciones": "Texto opcional (máx. 200)"
      }'
```

Las claves de `detalles` son los `Codigo_Detalle` del préstamo. `estado` admite `Buen estado`, `Dañado` o `Perdido`. La devolución libera o ajusta el estado del ejemplar, concluye el préstamo si no quedan ejemplares pendientes y genera multas por mora/daño/pérdida según la configuración (`config/biblioteca.php`).

#### Actividad del sistema

```bash
curl -H "Authorization: Bearer <TOKEN>" -H "Accept: application/json" \
  "http://127.0.0.1:8000/api/actividad?fecha=2026-11-03"
```

Devuelve `{ desde, hasta, etiqueta, total, dias: [ { fecha, prestamos, devoluciones, multas, reservas, total } ] }`. Sin `fecha` cubre los últimos 12 meses; con `fecha`, el mes completo. Es la misma fuente que alimenta el heatmap del panel.

### Endpoints de socio (lector)

| Método | Ruta | Auth | Descripción |
|---|---|---|---|
| POST | `/api/socio/login` | público | Token de socio (`Email`, `password`) |
| GET | `/api/socio/catalogo` | público | Catálogo completo con categorías, editoriales y autores |
| POST | `/api/socio/logout` | token | Revoca el token |
| GET | `/api/socio/dashboard` o `/api/socio/perfil` | token | Préstamos, reservas y multas pendientes del socio |
| POST | `/api/socio/reservas` | token | Reservar un libro (`Codigo_ISBN`); entra en lista de espera si ya hay reserva |
| DELETE | `/api/socio/reservas/{id}` | token | Cancelar una reserva propia |

### Códigos de respuesta

- `200/201` operación exitosa.
- `401` sin token o credenciales incorrectas.
- `403` token válido sin rol/permiso suficiente (p. ej. un token de socio contra endpoints de personal).
- `422` error de validación (incluye `errors` por campo) o regla de negocio incumplida.

## Estructura relevante

```
app/
├── Console/Commands/ImportScrapedBooksCommand.php   # libros:import-scraped
├── Http/
│   ├── Controllers/
│   │   ├── Api/                     # Controladores de la API (staff, socios, catálogo, circulación, actividad)
│   │   ├── DashboardController.php  # KPIs + heatmap del panel
│   │   └── ...                      # Controladores CRUD de cada módulo
│   └── Middleware/CheckInstallation.php  # Autobloqueo del instalador
├── Models/                          # Libro, Ejemplar, Socio, Prestamo, Devolucion, Multa, Reserva, ...
└── Services/
    ├── PrestamoService.php          # Reglas y transacción de préstamo
    ├── DevolucionService.php        # Mora, multas y estados en devoluciones
    └── ActividadService.php         # Actividad diaria real (heatmap y /api/actividad)
routes/
├── api.php                          # API de socios y personal
└── web.php                          # Panel web e instalador
tests/Feature/                       # Tests de dashboard, API y módulos Jetstream
```

## Configuración de negocio

Los parámetros de circulación viven en `config/biblioteca.php`: días de préstamo, máximo de ejemplares por socio, multa por día de mora, multa por daño y por pérdida, y días de aviso de vencimiento.
