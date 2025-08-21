# 📇 API de Gestión de Contactos

API REST desarrollada en Laravel para la gestión de contactos personales con autenticación de usuarios.

## 🚀 Características

-   **Autenticación completa**: Registro, login, logout y gestión de sesiones con Laravel Sanctum
-   **Gestión de contactos**: CRUD completo (Crear, Leer, Actualizar, Eliminar)
-   **Validaciones robustas**: Prevención de duplicados y validación de datos
-   **Búsqueda avanzada**: Búsqueda por nombre, apellido, teléfono o email
-   **Respuestas consistentes**: Formato JSON estandarizado para todas las respuestas
-   **Aislamiento de datos**: Cada usuario solo puede acceder a sus propios contactos
-   **Paginación**: Listados paginados para mejor rendimiento
-   **Tests automatizados**: Cobertura completa de funcionalidades

## 🛠️ Tecnologías Utilizadas

-   **Laravel 10**: Framework PHP principal
-   **Laravel Sanctum**: Sistema de autenticación por tokens
-   **MySQL**: Base de datos relacional
-   **PHPUnit**: Framework de testing
-   **Eloquent ORM**: Manejo de base de datos

## 📋 Requisitos

-   PHP 8.1 o superior
-   Composer
-   MySQL 5.7 o superior
-   Laravel 10.x

## ⚡ Instalación Rápida

1. **Clonar el repositorio**

```bash
git clone https://github.com/HBE-Quantumisp/contacts-api.git
cd contacts-api
```

2. **Instalar dependencias**

    ```bash
    composer install
    ```

3. **Configurar entorno**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configurar base de datos**
   Edita el archivo `.env` con tus credenciales de base de datos

5. **Ejecutar migraciones**

    ```bash
    php artisan migrate
    ```

6. **Cargar datos de prueba (opcional)**

    ```bash
    php artisan db:seed
    ```

7. **Iniciar servidor**

    ```bash
    php artisan serve
    ```

8. **Instalar dependencias**

    ```bash
    composer install
    ```

9. **Configurar entorno**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

10. **Configurar base de datos**
    Edita el archivo `.env` con tus credenciales de base de datos

11. **Ejecutar migraciones**

    ```bash
    php artisan migrate
    ```

12. **Cargar datos de prueba (opcional)**

    ```bash
    php artisan db:seed
    ```

13. **Iniciar servidor**
    ```bash
    php artisan serve
    ```

## 📖 Documentación Completa

Consulta la [documentación completa de la API](API_DOCUMENTATION.md) para ver todos los endpoints y ejemplos de uso.

## 🧪 Testing

```bash
php artisan test
```

## 💾 Datos de Prueba

**Usuarios de prueba:**

-   Email: `juan@example.com` | Password: `password123`
-   Email: `carmen@example.com` | Password: `password123`

## 🔒 Validaciones Implementadas

-   Usuarios no pueden tener emails duplicados
-   Un usuario no puede tener contactos con el mismo teléfono
-   Un usuario no puede tener contactos con el mismo email
-   Validación de formatos de email y longitudes de campos

---

**Proyecto prueba usado como material de formación para el programa de ADSO**

---

¿Quieres replicar este proyecto desde cero?

Consulta la [Guía paso a paso para la creación de la API](GUIA.md) donde encontrarás todas las instrucciones detalladas para construir la API desde el inicio.
