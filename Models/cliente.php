<?php
require_once 'Core/db.php';

class Cliente
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
     * Devuelve la lista de todos los clientes
     * @return array|false
     */
    public function getAllClientes(): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_cliente");
            $stmt->execute();
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$clientes) {
                return false;
            }

            return $clientes;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener un cliente por su ID.
     * @param int $id
     * @return array|false
     */
    public function getClienteById(int $id): array|false
    {
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("El ID del cliente debe ser un número.");
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_cliente WHERE cli_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cliente) {
                return false;
            }
            return $cliente;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Agrega un nuevo cliente.
     * @param string $nombre
     * @param string $apellido
     * @param string $telefono
     * @return bool
     */
    public function createCliente($cliente): bool
    {

        if (empty($cliente['nombre']) && empty($cliente['apellido']) && empty($cliente['telefono'])) {
            throw new Exception("El nombre, apellido y número de contacto del cliente debe quedar en blanco.");
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO tbl_cliente (CLI_NOMBRE, CLI_APELLIDO, CLI_TELEFONO)
                VALUES (:nombre, :apellido, :telefono)
            ");
            $stmt->bindParam(':nombre', $cliente['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':apellido', $cliente['apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $cliente['telefono'], PDO::PARAM_STR_CHAR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar el cliente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza un cliente mediante su ID.
     * @param mixed $id
     * @param mixed $cliente
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function updateClienteById($id, $cliente)
    {
        if (!is_numeric($id)) {
            throw new InvalidArgumentException("El ID del cliente debe ser un número.");
        }

        $cliente = [
            'CLI_NOMBRE' => $cliente['nombre'],
            'CLI_APELLIDO' => $cliente['apellido'],
            'CLI_TELEFONO' => $cliente['telefono'],
        ];

        $fields = [];
        foreach ($cliente as $column => $value) {
            $fields[] = "$column = :$column";
        }
        $fieldsList = implode(', ', $fields);

        try {
            $stmt = $this->db->prepare("UPDATE tbl_cliente SET {$fieldsList} WHERE CLI_ID = :id");

            foreach ($cliente as $column => $value) {
                $stmt->bindValue(":$column", $value);
            }
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error al acutalizar cliente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un cliente de la lista mediante su ID.
     * @return array|false
     */
    public function deleteClienteById($id): array|false
    {
        if (empty($id)) {
            throw new Exception("Debe Ingresar el ID para poder eliminar el cliente.");
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM tbl_cliente WHERE CLI_ID = :id");
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar el cliente: " . $e->getMessage());
            return false;
        }
    }
}
