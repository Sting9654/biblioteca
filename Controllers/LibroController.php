<?php
require_once 'Models/libro.php';

class LibroController
{

    private $libro;
    public function __construct(){
        $this->libro = new Libro();
    }

    public function index()
    {
        $libros = $this->libro->getLastLibroAdded();
        require 'Views/v_books_list.php';
    }
}
