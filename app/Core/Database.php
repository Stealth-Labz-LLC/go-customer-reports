<?php
/**
 * Simple PDO Database Connection
 */

namespace App\Core;

class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;

    private function __construct(string $host, string $dbname, string $user, string $pass)
    {
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $this->pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            $secrets = require dirname(__DIR__, 2) . '/config/secrets.php';
            self::$instance = new self(
                $secrets['db_host'] ?? 'localhost',
                $secrets['db_name'] ?? '',
                $secrets['db_user'] ?? '',
                $secrets['db_pass'] ?? ''
            );
        }
        return self::$instance;
    }

    public function pdo(): \PDO
    {
        return $this->pdo;
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchOne(string $sql, array $params = []): ?object
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Get a separate connection (e.g., for WordPress DB)
     */
    public static function connect(string $host, string $dbname, string $user, string $pass): self
    {
        return new self($host, $dbname, $user, $pass);
    }
}
