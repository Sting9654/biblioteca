<?php

class Biblioteca
{
    private $empresa;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->loadEmpresa(); // Carga los datos de la empresa al instanciar la clase
    }

    /**
     * Carga la empresa en la sesión si no está presente.
     */
    private function loadEmpresa()
    {
        if (!isset($_SESSION['empresa'])) {
            $_SESSION['empresa'] = $this->createEmpresa();
        }
    }

    /**
     * Retorna los datos de la empresa desde la sesión.
     * @return array Datos de la empresa
     */
    public function getEmpresa()
    {
        return $_SESSION['empresa'];
    }

    /**
     * Crea y retorna los datos de una empresa.
     * @return array Datos de la empresa
     */
    private function createEmpresa()
    {
        return [
            'name' => 'Biblioteca Nacional Dominicana',
            'logo' => 'Views/Assets/openart-image_XquzAkbh_1726983926415_raw.png'
        ];
    }
}
