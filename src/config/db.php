<?php
// Database configuration and connection

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $databaseUrl = getenv('DATABASE_URL');
        
        if (!$databaseUrl) {
            throw new Exception('DATABASE_URL environment variable is not set');
        }
        
        // Parse PostgreSQL connection URL
        $dbParts = parse_url($databaseUrl);
        
        $host = $dbParts['host'] ?? 'localhost';
        $port = $dbParts['port'] ?? 5432;
        $dbname = ltrim($dbParts['path'] ?? '/postgres', '/');
        $user = $dbParts['user'] ?? '';
        $password = $dbParts['pass'] ?? '';
        
        // Coolify internal network doesn't require SSL
        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        
        try {
            $this->pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true
            ]);
        } catch (PDOException $e) {
            if (getenv('DEBUG_MODE') === 'true') {
                throw new Exception('Database connection failed: ' . $e->getMessage());
            } else {
                error_log('Database connection failed: ' . $e->getMessage());
                throw new Exception('Database connection failed');
            }
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
            if (getenv('DEBUG_MODE') === 'true') {
                throw new Exception('Query failed: ' . $e->getMessage());
            } else {
                error_log('Query failed: ' . $e->getMessage() . ' | SQL: ' . $sql);
                throw new Exception('Database query failed');
            }
        }
    }
    
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
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
}

