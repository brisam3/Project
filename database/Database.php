<?php

class Database {
    private $host = 'localhost';
    private $db = 'wolchuk';
    private $user = 'root'; // Cambia con tu usuario de base de datos
    private $pass = ''; // Cambia con tu contraseña de base de datos
    private $charset = 'utf8mb4';
    private $pdo;
    private $error;

    public function __construct() {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            echo "Conexión exitosa a la base de datos.";
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
