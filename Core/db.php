<?php
include 'file_read.php';

class Db
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $dbParams = getDbParams();
        $dsn = "mysql:host={$dbParams['DB_HOST']};dbname={$dbParams['DB_NAME']};charset={$dbParams['DB_CHARSET']}";

        try {
            $this->conn = new PDO($dsn, $dbParams['DB_USERNAME'], $dbParams['DB_PASSWORD']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "Error de conexión.";
            exit();
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

    public function closeConnection()
    {
        $this->conn = null;
        self::$instance = null;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function query($sql, $params = [])
    {
        if ($this->conn === null) {
            error_log("No se puede preparar la consulta porque la conexión es null.");
            echo "Error de conexión.";
            return false;
        }

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            echo "Error en la consulta.";
            return false;
        }
    }

    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : false;
    }

    public function fetch($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }
}
