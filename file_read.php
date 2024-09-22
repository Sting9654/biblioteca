<?php
function loadEnvFile($path)
{
    if (!file_exists($path)) {
        throw new Exception("El archivo no existe.");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];

    foreach ($lines as $line) {
        $line = trim($line);

        if (strpos($line, '#') === 0 || empty($line)) {
            continue;
        }

        if (strpos($line, '#') !== false) {
            $line = strstr($line, '#', true);
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (!empty($key)) {
            $env[$key] = $value;
        }
    }

    return $env;
}

// Devuelve los parámetros necesarios en un arreglo para establecer una conexión con la base de datos
function getDbParams(): array
{
    $global = loadEnvFile('config.env');
    $requiredKeys = ['DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_NAME', 'DB_CHARSET'];

    foreach ($requiredKeys as $key) {
        if (!isset($global[$key])) {
            throw new Exception("Falta la clave $key en el archivo de configuración.");
        }
    }

    return [
        'DB_HOST' => $global['DB_HOST'],
        'DB_USERNAME' => $global['DB_USERNAME'],
        'DB_PASSWORD' => $global['DB_PASSWORD'],
        'DB_NAME' => $global['DB_NAME'],
        'DB_CHARSET' => $global['DB_CHARSET']
    ];
}

