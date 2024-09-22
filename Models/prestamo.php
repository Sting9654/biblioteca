<?php
require_once 'Core/db.php';

class Prestamo
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
     * Obtener todos los préstamos.
     * @return array|false
     */
    public function getAllPrestamos(): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_prestamo");
            $stmt->execute();
            $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$prestamos) {
                return false;
            }

            return $prestamos;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener un préstamo por su ID.
     * @param int $id
     * @return array|false
     */
    public function getPrestamoById($id): array|false
    {
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("El ID del prestamo debe ser un número.");
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_prestamo WHERE PMO_ID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$prestamo) {
                return false;
            }

            return $prestamo;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    public function getPrestamoByClientId($id): array|false
    {
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("El ID del prestamo debe ser un número.");
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_prestamo WHERE PMO_ID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$prestamo) {
                return false;
            }

            return $prestamo;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    private function validateEstatus($id){

    }

    /**
     * Agregar un nuevo préstamo.
     * @param int $clienteId
     * @param int $libroId
     * @param string $fechaPrestamo
     * @param string $fechaDevolucion
     * @return bool
     */
    public function createPrestamo($prestamo)
    {
        if (empty($prestamo["cliente"]) && empty($prestamo["libro"]) && empty($prestamo["fecha_devolucion"])) {
            throw new Exception("Para insertar un prestamo debe proporcionar un cliente, libro y la fecha en la que se debe retornar.");
        }

        $prestamo['fecha_salida'] = new DateTime(date("Y-m-d H:is"));

        if (date_diff($prestamo['fecha_salida'], new DateTime($prestamo['fecha_devolucion']))->invert == 1) {
            throw new Exception("la fecha de retorno no debe ser menor a la fecha actual.");
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO tbl_prestamo (PMO_CLIENTE_ID, PMO_LIBRO_ID, PMO_FECHA_SALIDA, PMO_FECHA_DEVOLUCION)
                VALUES (:cliente, :libro, :fecha_salida, :fecha_devolucion)
            ");
            $stmt->bindParam(':cliente', $prestamo['cliente'], PDO::PARAM_INT);
            $stmt->bindParam(':libro', $prestamo['libro'], PDO::PARAM_INT);
            $stmt->bindParam(':fecha_salida', $prestamo['fecha_salida'], PDO::PARAM_STR);
            $stmt->bindParam(':fecha_devolucion', $prestamo['fecha_devolucion'], PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar el préstamo: " . $e->getMessage());
            return false;
        }
    }

}
