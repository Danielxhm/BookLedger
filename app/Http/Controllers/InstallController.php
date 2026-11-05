<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use PDO;
use Exception;

class InstallController extends Controller
{
    /**
     * Paso 1: Verificación de Requisitos del Servidor y Entorno
     */
    public function index()
    {
        if (file_exists(storage_path('installed'))) {
            return redirect()->route('login');
        }

        $phpVersion = phpversion();
        $phpSatisfied = version_compare($phpVersion, '8.2.0', '>=');

        $extensions = [
            'pdo' => extension_loaded('pdo'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'mbstring' => extension_loaded('mbstring'),
            'openssl' => extension_loaded('openssl'),
            'tokenizer' => extension_loaded('tokenizer'),
            'xml' => extension_loaded('xml'),
            'ctype' => extension_loaded('ctype'),
            'json' => extension_loaded('json'),
            'fileinfo' => extension_loaded('fileinfo'),
            'curl' => extension_loaded('curl'),
        ];
        $extensionsSatisfied = !in_array(false, $extensions, true);

        $directories = [
            'storage' => is_writable(storage_path()),
            'storage/framework' => is_writable(storage_path('framework')),
            'storage/logs' => is_writable(storage_path('logs')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            '.env' => file_exists(base_path('.env')) ? is_writable(base_path('.env')) : is_writable(base_path()),
        ];
        $directoriesSatisfied = !in_array(false, $directories, true);

        // Verificación del archivo SQL de instalación
        $sqlPath = database_path('install.sql');
        $sqlExists = file_exists($sqlPath);
        $sqlSize = $sqlExists ? round(filesize($sqlPath) / 1024, 1) : 0;

        $canProceed = $phpSatisfied && $extensionsSatisfied && $directoriesSatisfied;

        return view('install.step1-requirements', compact(
            'phpVersion',
            'phpSatisfied',
            'extensions',
            'extensionsSatisfied',
            'directories',
            'directoriesSatisfied',
            'sqlExists',
            'sqlPath',
            'sqlSize',
            'canProceed'
        ));
    }

    /**
     * Paso 2: Formulario de Configuración de Base de Datos y Sistema
     */
    public function database(Request $request)
    {
        if (file_exists(storage_path('installed'))) {
            return redirect()->route('login');
        }

        $defaultAppUrl = $request->root();
        $defaultHost = env('DB_HOST', '127.0.0.1');
        $defaultPort = env('DB_PORT', '3306');
        $defaultDatabase = env('DB_DATABASE', 'sistema_biblioteca');
        $defaultUsername = env('DB_USERNAME', 'root');
        $defaultPassword = env('DB_PASSWORD', '');

        $sqlPath = database_path('install.sql');
        $sqlExists = file_exists($sqlPath);

        return view('install.step2-database', compact(
            'defaultAppUrl',
            'defaultHost',
            'defaultPort',
            'defaultDatabase',
            'defaultUsername',
            'defaultPassword',
            'sqlExists'
        ));
    }

    /**
     * Paso 3: Proceso de Instalación (Crear BD, Importar SQL, Configurar .env, Bloqueo/Autodestrucción)
     */
    public function process(Request $request)
    {
        if (file_exists(storage_path('installed'))) {
            return redirect()->route('login');
        }

        $request->validate([
            'app_name' => 'required|string|max:100',
            'app_url' => 'required|url',
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string|regex:/^[a-zA-Z0-9_\-]+$/',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
            'admin_name' => 'required|string|max:100',
            'admin_email' => 'required|email|max:150',
            'admin_password' => 'required|string|min:8|confirmed',
        ], [
            'db_database.regex' => 'El nombre de la base de datos solo puede contener letras, números, guiones y guiones bajos.',
            'admin_password.min' => 'La contraseña del administrador debe tener al menos 8 caracteres.',
            'admin_password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $appName = $request->input('app_name', 'BookLedger');
        $appUrl = rtrim($request->input('app_url'), '/');
        $host = $request->input('db_host');
        $port = $request->input('db_port');
        $database = $request->input('db_database');
        $username = $request->input('db_username');
        $password = $request->input('db_password', '');
        $adminName = $request->input('admin_name');
        $adminEmail = $request->input('admin_email');
        $adminPassword = $request->input('admin_password');
        $autoCreateDb = $request->boolean('auto_create_db', true);
        $autoDestruct = $request->boolean('auto_destruct', true);

        // 1. Probar conexión al motor MySQL (sin base de datos)
        try {
            $pdoOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            ];
            
            if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
                $pdoOptions[PDO::MYSQL_ATTR_MULTI_STATEMENTS] = true;
            }

            $dsnWithoutDb = "mysql:host={$host};port={$port}";
            $pdo = new PDO($dsnWithoutDb, $username, $password, $pdoOptions);
        } catch (Exception $e) {
            return back()->withInput()->withErrors([
                'db_error' => 'No se pudo conectar al servidor MySQL: ' . $e->getMessage() . '. Revisa el host, puerto, usuario y contraseña.'
            ]);
        }

        // 2. Crear la base de datos si no existe
        if ($autoCreateDb) {
            try {
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            } catch (Exception $e) {
                return back()->withInput()->withErrors([
                    'db_error' => 'No se pudo crear automáticamente la base de datos: ' . $e->getMessage()
                ]);
            }
        }

        // 3. Conectarse a la base de datos específica
        try {
            $pdo->exec("USE `{$database}`;");
        } catch (Exception $e) {
            return back()->withInput()->withErrors([
                'db_error' => "No se pudo seleccionar la base de datos '{$database}'. Asegúrate de que existe o marca la opción para crearla automáticamente."
            ]);
        }

        // 4. Importar el archivo SQL (database/install.sql)
        $sqlPath = database_path('install.sql');
        if (file_exists($sqlPath)) {
            try {
                $sqlContent = file_get_contents($sqlPath);
                
                // Desactivar temporalmente foreign keys para importación limpia
                $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
                
                // Ejecutar el SQL de manera robusta
                $this->executeSqlDump($pdo, $sqlContent);

                $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
            } catch (Exception $e) {
                return back()->withInput()->withErrors([
                    'db_error' => 'Error al importar el archivo database/install.sql: ' . $e->getMessage()
                ]);
            }
        } else {
            // Fallback: si no hay install.sql, ejecutar migraciones y seeders de Laravel
            try {
                config([
                    'database.connections.mysql.host' => $host,
                    'database.connections.mysql.port' => $port,
                    'database.connections.mysql.database' => $database,
                    'database.connections.mysql.username' => $username,
                    'database.connections.mysql.password' => $password,
                ]);
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('db:seed', ['--force' => true]);
            } catch (Exception $e) {
                return back()->withInput()->withErrors([
                    'db_error' => 'No se encontró database/install.sql y falló la migración automática: ' . $e->getMessage()
                ]);
            }
        }

        // 5. Crear o actualizar la cuenta del Super Administrador
        try {
            $adminPasswordHash = password_hash($adminPassword, PASSWORD_BCRYPT);

            // Verificar si el usuario ya existe por email
            $checkUser = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $checkUser->execute([$adminEmail]);
            $existingUser = $checkUser->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                $userId = $existingUser['id'];
                $stmt = $pdo->prepare("UPDATE users SET name = ?, password = ?, email_verified_at = NOW(), updated_at = NOW() WHERE id = ?");
                $stmt->execute([$adminName, $adminPasswordHash, $userId]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW(), NOW())");
                $stmt->execute([$adminName, $adminEmail, $adminPasswordHash]);
                $userId = $pdo->lastInsertId();
            }

            // Asignar el rol 'Administrador' (id 1)
            $roleStmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'Administrador' LIMIT 1");
            $roleStmt->execute();
            $role = $roleStmt->fetch(PDO::FETCH_ASSOC);
            $roleId = $role ? $role['id'] : 1;

            $mhrStmt = $pdo->prepare("INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id) VALUES (?, 'App\\\\Models\\\\User', ?)");
            $mhrStmt->execute([$roleId, $userId]);

            // Actualizar o vincular en la tabla bibliotecarios
            $biblioCheck = $pdo->prepare("SELECT Codigo_Bibliotecario FROM bibliotecarios WHERE user_id = ? LIMIT 1");
            $biblioCheck->execute([$userId]);
            $existingBiblio = $biblioCheck->fetch(PDO::FETCH_ASSOC);

            if ($existingBiblio) {
                $stmtBiblio = $pdo->prepare("UPDATE bibliotecarios SET Nombres = ?, clave_hash = ?, EstadoEmpleado = 'Activo' WHERE Codigo_Bibliotecario = ?");
                $stmtBiblio->execute([$adminName, $adminPasswordHash, $existingBiblio['Codigo_Bibliotecario']]);
            } else {
                $biblio1 = $pdo->query("SELECT Codigo_Bibliotecario FROM bibliotecarios WHERE Codigo_Bibliotecario = 1")->fetch(PDO::FETCH_ASSOC);
                if ($biblio1) {
                    $stmtBiblio = $pdo->prepare("UPDATE bibliotecarios SET Nombres = ?, user_id = ?, clave_hash = ?, EstadoEmpleado = 'Activo' WHERE Codigo_Bibliotecario = 1");
                    $stmtBiblio->execute([$adminName, $userId, $adminPasswordHash]);
                } else {
                    $stmtBiblio = $pdo->prepare("INSERT INTO bibliotecarios (Num_documento, Nombres, Apellidos, NombreUsuario, clave_hash, EstadoEmpleado, user_id, created_at) VALUES ('12212111-1', ?, 'Admin', 'admin', ?, 'Activo', ?, NOW())");
                    $stmtBiblio->execute([$adminName, $adminPasswordHash, $userId]);
                }
            }
        } catch (Exception $e) {
            return back()->withInput()->withErrors([
                'db_error' => 'Error al configurar el usuario Super Administrador: ' . $e->getMessage()
            ]);
        }

        // 6. Actualizar el archivo .env
        try {
            $this->updateEnvironmentFile([
                'APP_NAME' => $appName,
                'APP_URL' => $appUrl,
                'DB_CONNECTION' => 'mysql',
                'DB_HOST' => $host,
                'DB_PORT' => $port,
                'DB_DATABASE' => $database,
                'DB_USERNAME' => $username,
                'DB_PASSWORD' => $password,
                'SESSION_DRIVER' => 'database',
                'CACHE_STORE' => 'database',
            ]);
        } catch (Exception $e) {
            return back()->withInput()->withErrors([
                'db_error' => 'Error al actualizar el archivo .env: ' . $e->getMessage()
            ]);
        }

        // 6.5 Asegurar APP_KEY sin Artisan (llamar Artisan dentro de una petición
        // HTTP puede tumbar el worker del servidor built-in en Windows)
        $envContent = (string) file_get_contents(base_path('.env'));
        if (! preg_match('/^APP_KEY=base64:.+/m', $envContent)) {
            $this->updateEnvironmentFile(['APP_KEY' => 'base64:' . base64_encode(random_bytes(32))]);
        }

        // 7. Limpiar cachés compiladas directamente sobre el filesystem
        @unlink(base_path('bootstrap/cache/config.php'));
        foreach (glob(storage_path('framework/views/*.php')) ?: [] as $viewCompilada) {
            @unlink($viewCompilada);
        }

        // 7.5 Enlace público de storage para servir portadas e imágenes subidas.
        // En Windows se prefiere una junction (no requiere privilegios de symlink).
        if (! file_exists(public_path('storage'))) {
            if (PHP_OS_FAMILY === 'Windows') {
                @exec('mklink /J "' . public_path('storage') . '" "' . storage_path('app/public') . '"');
            }

            if (! file_exists(public_path('storage'))) {
                @symlink(storage_path('app/public'), public_path('storage'));
            }
        }

        // 8. Crear archivo de bloqueo permanente (storage/installed)
        $installMetadata = [
            'installed_at' => now()->toIso8601String(),
            'app_name' => $appName,
            'app_url' => $appUrl,
            'database' => $database,
            'ip' => $request->ip(),
            'auto_destruct' => $autoDestruct,
            'status' => 'LOCKED_AND_COMPLETED',
        ];

        file_put_contents(storage_path('installed'), json_encode($installMetadata, JSON_PRETTY_PRINT));

        // 9. Auto-destrucción del archivo SQL si fue marcado
        if ($autoDestruct && file_exists($sqlPath)) {
            // Renombrar como salvaguarda o eliminar de acuerdo a la instrucción
            @rename($sqlPath, database_path('install.sql.installed'));
        }

        return redirect()->route('install.success')
            ->with('installation_completed', true)
            ->with('admin_email', $adminEmail)
            ->with('admin_name', $adminName);
    }

    /**
     * Paso 4: Pantalla de Éxito y Confirmación Final
     */
    public function success()
    {
        return view('install.step3-success');
    }

    /**
     * Ejecuta sentencias SQL soportando multi-query y parser por bloques
     */
    protected function executeSqlDump(PDO $pdo, string $sqlContent): void
    {
        // Si el driver tiene habilitado multi-statement, intentamos ejecución directa
        try {
            $pdo->exec($sqlContent);
            return;
        } catch (Exception $e) {
            // Si falla la ejecución directa, particionamos sentencias de forma segura
        }

        $lines = explode("\n", $sqlContent);
        $query = '';
        $delimiter = ';';

        foreach ($lines as $line) {
            $trimmed = trim($line);
            
            // Ignorar comentarios y líneas vacías
            if ($trimmed === '' || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '/*') || str_starts_with($trimmed, '#')) {
                continue;
            }

            // Manejo de DELIMITER personalizado si existiera
            if (preg_match('/^DELIMITER\s+(.+)$/i', $trimmed, $matches)) {
                $delimiter = trim($matches[1]);
                continue;
            }

            $query .= $line . "\n";

            if (str_ends_with($trimmed, $delimiter)) {
                $statement = trim(substr(trim($query), 0, -strlen($delimiter)));
                if ($statement !== '') {
                    $pdo->exec($statement);
                }
                $query = '';
            }
        }

        if (trim($query) !== '') {
            $pdo->exec(trim($query));
        }
    }

    /**
     * Actualiza las variables de entorno en el archivo .env
     */
    protected function updateEnvironmentFile(array $values): void
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $envPath);
            } else {
                touch($envPath);
            }
        }

        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $value = (string)$value;
            $formattedValue = (str_contains($value, ' ') || str_contains($value, '#') || $value === '')
                ? '"' . str_replace('"', '\"', $value) . '"'
                : $value;

            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "{$key}={$formattedValue}", $content);
            } else {
                $content .= "\n{$key}={$formattedValue}";
            }
        }

        file_put_contents($envPath, $content);
    }
}
