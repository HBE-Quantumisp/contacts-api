# API de Gestión de Contactos

Esta API permite a los usuarios registrarse, autenticarse y gestionar su propia lista de contactos.

## Base URL

```
http://localhost:8000/api
```

## Autenticación

La API utiliza Laravel Sanctum para la autenticación basada en tokens. Después del login o registro, incluye el token en el header `Authorization`:

```
Authorization: Bearer {tu_token}
```

## Endpoints

### 🔐 Autenticación

#### Registrar Usuario

```http
POST /auth/register
```

**Body:**

```json
{
    "nombre": "Juan",
    "apellido": "Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Respuesta exitosa (201):**

```json
{
    "success": true,
    "message": "Usuario registrado exitosamente",
    "data": {
        "user": {
            "id": 1,
            "nombre": "Juan",
            "apellido": "Pérez",
            "email": "juan@example.com"
        },
        "token": "1|abc123..."
    }
}
```

#### Iniciar Sesión

```http
POST /auth/login
```

**Body:**

```json
{
    "email": "juan@example.com",
    "password": "password123"
}
```

**Respuesta exitosa (200):**

```json
{
    "success": true,
    "message": "Inicio de sesión exitoso",
    "data": {
        "user": {
            "id": 1,
            "nombre": "Juan",
            "apellido": "Pérez",
            "email": "juan@example.com"
        },
        "token": "2|def456..."
    }
}
```

#### Cerrar Sesión

```http
POST /auth/logout
```

**Headers:** `Authorization: Bearer {token}`

**Respuesta exitosa (200):**

```json
{
    "success": true,
    "message": "Sesión cerrada exitosamente"
}
```

#### Obtener Usuario Actual

```http
GET /auth/me
```

**Headers:** `Authorization: Bearer {token}`

**Respuesta exitosa (200):**

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "nombre": "Juan",
            "apellido": "Pérez",
            "email": "juan@example.com"
        }
    }
}
```

### 📇 Gestión de Contactos

#### Listar Contactos

```http
GET /contacts
```

**Headers:** `Authorization: Bearer {token}`

**Respuesta exitosa (200):**

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "nombre": "María",
                "apellido": "García",
                "telefono": "+34 612 345 678",
                "email": "maria.garcia@example.com",
                "direccion": "Calle Mayor 123, Madrid, España",
                "created_at": "2025-08-20T16:30:00.000000Z",
                "updated_at": "2025-08-20T16:30:00.000000Z"
            }
        ],
        "first_page_url": "http://localhost:8000/api/contacts?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost:8000/api/contacts?page=1",
        "next_page_url": null,
        "path": "http://localhost:8000/api/contacts",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

#### Crear Contacto

```http
POST /contacts
```

**Headers:** `Authorization: Bearer {token}`

**Body:**

```json
{
    "nombre": "María",
    "apellido": "García",
    "telefono": "+34 612 345 678",
    "email": "maria.garcia@example.com",
    "direccion": "Calle Mayor 123, Madrid, España"
}
```

**Respuesta exitosa (201):**

```json
{
    "success": true,
    "message": "Contacto creado exitosamente",
    "data": {
        "id": 1,
        "user_id": 1,
        "nombre": "María",
        "apellido": "García",
        "telefono": "+34 612 345 678",
        "email": "maria.garcia@example.com",
        "direccion": "Calle Mayor 123, Madrid, España",
        "created_at": "2025-08-20T16:30:00.000000Z",
        "updated_at": "2025-08-20T16:30:00.000000Z"
    }
}
```

#### Obtener Contacto

```http
GET /contacts/{id}
```

**Headers:** `Authorization: Bearer {token}`

**Respuesta exitosa (200):**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "nombre": "María",
        "apellido": "García",
        "telefono": "+34 612 345 678",
        "email": "maria.garcia@example.com",
        "direccion": "Calle Mayor 123, Madrid, España",
        "created_at": "2025-08-20T16:30:00.000000Z",
        "updated_at": "2025-08-20T16:30:00.000000Z"
    }
}
```

#### Actualizar Contacto

```http
PUT /contacts/{id}
```

**Headers:** `Authorization: Bearer {token}`

**Body:**

```json
{
    "nombre": "María",
    "apellido": "García Rodríguez",
    "telefono": "+34 612 345 678",
    "email": "maria.garcia@example.com",
    "direccion": "Calle Mayor 456, Madrid, España"
}
```

**Respuesta exitosa (200):**

```json
{
    "success": true,
    "message": "Contacto actualizado exitosamente",
    "data": {
        "id": 1,
        "user_id": 1,
        "nombre": "María",
        "apellido": "García Rodríguez",
        "telefono": "+34 612 345 678",
        "email": "maria.garcia@example.com",
        "direccion": "Calle Mayor 456, Madrid, España",
        "created_at": "2025-08-20T16:30:00.000000Z",
        "updated_at": "2025-08-20T16:32:00.000000Z"
    }
}
```

#### Eliminar Contacto

```http
DELETE /contacts/{id}
```

**Headers:** `Authorization: Bearer {token}`

**Respuesta exitosa (200):**

```json
{
    "success": true,
    "message": "Contacto eliminado exitosamente"
}
```

#### Buscar Contactos

```http
GET /contacts/search?q={término_búsqueda}
```

**Headers:** `Authorization: Bearer {token}`

**Parámetros de consulta:**

-   `q`: Término de búsqueda (requerido) - Busca en nombre, apellido, teléfono y email

**Ejemplo:**

```http
GET /contacts/search?q=maria
```

**Respuesta exitosa (200):**

```json
{
    "success": true,
    "data": {
        "contacts": [
            {
                "id": 1,
                "nombre": "María",
                "apellido": "García",
                "nombre_completo": "María García",
                "telefono": "+34 612 345 678",
                "email": "maria.garcia@example.com",
                "direccion": "Calle Mayor 123, Madrid, España",
                "fecha_creacion": "20/08/2025 16:30",
                "fecha_actualizacion": "20/08/2025 16:30"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 1,
            "last_page": 1,
            "from": 1,
            "to": 1
        }
    }
}
```

## Códigos de Error

### 400 - Bad Request

Solicitud malformada.

### 401 - Unauthorized

Token de autenticación inválido o faltante.

### 404 - Not Found

Recurso no encontrado.

### 422 - Unprocessable Entity

Errores de validación.

**Ejemplo de respuesta de error de validación:**

```json
{
    "success": false,
    "message": "Error de validación",
    "errors": {
        "email": [
            "Ya tienes un contacto registrado con este correo electrónico."
        ],
        "telefono": [
            "Ya tienes un contacto registrado con este número de teléfono."
        ]
    }
}
```

### 500 - Internal Server Error

Error interno del servidor.

## Validaciones

### Usuario

-   **nombre**: Requerido, string, máximo 255 caracteres
-   **apellido**: Requerido, string, máximo 255 caracteres
-   **email**: Requerido, email válido, único en la tabla users
-   **password**: Requerido, mínimo 8 caracteres, debe coincidir con password_confirmation

### Contacto

-   **nombre**: Requerido, string, máximo 255 caracteres
-   **apellido**: Requerido, string, máximo 255 caracteres
-   **telefono**: Requerido, string, máximo 20 caracteres, único por usuario
-   **email**: Requerido, email válido, único por usuario
-   **direccion**: Opcional, string, máximo 500 caracteres

## Notas Importantes

1. **Unicidad de contactos**: Un usuario no puede tener dos contactos con el mismo teléfono o email.
2. **Aislamiento de datos**: Los usuarios solo pueden ver y gestionar sus propios contactos.
3. **Paginación**: La lista de contactos está paginada (15 contactos por página).
4. **Tokens**: Al cerrar sesión, todos los tokens del usuario son revocados.

## Datos de Prueba

Puedes ejecutar los seeders para crear datos de prueba:

```bash
php artisan db:seed
```

Esto creará:

-   Usuario: `juan@example.com` (password: `password123`)
-   Usuario: `carmen@example.com` (password: `password123`)
-   Varios contactos de ejemplo para cada usuario

## Instalación y Configuración

1. Clona el repositorio
2. Ejecuta `composer install`
3. Copia `.env.example` a `.env` y configura la base de datos
4. Ejecuta `php artisan key:generate`
5. Ejecuta `php artisan migrate`
6. Opcionalmente ejecuta `php artisan db:seed` para datos de prueba
7. Inicia el servidor con `php artisan serve`
