<?php
require_once 'Core/db.php';

class Libro
{
    private $db;

    public function __construct()
    {
        $this->db = Db::getInstance()->getConnection();
        if ($this->db === null) {
            throw new Exception("No se puede conectar con la base de datos");
        }
    }

    /**
     * Devuelve la lista de todos los libros
     * @return array|false
     */
    public function getAllLibros(): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_libro");
            $stmt->execute();
            $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$libros) {
                return false;
            }

            return $libros;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    public function getLastLibroAdded(): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_libro ORDER BY LIB_DATE_ADDED LIMIT 5");
            $stmt->execute();
            $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$libros) {
                return false;
            }

            return $libros;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener un libro por su ID.
     * @param int $id
     * @return array|false
     */
    public function getLibroById(int $id): array|false
    {
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("El ID del libro debe ser un número.");
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_cliente WHERE cli_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $libro = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$libro) {
                return false;
            }
            return $libro;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Agrega un nuevo libro.
     * @param string $nombre
     * @param string $apellido
     * @param string $telefono
     * @return bool
     */
    public function createLibro($libro): bool
    {

        if (empty($libro['titulo']) && empty($libro['autor']) && empty($libro['isbn']) && empty($libro['edicion'])) {
            throw new Exception("El titulo, autor, ISBN y la edición no se deben quedar vaciós.");
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO tbl_libro (LIB_TITULO, LIB_SIPNOPSIS, LIB_AUTOR, LIB_ISBN, LIB_EDICION, LIB_COSTO_MORA_DIA)
                VALUES (:titulo, :sipnosis, :autor, :isbn, :edicion, :costo_mora)
            ");
            $stmt->bindParam(':titulo', $libro['titulo'], PDO::PARAM_STR);
            $stmt->bindParam(':sipnosis', $libro['sipnosis'], PDO::PARAM_STR);
            $stmt->bindParam(':autor', $libro['autor'], PDO::PARAM_STR);
            $stmt->bindParam(':isbn', $libro['isbn'], PDO::PARAM_STR_CHAR);
            $stmt->bindParam(':edicion', $libro['edicion'], PDO::PARAM_STR);
            $stmt->bindParam(':costo_mora', $libro['costo_mora']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar el libro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza un libro mediante su ID.
     * @param mixed $id
     * @param mixed $libro
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function updateLibroById($id, $libro)
    {
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("El ID del libro debe ser un número.");
        }

        $libro = [
            'LIB_TITULO' => $libro['titulo'],
            'LIB_SIPNOSIS' => $libro['sipnosis'],
            'LIB_AUTOR' => $libro['autor'],
            'LIB_ISBN' => $libro['isbn'],
            'LIB_EDICION' => $libro['edicion'],
            'LIB_COSTO_MORA_DIA' => $libro['costo_mora'],
        ];

        $fields = [];
        foreach ($libro as $column => $value) {
            $fields[] = "$column = :$column";
        }
        $fieldsList = implode(', ', $fields);

        try {
            $stmt = $this->db->prepare("UPDATE tbl_libro SET {$fieldsList} WHERE LIB_ID = :id");

            foreach ($libro as $column => $value) {
                $stmt->bindValue(":$column", $value);
            }
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error al acutalizar libro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un libro de la lista mediante su ID.
     * @return array|false
     */
    public function deleteLibroById($id): array|false
    {
        if (empty($id)) {
            throw new Exception("Debe Ingresar el ID del libro para poder eliminarlo.");
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM Ttbl_libro WHERE LIB_ID = :id");
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar el libro: " . $e->getMessage());
            return false;
        }
    }
}
