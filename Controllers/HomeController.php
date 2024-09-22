<?php
require 'Models/biblioteca.php';
require_once 'Models/libro.php';

class HomeController
{
    private $libro;
    private $empresa;

    public function __construct()
    {
        $this->libro = new Libro();
        $this->empresa = new Biblioteca();
    }

    public function index()
    {
        $empresa = $this->empresa->getEmpresa();
        $libros = $this->libro->getLastLibroAdded();

        require 'Views/c_nav_bar.php';

        require 'Views/v_home.php';
    }
}
