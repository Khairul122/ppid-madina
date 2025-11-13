<?php
// Mulai session
session_start();

// Memuat koneksi database
require_once 'config/koneksi.php';
$database = new Database();

// Menentukan controller dan action default
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'beranda';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Fungsi untuk mencari file controller dengan berbagai format penamaan
function findControllerFile($controllerName) {
    $controllerDir = 'controllers/';

    // Berbagai format penamaan yang mungkin
    $possibleNames = [
        // Format PascalCase/CamelCase - yang paling umum
        ucfirst($controllerName) . 'Controller.php',
        // Format as-is (tanpa perubahan case)
        $controllerName . 'Controller.php',
        // Format lowercase
        strtolower($controllerName) . 'Controller.php',
        // Format UPPERCASE
        strtoupper($controllerName) . 'Controller.php',
        // Handle multi-kata: ubah "permohonanadmin" menjadi "PermohonanAdmin"
        ucfirst(preg_replace('/([a-z])([A-Z])/', '$1 $2', $controllerName)) . 'Controller.php',
        // Handle tanpa spasi: ubah "permohonanadmin" menjadi "Permohonan Admin" lalu gabung
        str_replace(' ', '', ucwords(strtolower(preg_replace('/([A-Z])/', ' $1', $controllerName)))) . 'Controller.php',
        // Format dengan underscore
        strtolower(preg_replace('/([A-Z])/', '_$1', $controllerName)) . 'Controller.php'
    ];

    // Special handling untuk kasus seperti "permohonanadmin" -> "PermohonanAdminController"
    if (strtolower($controllerName) === 'permohonanadmin') {
        $possibleNames[] = 'PermohonanAdminController.php';
    }

    // Special handling untuk kasus lain yang sering digunakan
    $specialCases = [
        'beranda' => 'BerandaController.php',
        'home' => 'HomeController.php',
        'auth' => 'AuthController.php',
        'dashboard' => 'DashboardController.php',
        'user' => 'UserController.php',
        'profile' => 'ProfileController.php',
        'download' => 'DownloadController.php',
        'sosialmedia' => 'SosialMediaController.php',
        'informasipublik' => 'InformasiPublikController.php',
        'layananketerangan' => 'LayananKeteranganController.php'
    ];

    if (isset($specialCases[strtolower($controllerName)])) {
        array_unshift($possibleNames, $specialCases[strtolower($controllerName)]);
    }

    // Debug: tampilkan semua kemungkinan yang dicoba
    error_log("Mencari controller untuk: " . $controllerName);
    foreach ($possibleNames as $fileName) {
        $filePath = $controllerDir . $fileName;
        error_log("Mencoba: " . $filePath);
        if (file_exists($filePath)) {
            error_log("Ditemukan: " . $filePath);
            return [
                'file' => $filePath,
                'class' => pathinfo($fileName, PATHINFO_FILENAME)
            ];
        }
    }

    return null;
}

// Mencari file controller
$controllerInfo = findControllerFile($controller);

if ($controllerInfo) {
    require_once $controllerInfo['file'];
    $controllerClass = $controllerInfo['class'];

    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass();

        // Memeriksa apakah method (action) ada di dalam controller
        if (method_exists($controllerInstance, $action)) {
            $controllerInstance->$action();
        } else {
            // Jika action tidak ditemukan, gunakan action default
            if (method_exists($controllerInstance, 'index')) {
                $controllerInstance->index();
            } else {
                echo "Action not found";
            }
        }
    } else {
        echo "Controller class not found: " . $controllerClass;
    }
} else {
    // Jika controller tidak ditemukan, tampilkan halaman 404
    echo "Controller not found: " . htmlspecialchars($controller);
}
?>