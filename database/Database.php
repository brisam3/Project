<?php

class Database {
    private $host;
    private $db;
    private $user;
    private $pass;
    private $charset = 'utf8mb4';
    private $pdo;
    private $error;

    public function __construct() {
        // Verificar si está en local o producción
        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            // Credenciales para local
            $this->host = 'localhost';
            $this->db = 'wolchuk';
            $this->user = 'root';
            $this->pass = '';
        } else {
            // Credenciales para producción
            $this->host = 'localhost';
            $this->db = 'u277628716_wolbd';
            $this->user = 'u277628716_wolbd';
            $this->pass = 'Cf4b1a7123';
        }

        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo "Error en la conexión: " . $this->error;
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}


?>
