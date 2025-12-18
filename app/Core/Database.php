<?php

class Database {
    private static $instance = null;
    private $pdo = null;
    private $config;
    
    private function __construct() {
        $this->config = require __DIR__ . '/../../config/database.php';
        $this->connect();
    }
    
    private function connect() {
        try {
            $driver = $this->config['driver'] ?? 'mysql';

            if ($driver === 'sqlite') {
                $dbPath = $this->config['sqlite']['database'];
                
                // Ensure directory and file exist
                $dir = dirname($dbPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                
                if (!file_exists($dbPath)) {
                    touch($dbPath);
                }

                $dsn = "sqlite:" . $dbPath;
                $this->pdo = new PDO($dsn);
                
                // Enable foreign keys for SQLite
                $this->pdo->exec("PRAGMA foreign_keys = ON;");
            } else {
                $dsn = "mysql:host={$this->config['host']};dbname={$this->config['database']};charset={$this->config['charset']}";
                $this->pdo = new PDO($dsn, $this->config['username'], $this->config['password'], $this->config['options']);
            }
            
            // Set common attributes
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function execute($sql, $params = []) {
        return $this->query($sql, $params);
    }
    
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    public function commit() {
        return $this->pdo->commit();
    }
    
    public function rollBack() {
        return $this->pdo->rollBack();
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization (PHP 8+ compatible)
    public function __wakeup(): void {
        throw new \Exception("Cannot unserialize singleton");
    }
    
    public function __serialize(): array {
        throw new \Exception("Cannot serialize singleton");
    }
    
    public function __unserialize(array $data): void {
        throw new \Exception("Cannot unserialize singleton");
    }
}
