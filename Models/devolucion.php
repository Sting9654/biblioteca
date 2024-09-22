<?php
require_once 'Core/db.php';
require_once 'Models/libro.php';
require_once 'Models/prestamo.php';


class Devolucion
{
    private $db;
    private $libro;
    private $prestamo;

    public function __construct()
    {
        $this->db = Db::getInstance()->getConnection();
        $this->libro = new Libro();
        $this->prestamo = new Prestamo();
        if ($this->db === null) {
            throw new Exception("No se puede conectar con la base de datos");
        }
    }

    /**
     * Obtiene todas las devoluciones.
     * @return array|false
     */
    public function getAllDevoluciones(): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_devoluciones");
            $stmt->execute();
            $devoluciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$devoluciones) {
                return false;
            }

            return $devoluciones;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene una devolución por su ID.
     * @param int $id
     * @return array|false
     */
    public function getDevolucionById($id): array|false
    {
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("El ID de la devolución debe ser un número.");
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_devolucion WHERE DEV_ID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $devolucion = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$devolucion) {
                return false;
            }

            return $devolucion;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Agrega un nuevo préstamo.
     * @param int $clienteId
     * @param int $libroId
     * @param string $fechaPrestamo
     * @param string $fechaDevolucion
     * @return bool
     */
    public function createDevolucion($devolucion)
    {
        if (empty($devolucion["prestamo"]) && empty($devolucion["fecha_retorno"])) {
            throw new Exception("Para insertar una devolución no debe estar el prestamo y la fecha retorno vacía.");
        }

        $prestamo = $this->prestamo->getPrestamoById($devolucion['prestamo']);
        $libro = $this->libro->getLibroById($prestamo('PMO_LIBRO_ID'));

        if (date_diff($prestamo['PMO_FECHA_DEVOLUCION'], new DateTime($devolucion['fecha_devolucion']))->days > 1) {
         $devolucion['mora_total'] = $libro['LIB_COSTO_MORA_DIA'] * date_diff($prestamo['PMO_FECHA_DEVOLUCION'], new DateTime($devolucion['fecha_devolucion']))->days;
        }
        else{
            $devolucion['mora_total'] = 0.00;
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO tbl_devolución (DEV_PRESTAMO_ID, DEV_FECHA_RETORNO, DEV_MONTO_MORA)
                VALUES (:prestamo, :fecha_retorno, :mora_total)
            ");
            $stmt->bindParam(':prestamo', $devolucion['prestamo'], PDO::PARAM_INT);
            $stmt->bindParam(':fecha_retorno', $devolucion['fecha_retorno'], PDO::PARAM_STR);
            $stmt->bindParam(':mora_total', $devolucion['fecha_salida']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar la devolución: " . $e->getMessage());
            return false;
        }
    }

}
