# Guía de consumo de la API — BookLedger

API REST del Sistema de Gestión de Biblioteca. Todas las rutas viven bajo `/api`, responden **JSON** y usan **Laravel Sanctum** con tokens Bearer. Hay dos familias de endpoints:

- **Personal** (`/api/staff/*`, catálogo y circulación): para aplicaciones de mostrador, integraciones o automatización del personal bibliotecario.
- **Socios** (`/api/socio/*`): para apps del lector (consultar catálogo, reservar, ver su estado).

> Envía siempre `Accept: application/json` para que los errores lleguen como JSON y no como HTML.

---

## 1. Autenticación

### 1.1 Personal (bibliotecarios / administradores)

```bash
curl -X POST http://127.0.0.1:8000/api/staff/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@sgb.test","password":"12345678"}'
```

Respuesta `200`:

```json
{
  "message": "Inicio de sesión exitoso.",
  "token": "2|PntDbe7DwbL5NhVeIBHQEafUTpmzdZVZBu6fJqcO87fffe25",
  "usuario": { "id": 1, "name": "Administrador", "email": "admin@sgb.test", "roles": ["Administrador"] }
}
```

Errores: `401` credenciales incorrectas · `403` el usuario no tiene rol `Administrador` ni `Bibliotecario`.

Con el token, toda petición posterior lleva:

```bash
curl -H "Authorization: Bearer <TOKEN>" -H "Accept: application/json" \
  http://127.0.0.1:8000/api/libros
```

Cerrar sesión (revoca el token en uso): `POST /api/staff/logout`.

### 1.2 Socios (lectores)

```bash
curl -X POST http://127.0.0.1:8000/api/socio/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"Email":"socio@sgb.test","password":"12345678"}'
```

> Nota: el campo de correo del socio es `Email` (mayúscula), distinto del `email` de personal.
> Errores: `401` credenciales incorrectas · `403` cuenta de socio no activa.

### 1.3 Permisos

Cada endpoint de personal exige, además del token, el **permiso spatie** del módulo (el mismo que aplica el panel web). Los roles los otorgan en bloque:

| Rol | Permisos |
|---|---|
| `Administrador` | todos (`libros.*`, `categorias.*`, `editoriales.*`, `autores.*`, `ejemplares.*`, `socios.*`, `prestamos.*`, `devoluciones.*`, `reservas.*`, `multas.*`, `bibliotecarios.*`, `roles.gestionar`) |
| `Bibliotecario` | todos excepto `bibliotecarios.*` y `roles.gestionar` |

Un token válido sin el permiso recibe `403`. Un token de socio contra endpoints de personal también recibe `403`.

### 1.4 Sello de circulación

Todo préstamo y devolución guarda **qué empleado lo operó** (`Codigo_Bibliotecario`):

- Si la cuenta tiene ficha de empleado vinculada (`bibliotecarios.user_id`), se sella con ella.
- Si la cuenta es `Administrador` y aún no tiene ficha, el sistema **crea una automáticamente** en su primera operación, con el nombre de la cuenta.
- Cualquier otra cuenta sin ficha recibe `403` ("Tu usuario no está vinculado a un bibliotecario").

---

## 2. Endpoints de personal

### 2.1 Catálogo (solo lectura)

| Método | Ruta | Permiso | Parámetros |
|---|---|---|---|
| GET | `/api/libros` | `libros.ver` | `buscar` (título o ISBN), `categoria`, `editorial`, `autor` (códigos), `per_page` (1–100, default 15) |
| GET | `/api/libros/{isbn}` | `libros.ver` | — |
| GET | `/api/categorias` | `categorias.ver` | — |
| GET | `/api/autores` | `autores.ver` | — |
| GET | `/api/editoriales` | `editoriales.ver` | — |
| GET | `/api/ejemplares` | `ejemplares.ver` | `estado` (`Disponible`/`Prestado`/`Mantenimiento`/`Perdido`), `isbn`, `per_page` |
| GET | `/api/socios` | `socios.ver` | `estado` (`Activo`/`Sancionado`/`Inactivo`), `per_page` |

Ejemplo:

```bash
curl -H "Authorization: Bearer <TOKEN>" -H "Accept: application/json" \
  "http://127.0.0.1:8000/api/libros?buscar=laberinto&per_page=5"
```

Los listados paginados devuelven el formato estándar de Laravel:

```json
{
  "current_page": 1,
  "data": [ { "Codigo_ISBN": "...", "Titulo": "...", "categoria": {...}, "editorial": {...}, "autores": [...], "ejemplares_count": 3, "ejemplares_disponibles_count": 2 } ],
  "last_page": 4,
  "next_page_url": ".../api/libros?page=2",
  "total": 106
}
```

`GET /api/libros/{isbn}` devuelve `{ "libro": { ..., "categoria", "editorial", "autores", "ejemplares" } }` con todos los ejemplares y su estado actual.

`/api/categorias`, `/api/autores` y `/api/editoriales` devuelven `{ "categorias": [...] }`, `{ "autores": [...] }`, `{ "editoriales": [...] }` con `libros_count` y, en categorías, `subcategorias`.

### 2.2 Circulación: prestar y devolver

| Método | Ruta | Permiso | Descripción |
|---|---|---|---|
| GET | `/api/prestamos` | `prestamos.ver` | Préstamos paginados con socio, bibliotecario y detalles |
| POST | `/api/prestamos` | `prestamos.crear` | Registrar préstamo |
| GET | `/api/prestamos/{id}` | `prestamos.ver` | Detalle con multas por detalle |
| GET | `/api/devoluciones` | `devoluciones.ver` | Devoluciones paginadas |
| POST | `/api/devoluciones` | `devoluciones.crear` | Registrar devolución |
| GET | `/api/devoluciones/{id}` | `devoluciones.ver` | Detalle completo |
| GET | `/api/multas` | `multas.ver` | Multas paginadas, filtro `estado` (`Pendiente`/`Pagado`) |
| PATCH | `/api/multas/{id}/pagar` | `multas.editar` | Marcar pagada y reactivar al socio si procede |
| GET | `/api/reservas` | `reservas.ver` | Reservas paginadas, filtro `estado` |

#### Prestar libros

```bash
curl -X POST http://127.0.0.1:8000/api/prestamos \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"Codigo_Socio": 1, "ejemplares": [199, 200]}'
```

`201` con `{ "message", "prestamo": { Codigo_Prestamo, FechaPrestamo, FechaLimite_Devolucion, socio, detalles[] } }`.

Reglas de negocio que responden `422` (`errors` por campo):

- El socio debe existir y estar `Activo`.
- El socio no puede tener multas `Pendiente`.
- Cupo máximo de ejemplares en poder (config `biblioteca.max_ejemplares_socio`, default 3).
- Todos los ejemplares deben existir y estar `Disponible` en ese instante.

#### Devolver ejemplares

```bash
curl -X POST http://127.0.0.1:8000/api/devoluciones \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{
        "detalles": {
          "586": { "devolver": true, "estado": "Buen estado" },
          "587": { "devolver": true, "estado": "Dañado" }
        },
        "Observaciones": "Entrega en mostrador"
      }'
```

- Las **claves de `detalles` son los `Codigo_Detalle`** del préstamo (no un arreglo positional).
- `estado` admite `Buen estado`, `Dañado` o `Perdido`.
- `201` con `{ "message", "devolucion": { ..., detalles con ejemplar/libro/multas } }`.

Efectos automáticos: el ejemplar vuelve a `Disponible` (o queda `Mantenimiento`/`Perdido` según el estado), el préstamo pasa a `Concluido` cuando no quedan detalles pendientes, y se generan multas por **mora** (días tras la fecha límite × `multa_por_dia`), **daño** o **pérdida**. Un socio con multa pendiente queda `Sancionado` hasta pagarla.

#### Pagar una multa

```bash
curl -X PATCH http://127.0.0.1:8000/api/multas/347/pagar \
  -H "Authorization: Bearer <TOKEN>" -H "Accept: application/json"
```

`200` con `{ "message", "multa" }`. Si ya estaba pagada: `422`. Al pagar la última multa pendiente de un socio `Sancionado`, vuelve a `Activo`.

### 2.3 Actividad del sistema

```bash
curl -H "Authorization: Bearer <TOKEN>" -H "Accept: application/json" \
  "http://127.0.0.1:8000/api/actividad?fecha=2026-10-07"
```

Permiso: `prestamos.ver`. Respuesta:

```json
{
  "desde": "2026-10-01",
  "hasta": "2026-10-31",
  "etiqueta": "Octubre 2026",
  "total": 31,
  "dias": [
    { "fecha": "2026-10-01", "prestamos": 1, "devoluciones": 0, "multas": 0, "reservas": 1, "total": 2 }
  ]
}
```

Sin `fecha` cubre los últimos 12 meses; con `fecha=YYYY-MM-DD` limita al mes completo. Es la misma fuente que alimenta el heatmap del panel.

---

## 3. Endpoints de socio

| Método | Ruta | Auth | Descripción |
|---|---|---|---|
| POST | `/api/socio/login` | pública | Token de socio (`Email`, `password`) |
| GET | `/api/socio/catalogo` | pública | Catálogo completo con categorías, editoriales y autores |
| POST | `/api/socio/logout` | token | Revoca el token |
| GET | `/api/socio/dashboard` o `/api/socio/perfil` | token | Sus préstamos, reservas y suma de multas pendientes |
| POST | `/api/socio/reservas` | token | `{ "Codigo_ISBN": "..." }` → reserva o lista de espera (`201`, campo `en_espera`) |
| DELETE | `/api/socio/reservas/{id}` | token | Cancela una reserva propia (ajena: `403`) |

---

## 4. Códigos de error

| Código | Cuándo |
|---|---|
| `401` | Sin token, token inválido o credenciales incorrectas |
| `403` | Token válido sin rol/permiso suficiente, cuenta sin ficha de empleado (no Administrador), o recurso ajeno (reserva de otro socio) |
| `404` | Ruta o modelo inexistente (p. ej. `/api/libros/{isbn}` con ISBN que no existe) |
| `422` | Error de validación o regla de negocio; cuerpo `{ "message": "...", "errors": { "campo": ["motivo"] } }` |

---

## 5. Flujo completo de ejemplo (bash)

```bash
BASE=http://127.0.0.1:8000/api

# 1. Token de personal
TOKEN=$(curl -s -X POST $BASE/staff/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"biblio@sgb.test","password":"12345678"}' | php -r 'echo json_decode(stream_get_contents(STDIN))->token;')

# 2. Buscar un ejemplar disponible
EJEMPLAR=$(curl -s -H "Authorization: Bearer $TOKEN" -H "Accept: application/json" \
  "$BASE/ejemplares?estado=Disponible&per_page=1" | php -r 'echo json_decode(stream_get_contents(STDIN))->data[0]->Codigo_Ejemplar;')

# 3. Prestarlo al socio 1
PRESTAMO=$(curl -s -X POST $BASE/prestamos \
  -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -H "Accept: application/json" \
  -d "{\"Codigo_Socio\": 1, \"ejemplares\": [$EJEMPLAR]}")
echo "$PRESTAMO"

# 4. Devolverlo (el Codigo_Detalle viene en prestamo.detalles[0].Codigo_Detalle)
DETALLE=$(echo "$PRESTAMO" | php -r 'echo json_decode(stream_get_contents(STDIN))->prestamo->detalles[0]->Codigo_Detalle;')
curl -s -X POST $BASE/devoluciones \
  -H "Authorization: Bearer $TOKEN" -H "Content-Type: application/json" -H "Accept: application/json" \
  -d "{\"detalles\": {\"$DETALLE\": {\"devolver\": true, \"estado\": \"Buen estado\"}}}"

# 5. Ver la actividad del día reflejada
curl -s -H "Authorization: Bearer $TOKEN" -H "Accept: application/json" "$BASE/actividad"
```

---

## 6. Notas prácticas

- **Fechas**: siempre `YYYY-MM-DD` en parámetros y respuestas de fecha; `YYYY-MM-DD HH:MM:SS` en timestamps.
- **Paginación**: `?page=N` y `?per_page=N` (máx. 100) en listados paginados; el cuerpo incluye `links` y `meta` útiles para UI.
- **Tokens**: no expiran por configuración; revócalos con `/api/staff/logout` o `/api/socio/logout`. Cada login crea un token nuevo.
- **Idempotencia**: no hay reintentos seguros en `POST /api/prestamos` (dos llamadas crean dos préstamos); usa `GET /api/prestamos` para confirmar antes de reintentar.
- **Configuración de negocio** (días de préstamo, cupos, montos de multa): `config/biblioteca.php`.
