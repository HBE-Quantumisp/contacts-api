<!DOCTYPE html>
<h lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>API de Gestión de Contactos</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Custom Styles -->
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Inter', sans-serif;
                line-height: 1.6;
                color: #333;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 2rem;
            }
            
            .header {
                text-align: center;
                color: white;
                margin-bottom: 3rem;
            }
            
            .header h1 {
                font-size: 3rem;
                font-weight: 700;
                margin-bottom: 1rem;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            }
            
            .header p {
                font-size: 1.2rem;
                opacity: 0.9;
                margin-bottom: 2rem;
            }
            
            .badge {
                display: inline-block;
                background: rgba(255,255,255,0.2);
                padding: 0.5rem 1rem;
                border-radius: 50px;
                color: white;
                font-weight: 500;
                margin: 0 0.5rem;
            }
            
            .content {
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            
            .nav-tabs {
                display: flex;
                background: #f8f9fa;
                border-bottom: 1px solid #e9ecef;
            }
            
            .nav-tab {
                flex: 1;
                padding: 1.5rem;
                text-align: center;
                background: none;
                border: none;
                cursor: pointer;
                font-weight: 600;
                color: #6c757d;
                transition: all 0.3s ease;
            }
            
            .nav-tab.active {
                background: white;
                color: #667eea;
                border-bottom: 3px solid #667eea;
            }
            
            .tab-content {
                display: none;
                padding: 2rem;
            }
            
            .tab-content.active {
                display: block;
            }
            
            .endpoint-card {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                border-left: 4px solid #667eea;
            }
            
            .method {
                display: inline-block;
                padding: 0.3rem 0.8rem;
                border-radius: 5px;
                color: white;
                font-weight: 600;
                font-size: 0.9rem;
                margin-right: 1rem;
            }
            
            .method.post { background: #28a745; }
            .method.get { background: #007bff; }
            .method.put { background: #ffc107; color: #000; }
            .method.delete { background: #dc3545; }
            
            .endpoint-url {
                font-family: monospace;
                font-size: 1.1rem;
                color: #495057;
                font-weight: 600;
            }
            
            .code-block {
                background: #2d3748;
                color: #e2e8f0;
                padding: 1.5rem;
                border-radius: 8px;
                overflow-x: auto;
                margin: 1rem 0;
                font-family: 'Courier New', monospace;
                font-size: 0.9rem;
                line-height: 1.5;
            }
            
            .feature-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.5rem;
                margin: 2rem 0;
            }
            
            .feature-card {
                background: #f8f9fa;
                padding: 1.5rem;
                border-radius: 10px;
                text-align: center;
                border: 2px solid transparent;
                transition: all 0.3s ease;
            }
            
            .feature-card:hover {
                border-color: #667eea;
                transform: translateY(-5px);
            }
            
            .feature-icon {
                font-size: 2.5rem;
                margin-bottom: 1rem;
            }
            
            .feature-title {
                font-size: 1.2rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: #495057;
            }
            
            .btn {
                display: inline-block;
                padding: 0.8rem 1.5rem;
                background: #667eea;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s ease;
                margin: 0.5rem;
            }
            
            .btn:hover {
                background: #5a6fd8;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            }
            
            .btn-secondary {
                background: #6c757d;
            }
            
            .btn-secondary:hover {
                background: #5a6268;
            }
            
            .text-center {
                text-align: center;
            }

            .mt-3 {
                margin-top: 1.5rem;
            }
            
            .mt-3 { margin-top: 1.5rem; }
            .mb-2 { margin-bottom: 1rem; }
            .mb-3 { margin-bottom: 1.5rem; }
            
            @media (max-width: 768px) {
                .container {
                    padding: 1rem;
                }
                
                .header h1 {
                    font-size: 2rem;
                }
                
                .nav-tabs {
                    flex-direction: column;
                }
                
                .feature-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>📇 API de Gestión de Contactos</h1>
                <p>Una API REST completa desarrollada en Laravel para la gestión de contactos personales</p>
                <div>
                    <span class="badge">Laravel 10</span>
                    <span class="badge">Laravel Sanctum</span>
                    <span class="badge">MySQL</span>
                    <span class="badge">PHPUnit</span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="content">
                <!-- Navigation Tabs -->
                <div class="nav-tabs">
                    <button class="nav-tab active" onclick="showTab('overview')">🏠 Descripción</button>
                    <button class="nav-tab" onclick="showTab('auth')">🔐 Autenticación</button>
                    <button class="nav-tab" onclick="showTab('contacts')">📇 Contactos</button>
                    <button class="nav-tab" onclick="showTab('installation')">⚡ Instalación</button>
                </div>

                <!-- Overview Tab -->
                <div id="overview" class="tab-content active">
                    <h2 class="mb-3">🚀 Características Principales</h2>
                    
                    <div class="feature-grid">
                        <div class="feature-card">
                            <div class="feature-icon">🔐</div>
                            <div class="feature-title">Autenticación Completa</div>
                            <p>Sistema de registro, login y logout con Laravel Sanctum</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">📇</div>
                            <div class="feature-title">CRUD Completo</div>
                            <p>Crear, leer, actualizar y eliminar contactos</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">🔍</div>
                            <div class="feature-title">Búsqueda Avanzada</div>
                            <p>Búsqueda por nombre, apellido, teléfono o email</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">✅</div>
                            <div class="feature-title">Validaciones Robustas</div>
                            <p>Prevención de duplicados y validación de datos</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">🔒</div>
                            <div class="feature-title">Aislamiento de Datos</div>
                            <p>Cada usuario solo accede a sus propios contactos</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">🧪</div>
                            <div class="feature-title">Tests Automatizados</div>
                            <p>Cobertura completa con PHPUnit</p>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <strong>Base URL:</strong> 
                        <code style="background: #f8f9fa; padding: 0.3rem 0.6rem; border-radius: 4px;">
                            {{ url('/api') }}
                        </code>
                    </div>
                </div>

                <!-- Authentication Tab -->
                <div id="auth" class="tab-content">
                    <h2 class="mb-3">🔐 Endpoints de Autenticación</h2>
                    
                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method post">POST</span>
                            <span class="endpoint-url">/auth/register</span>
                        </div>
                        <p><strong>Descripción:</strong> Registrar un nuevo usuario</p>
                        <div class="code-block">
{
    "nombre": "Juan",
    "apellido": "Pérez", 
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}</div>
                    </div>

                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method post">POST</span>
                            <span class="endpoint-url">/auth/login</span>
                        </div>
                        <p><strong>Descripción:</strong> Iniciar sesión y obtener token</p>
                        <div class="code-block">
{
    "email": "juan@example.com",
    "password": "password123"
}</div>
                    </div>

                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method post">POST</span>
                            <span class="endpoint-url">/auth/logout</span>
                        </div>
                        <p><strong>Descripción:</strong> Cerrar sesión y revocar token</p>
                        <p><strong>Headers:</strong> <code>Authorization: Bearer {token}</code></p>
                    </div>

                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method get">GET</span>
                            <span class="endpoint-url">/auth/me</span>
                        </div>
                        <p><strong>Descripción:</strong> Obtener información del usuario autenticado</p>
                        <p><strong>Headers:</strong> <code>Authorization: Bearer {token}</code></p>
                    </div>
                </div>

                <!-- Contacts Tab -->
                <div id="contacts" class="tab-content">
                    <h2 class="mb-3">📇 Endpoints de Contactos</h2>
                    
                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method get">GET</span>
                            <span class="endpoint-url">/contacts</span>
                        </div>
                        <p><strong>Descripción:</strong> Listar todos los contactos del usuario (paginado)</p>
                        <p><strong>Headers:</strong> <code>Authorization: Bearer {token}</code></p>
                    </div>

                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method post">POST</span>
                            <span class="endpoint-url">/contacts</span>
                        </div>
                        <p><strong>Descripción:</strong> Crear un nuevo contacto</p>
                        <div class="code-block">
{
    "nombre": "María",
    "apellido": "García",
    "telefono": "+34 612 345 678",
    "email": "maria.garcia@example.com",
    "direccion": "Calle Mayor 123, Madrid, España"
}</div>
                    </div>

                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method get">GET</span>
                            <span class="endpoint-url">/contacts/{id}</span>
                        </div>
                        <p><strong>Descripción:</strong> Obtener un contacto específico</p>
                        <p><strong>Headers:</strong> <code>Authorization: Bearer {token}</code></p>
                    </div>

                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method put">PUT</span>
                            <span class="endpoint-url">/contacts/{id}</span>
                        </div>
                        <p><strong>Descripción:</strong> Actualizar un contacto existente</p>
                        <p><strong>Headers:</strong> <code>Authorization: Bearer {token}</code></p>
                    </div>

                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method delete">DELETE</span>
                            <span class="endpoint-url">/contacts/{id}</span>
                        </div>
                        <p><strong>Descripción:</strong> Eliminar un contacto</p>
                        <p><strong>Headers:</strong> <code>Authorization: Bearer {token}</code></p>
                    </div>

                    <div class="endpoint-card">
                        <div class="mb-2">
                            <span class="method get">GET</span>
                            <span class="endpoint-url">/contacts/search?q={término}</span>
                        </div>
                        <p><strong>Descripción:</strong> Buscar contactos por nombre, apellido, teléfono o email</p>
                        <p><strong>Headers:</strong> <code>Authorization: Bearer {token}</code></p>
                    </div>
                </div>

                <!-- Installation Tab -->
                <div id="installation" class="tab-content">
                    <h2 class="mb-3">⚡ Instalación y Configuración</h2>
                    
                    <div class="endpoint-card">
                        <h3 class="mb-2">1. Instalar dependencias</h3>
                        <div class="code-block">composer install</div>
                    </div>

                    <div class="endpoint-card">
                        <h3 class="mb-2">2. Configurar entorno</h3>
                        <div class="code-block">cp .env.example .env
php artisan key:generate</div>
                    </div>

                    <div class="endpoint-card">
                        <h3 class="mb-2">3. Configurar base de datos</h3>
                        <p>Edita el archivo <code>.env</code> con tus credenciales de MySQL</p>
                    </div>

                    <div class="endpoint-card">
                        <h3 class="mb-2">4. Ejecutar migraciones</h3>
                        <div class="code-block">php artisan migrate</div>
                    </div>

                    <div class="endpoint-card">
                        <h3 class="mb-2">5. Cargar datos de prueba (opcional)</h3>
                        <div class="code-block">php artisan db:seed</div>
                        <p><strong>Usuarios de prueba:</strong></p>
                        <ul style="margin-top: 0.5rem;">
                            <li>📧 <code>juan@example.com</code> | 🔑 <code>password123</code></li>
                            <li>📧 <code>carmen@example.com</code> | 🔑 <code>password123</code></li>
                        </ul>
                    </div>

                    <div class="endpoint-card">
                        <h3 class="mb-2">6. Iniciar servidor</h3>
                        <div class="code-block">php artisan serve</div>
                    </div>

                    <div class="endpoint-card">
                        <h3 class="mb-2">7. Ejecutar tests</h3>
                        <div class="code-block">php artisan test</div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="https://insomnia.rest/download" class="btn" target="_blank">🚀 Probar API</a>
                        <a href="https://github.com" class="btn btn-secondary" target="_blank">📚 Ver en GitHub</a>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 mt-3">
                <div class="max-w-7xl mx-auto py-4 px-6">
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                </div>
            </footer>
        </div>

        <script>
            function showTab(tabName) {
                // Hide all tab contents
                const tabContents = document.querySelectorAll('.tab-content');
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });

                // Remove active class from all tabs
                const tabs = document.querySelectorAll('.nav-tab');
                tabs.forEach(tab => {
                    tab.classList.remove('active');
                });

                // Show selected tab content
                document.getElementById(tabName).classList.add('active');

                // Add active class to clicked tab
                event.target.classList.add('active');
            }
        </script>
    </body>
</html>
