# 📋 Guía Completa: API de Gestión de Contactos con Laravel

## 📝 Índice

1. [Requisitos Previos](#requisitos-previos)
2. [Instalación de Laravel](#instalación-de-laravel)
3. [Configuración Inicial](#configuración-inicial)
4. [Configuración de Base de Datos](#configuración-de-base-de-datos)
5. [Instalación de Laravel Sanctum](#instalación-de-laravel-sanctum)
6. [Creación de Migraciones](#creación-de-migraciones)
7. [Creación de Modelos](#creación-de-modelos)
8. [Creación de Form Requests](#creación-de-form-requests)
9. [Creación de API Resources](#creación-de-api-resources)
10. [Creación de Controladores](#creación-de-controladores)
11. [Configuración de Rutas](#configuración-de-rutas)
12. [Configuración de Middleware](#configuración-de-middleware)
13. [Creación de Seeders](#creación-de-seeders)
14. [Creación de Tests](#creación-de-tests)
15. [Documentación de la API](#documentación-de-la-api)
16. [Página de Bienvenida](#página-de-bienvenida)
17. [Pruebas y Validación](#pruebas-y-validación)

---

## 🔧 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

-   **PHP 8.1 o superior**
-   **Composer** (gestor de dependencias de PHP)
-   **MySQL** 5.7 o superior
-   **Node.js** (opcional, para compilar assets frontend)
-   **Git** (control de versiones)

## 🚀 1. Instalación de Laravel

### Paso 1.1: Crear el proyecto Laravel

```bash
composer create-project laravel/laravel contacts-api "^10.0"
cd contacts-api
```

**¿Qué hace esto?**

-   Descarga Laravel 10 y todas sus dependencias
-   Crea la estructura básica del proyecto
-   Configura el autoloader de Composer

### Paso 1.2: Verificar la instalación

```bash
php artisan --version
```

**Resultado esperado:** Laravel Framework 10.x.x

## ⚙️ 2. Configuración Inicial

### Paso 2.1: Configurar el archivo .env

```bash
cp .env.example .env
php artisan key:generate
```

**¿Qué hace esto?**

-   Copia el archivo de configuración de ejemplo
-   Genera una clave de aplicación única para cifrado

### Paso 2.2: Configurar variables de entorno básicas

Edita el archivo `.env`:

```env
APP_NAME="API Gestión Contactos"
APP_ENV=local
APP_KEY=base64:... # Generada automáticamente
APP_DEBUG=true
APP_URL=http://localhost:8000
```

**¿Qué configuramos?**

-   `APP_NAME`: Nombre de la aplicación
-   `APP_ENV`: Entorno (local, staging, production)
-   `APP_DEBUG`: Habilita información de debug en desarrollo
-   `APP_URL`: URL base de la aplicación

## 🗄️ 3. Configuración de Base de Datos

### Paso 3.1: Configurar MySQL en .env

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=contacts_api
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### Paso 3.2: Crear la base de datos

```sql
CREATE DATABASE contacts_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**¿Por qué utf8mb4?**

-   Soporte completo para caracteres Unicode (incluye emojis)
-   Estándar recomendado por Laravel

### Paso 3.3: Probar la conexión

```bash
php artisan migrate:status
```

**¿Qué verifica esto?**

-   La conexión a la base de datos
-   El estado de las migraciones

## 🔐 4. Instalación de Laravel Sanctum

### Paso 4.1: Instalar Sanctum

```bash
composer require laravel/sanctum
```

### Paso 4.2: Publicar la configuración

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

**¿Qué hace esto?**

-   Instala Laravel Sanctum para autenticación API
-   Publica archivos de configuración y migraciones

### Paso 4.3: Ejecutar migraciones de Sanctum

```bash
php artisan migrate
```

**¿Qué crea?**

-   Tabla `personal_access_tokens` para gestionar tokens de API

## 📊 5. Creación de Migraciones

### Paso 5.1: Migración para modificar tabla users

```bash
php artisan make:migration modify_users_table --table=users
```

**Contenido del archivo:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar columna name si existe
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }

            // Agregar nuevas columnas
            $table->string('nombre')->after('id');
            $table->string('apellido')->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'apellido']);
            $table->string('name')->after('id');
        });
    }
};
```

**¿Qué hace esta migración?**

-   Elimina la columna `name` por defecto de Laravel
-   Agrega columnas `nombre` y `apellido` separadas
-   Permite reversión con el método `down()`

### Paso 5.2: Migración para tabla contacts

```bash
php artisan make:migration create_contacts_table
```

**Contenido del archivo:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono', 20);
            $table->string('email');
            $table->text('direccion')->nullable();
            $table->timestamps();

            // Índices únicos por usuario
            $table->unique(['user_id', 'telefono']);
            $table->unique(['user_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
```

**¿Qué características tiene esta tabla?**

-   `user_id`: Clave foránea con eliminación en cascada
-   `telefono`: Máximo 20 caracteres
-   `direccion`: Campo opcional (nullable)
-   **Índices únicos**: Un usuario no puede tener contactos duplicados por teléfono o email
-   `timestamps`: Campos created_at y updated_at automáticos

### Paso 5.3: Ejecutar las migraciones

```bash
php artisan migrate
```

**¿Qué sucede?**

-   Se modifica la tabla users
-   Se crea la tabla contacts con sus restricciones
-   Se mantiene la integridad referencial

## 🏗️ 6. Creación de Modelos

### Paso 6.1: Modelo User (modificar existente)

**Archivo:** `app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relación uno a muchos con Contact
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
```

**¿Qué incluye este modelo?**

-   `HasApiTokens`: Trait de Sanctum para tokens
-   `$fillable`: Campos permitidos para asignación masiva
-   `$hidden`: Campos ocultos en serialización JSON
-   `$casts`: Conversión automática de tipos
-   `contacts()`: Relación uno a muchos

### Paso 6.2: Modelo Contact

```bash
php artisan make:model Contact
```

**Archivo:** `app/Models/Contact.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'telefono',
        'email',
        'direccion',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación inversa con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

**¿Qué características tiene?**

-   `$fillable`: Protección contra asignación masiva
-   `$casts`: Conversión automática de fechas
-   `user()`: Relación muchos a uno con User

## 📝 7. Creación de Form Requests

Los Form Requests centralizan la validación y mantienen los controladores limpios.

### Paso 7.1: Request para registro de usuario

```bash
php artisan make:request RegisterRequest
```

**Archivo:** `app/Http/Requests/RegisterRequest.php`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    // Forzar respuestas JSON para API
    public function expectsJson(): bool
    {
        return true;
    }

    public function ajax(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}
```

### Paso 7.2: Request para login

```bash
php artisan make:request LoginRequest
```

**Archivo:** `app/Http/Requests/LoginRequest.php`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function expectsJson(): bool
    {
        return true;
    }

    public function ajax(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }
}
```

### Paso 7.3: Request para contactos

```bash
php artisan make:request ContactRequest
```

**Archivo:** `app/Http/Requests/ContactRequest.php`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contactId = $this->route('contact');

        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => [
                'required',
                'string',
                'max:20',
                Rule::unique('contacts')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($contactId)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('contacts')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })->ignore($contactId)
            ],
            'direccion' => 'nullable|string|max:500',
        ];
    }

    public function expectsJson(): bool
    {
        return true;
    }

    public function ajax(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.unique' => 'Ya tienes un contacto registrado con este número de teléfono.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Ya tienes un contacto registrado con este correo electrónico.',
        ];
    }
}
```

**¿Qué validaciones especiales tiene ContactRequest?**

-   **Uniqueness scoped**: Teléfono y email únicos por usuario
-   **Ignore en updates**: Ignora el registro actual al actualizar
-   **Validation closure**: Usa funciones anónimas para validaciones complejas

## 🎯 8. Creación de API Resources

Los API Resources formatean las respuestas JSON de manera consistente.

### Paso 8.1: Resource para User

```bash
php artisan make:resource UserResource
```

**Archivo:** `app/Http/Resources/UserResource.php`

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'email' => $this->email,
        ];
    }
}
```

### Paso 8.2: Resource para Contact

```bash
php artisan make:resource ContactResource
```

**Archivo:** `app/Http/Resources/ContactResource.php`

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'nombre_completo' => $this->nombre . ' ' . $this->apellido,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'fecha_creacion' => $this->created_at->format('d/m/Y H:i'),
            'fecha_actualizacion' => $this->updated_at->format('d/m/Y H:i'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

**¿Qué campos calculados incluye?**

-   `nombre_completo`: Concatenación de nombre y apellido
-   `fecha_creacion`: Formato legible para humanos
-   `fecha_actualizacion`: Formato legible para humanos

## 🎮 9. Creación de Controladores

### Paso 9.1: Controlador de Autenticación

```bash
php artisan make:controller Api/AuthController
```

**Archivo:** `app/Http/Controllers/Api/AuthController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
            ]
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Revocar todos los tokens del usuario
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($request->user())
            ]
        ]);
    }
}
```

**¿Qué métodos incluye?**

-   `register()`: Crea usuario y genera token
-   `login()`: Autentica y genera token
-   `logout()`: Revoca todos los tokens del usuario
-   `me()`: Retorna información del usuario autenticado

### Paso 9.2: Controlador de Contactos

```bash
php artisan make:controller Api/ContactController --resource
```

**Archivo:** `app/Http/Controllers/Api/ContactController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = $request->user()
            ->contacts()
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => ContactResource::collection($contacts)->response()->getData()
        ]);
    }

    public function store(ContactRequest $request)
    {
        $contact = $request->user()->contacts()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Contacto creado exitosamente',
            'data' => new ContactResource($contact)
        ], 201);
    }

    public function show(Request $request, Contact $contact)
    {
        // Verificar que el contacto pertenece al usuario autenticado
        if ($contact->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Contacto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ContactResource($contact)
        ]);
    }

    public function update(ContactRequest $request, Contact $contact)
    {
        // Verificar que el contacto pertenece al usuario autenticado
        if ($contact->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Contacto no encontrado'
            ], 404);
        }

        $contact->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Contacto actualizado exitosamente',
            'data' => new ContactResource($contact)
        ]);
    }

    public function destroy(Request $request, Contact $contact)
    {
        // Verificar que el contacto pertenece al usuario autenticado
        if ($contact->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Contacto no encontrado'
            ], 404);
        }

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contacto eliminado exitosamente'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([
                'success' => false,
                'message' => 'Parámetro de búsqueda requerido'
            ], 400);
        }

        $contacts = $request->user()
            ->contacts()
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('nombre', 'like', "%{$query}%")
                    ->orWhere('apellido', 'like', "%{$query}%")
                    ->orWhere('telefono', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'contacts' => ContactResource::collection($contacts),
                'pagination' => [
                    'current_page' => $contacts->currentPage(),
                    'per_page' => $contacts->perPage(),
                    'total' => $contacts->total(),
                    'last_page' => $contacts->lastPage(),
                    'from' => $contacts->firstItem(),
                    'to' => $contacts->lastItem(),
                ]
            ]
        ]);
    }
}
```

**¿Qué características destacadas tiene?**

-   **Aislamiento de datos**: Usuarios solo ven sus contactos
-   **Paginación**: 15 contactos por página
-   **Búsqueda**: Por nombre, apellido, teléfono o email
-   **Ordenamiento**: Alfabético por nombre y apellido
-   **Validación de pertenencia**: Verifica ownership en show, update, destroy

## 🛣️ 10. Configuración de Rutas

### Paso 10.1: Rutas de API

**Archivo:** `routes/api.php`

```php
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use Illuminate\Support\Facades\Route;

// Rutas públicas de autenticación
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de autenticación
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Rutas de contactos
    Route::apiResource('contacts', ContactController::class);
    Route::get('contacts/search', [ContactController::class, 'search'])->name('contacts.search');
});
```

**¿Cómo están organizadas?**

-   **Rutas públicas**: register, login
-   **Rutas protegidas**: Requieren token de Sanctum
-   **Resource routes**: CRUD automático con `apiResource`
-   **Rutas personalizadas**: search endpoint

### Paso 10.2: Verificar rutas creadas

```bash
php artisan route:list --path=api
```

**Rutas generadas:**

```
POST   api/auth/register
POST   api/auth/login
POST   api/auth/logout
GET    api/auth/me
GET    api/contacts
POST   api/contacts
GET    api/contacts/{contact}
PUT    api/contacts/{contact}
DELETE api/contacts/{contact}
GET    api/contacts/search
```

## 🛡️ 11. Configuración de Middleware

### Paso 11.1: Configurar Sanctum en kernel

**Archivo:** `app/Http/Kernel.php`

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ... otros middleware
    ];

    protected $middlewareGroups = [
        'web' => [
            // ... middleware web
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        // ... otros middleware
        'auth' => \App\Http\Middleware\Authenticate::class,
    ];
}
```

### Paso 11.2: Configurar middleware de autenticación para API

**Archivo:** `app/Http/Middleware/Authenticate.php`

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // Para rutas API, retornar null en lugar de redireccionar
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        return $request->expectsJson() ? null : route('login');
    }
}
```

**¿Qué hace esta configuración?**

-   **Para APIs**: Retorna null (no redirige)
-   **Para web**: Redirige a login
-   **Detección automática**: Usa expectsJson() y rutas api/\*

## 🌱 12. Creación de Seeders

### Paso 12.1: Seeder para usuarios

```bash
php artisan make:seeder UserSeeder
```

**Archivo:** `database/seeders/UserSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario 1
        User::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Usuario 2
        User::create([
            'nombre' => 'Carmen',
            'apellido' => 'González',
            'email' => 'carmen@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
```

### Paso 12.2: Seeder para contactos

```bash
php artisan make:seeder ContactSeeder
```

**Archivo:** `database/seeders/ContactSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('email', 'juan@example.com')->first();
        $user2 = User::where('email', 'carmen@example.com')->first();

        // Contactos para Juan
        if ($user1) {
            Contact::create([
                'user_id' => $user1->id,
                'nombre' => 'María',
                'apellido' => 'García',
                'telefono' => '+34 612 345 678',
                'email' => 'maria.garcia@example.com',
                'direccion' => 'Calle Mayor 123, Madrid, España',
            ]);

            Contact::create([
                'user_id' => $user1->id,
                'nombre' => 'Carlos',
                'apellido' => 'López',
                'telefono' => '+34 687 654 321',
                'email' => 'carlos.lopez@example.com',
                'direccion' => 'Avenida de la Paz 456, Barcelona, España',
            ]);

            Contact::create([
                'user_id' => $user1->id,
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'telefono' => '+34 698 123 456',
                'email' => 'ana.martinez@example.com',
                'direccion' => null,
            ]);
        }

        // Contactos para Carmen
        if ($user2) {
            Contact::create([
                'user_id' => $user2->id,
                'nombre' => 'Pedro',
                'apellido' => 'Rodríguez',
                'telefono' => '+34 611 987 654',
                'email' => 'pedro.rodriguez@example.com',
                'direccion' => 'Plaza España 789, Valencia, España',
            ]);

            Contact::create([
                'user_id' => $user2->id,
                'nombre' => 'Laura',
                'apellido' => 'Fernández',
                'telefono' => '+34 622 456 789',
                'email' => 'laura.fernandez@example.com',
                'direccion' => 'Calle Libertad 321, Sevilla, España',
            ]);
        }
    }
}
```

### Paso 12.3: Actualizar DatabaseSeeder

**Archivo:** `database/seeders/DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ContactSeeder::class,
        ]);
    }
}
```

### Paso 12.4: Ejecutar seeders

```bash
php artisan db:seed
```

**¿Qué datos crea?**

-   2 usuarios de prueba con contraseñas conocidas
-   5 contactos de ejemplo distribuidos entre usuarios
-   Datos realistas para testing manual

## 🧪 13. Creación de Tests

### Paso 13.1: Test de autenticación

```bash
php artisan make:test AuthTest
```

**Archivo:** `tests/Feature/AuthTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_register(): void
    {
        $userData = [
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'nombre', 'apellido', 'email'],
                    'token'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'juan@example.com',
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
        ]);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'nombre', 'apellido', 'email'],
                    'token'
                ]
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Credenciales incorrectas',
            ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente',
            ]);

        // Verificar que el token fue eliminado
        $this->assertCount(0, $user->tokens);
    }

    public function test_user_can_get_profile(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user' => ['id', 'nombre', 'apellido', 'email']
                ]
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                    ]
                ]
            ]);
    }
}
```

### Paso 13.2: Test de contactos

```bash
php artisan make:test ContactTest
```

**Archivo:** `tests/Feature/ContactTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private function authenticatedUser(): array
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        return [$user, $token];
    }

    public function test_user_can_create_contact(): void
    {
        [$user, $token] = $this->authenticatedUser();

        $contactData = [
            'nombre' => 'María',
            'apellido' => 'García',
            'telefono' => '+34 612 345 678',
            'email' => 'maria@example.com',
            'direccion' => 'Calle Mayor 123',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/contacts', $contactData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id', 'user_id', 'nombre', 'apellido', 'telefono', 'email', 'direccion'
                ]
            ]);

        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'email' => 'maria@example.com',
        ]);
    }

    public function test_user_can_list_contacts(): void
    {
        [$user, $token] = $this->authenticatedUser();

        // Crear algunos contactos
        Contact::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/contacts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => ['id', 'nombre', 'apellido', 'telefono', 'email']
                    ]
                ]
            ]);
    }

    public function test_user_can_view_specific_contact(): void
    {
        [$user, $token] = $this->authenticatedUser();

        $contact = Contact::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $contact->id,
                    'email' => $contact->email,
                ]
            ]);
    }

    public function test_user_can_update_contact(): void
    {
        [$user, $token] = $this->authenticatedUser();

        $contact = Contact::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'nombre' => 'Nombre Actualizado',
            'apellido' => $contact->apellido,
            'telefono' => $contact->telefono,
            'email' => $contact->email,
            'direccion' => 'Nueva dirección',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/contacts/{$contact->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Contacto actualizado exitosamente',
            ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'nombre' => 'Nombre Actualizado',
            'direccion' => 'Nueva dirección',
        ]);
    }

    public function test_user_can_delete_contact(): void
    {
        [$user, $token] = $this->authenticatedUser();

        $contact = Contact::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Contacto eliminado exitosamente',
            ]);

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id,
        ]);
    }

    public function test_user_can_search_contacts(): void
    {
        [$user, $token] = $this->authenticatedUser();

        Contact::factory()->create([
            'user_id' => $user->id,
            'nombre' => 'María',
            'apellido' => 'García',
        ]);

        Contact::factory()->create([
            'user_id' => $user->id,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/contacts/search?q=María');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'contacts',
                    'pagination'
                ]
            ]);
    }

    public function test_user_cannot_access_other_user_contacts(): void
    {
        [$user1, $token1] = $this->authenticatedUser();
        [$user2, $token2] = $this->authenticatedUser();

        // Crear contacto para user2
        $contact = Contact::factory()->create(['user_id' => $user2->id]);

        // user1 intenta acceder al contacto de user2
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
        ])->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Contacto no encontrado',
            ]);
    }

    public function test_contact_validation_prevents_duplicate_email_per_user(): void
    {
        [$user, $token] = $this->authenticatedUser();

        // Crear primer contacto
        Contact::factory()->create([
            'user_id' => $user->id,
            'email' => 'duplicate@example.com',
        ]);

        // Intentar crear segundo contacto con mismo email
        $contactData = [
            'nombre' => 'Segundo',
            'apellido' => 'Contacto',
            'telefono' => '+34 999 888 777',
            'email' => 'duplicate@example.com',
            'direccion' => 'Otra dirección',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/contacts', $contactData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
```

### Paso 13.3: Crear Factory para Contact

```bash
php artisan make:factory ContactFactory
```

**Archivo:** `database/factories/ContactFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nombre' => $this->faker->firstName,
            'apellido' => $this->faker->lastName,
            'telefono' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'direccion' => $this->faker->address,
        ];
    }
}
```

### Paso 13.4: Actualizar Factory de User

**Archivo:** `database/factories/UserFactory.php`

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName,
            'apellido' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

### Paso 13.5: Ejecutar tests

```bash
php artisan test
```

**¿Qué validan estos tests?**

-   **Funcionalidades básicas**: CRUD completo
-   **Autenticación**: Login, logout, registro
-   **Autorización**: Usuarios solo acceden a sus datos
-   **Validaciones**: Duplicados, campos requeridos
-   **Formato de respuestas**: JSON estructura consistente

## ✅ 14. Pruebas y Validación

### Paso 14.1: Ejecutar todas las pruebas

```bash
php artisan test --coverage
```

### Paso 14.2: Verificar rutas

```bash
php artisan route:list --path=api
```

### Paso 14.3: Limpiar cachés

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Paso 14.4: Pruebas manuales con cURL

**Registrar usuario:**

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test",
    "apellido": "User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Login:**

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

**Crear contacto (con token):**

```bash
curl -X POST http://localhost:8000/api/contacts \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -d '{
    "nombre": "María",
    "apellido": "García",
    "telefono": "+34 612 345 678",
    "email": "maria@example.com",
    "direccion": "Calle Mayor 123"
  }'
```

## 🚀 15. Comandos de Finalización

### Paso 15.1: Comandos para producción

```bash
# Optimizar autoloader
composer install --optimize-autoloader --no-dev

# Optimizar configuración
php artisan config:cache

# Optimizar rutas
php artisan route:cache

# Optimizar vistas
php artisan view:cache
```

### Paso 15.2: Variables de entorno para producción

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

# Configuración de base de datos de producción
DB_CONNECTION=mysql
DB_HOST=tu-servidor-db
DB_PORT=3306
DB_DATABASE=tu_base_datos_prod
DB_USERNAME=tu_usuario_prod
DB_PASSWORD=tu_contraseña_segura
```

## 📋 Resumen Final

### ✅ Lo que hemos creado:

1. **API REST completa** con Laravel 10
2. **Sistema de autenticación** con Laravel Sanctum
3. **CRUD de contactos** con validaciones robustas
4. **Tests automatizados** (16 tests pasando)
5. **Documentación completa** de la API
6. **Página de bienvenida** interactiva
7. **Seeders** con datos de prueba
8. **Validaciones de seguridad** y aislamiento de datos

### 🎯 Características destacadas:

-   **Seguridad**: Autenticación por tokens, aislamiento de datos por usuario
-   **Validación**: Prevención de duplicados, validación de formatos
-   **Escalabilidad**: Paginación, estructura modular
-   **Mantenibilidad**: Form Requests, API Resources, tests completos
-   **Documentación**: API bien documentada con ejemplos

### 🚀 Próximos pasos opcionales:

1. **Rate Limiting**: Implementar límites de peticiones
2. **Logging**: Registrar eventos importantes
3. **Notificaciones**: Emails de bienvenida
4. **Filtros avanzados**: Filtrar contactos por criterios
5. **Exportación**: Exportar contactos a CSV/Excel
6. **API Versioning**: Versionado de la API
7. **Docker**: Containerización del proyecto
8. **CI/CD**: Pipeline de integración continua

¡La API de gestión de contactos está completamente funcional y lista para producción! 🎉
