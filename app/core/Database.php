<?php

declare(strict_types=1);

namespace App\Core;

class Database
{
    /**
     * @var \PDO $db The PDO instance for database connection.
     */
    protected \PDO $db;

    /**
     * @var \PDOStatement|null $stmt The PDOStatement instance for executing SQL queries.
     */
    protected ?\PDOStatement $stmt = null;

    public function __construct()
    {
        $this->connect();
    }

    /**
     * Establish a database connection using PDO.
     *
     * @return void
     * @throws \Exception If the connection fails.
     */
    private function connect(): void
    {
        $DB_HOST = getenv("DB_HOST");
        $DB_NAME = getenv("DB_NAME");
        $DB_USER = getenv("DB_USER");
        $DB_PASS = getenv("DB_PASS");

        $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
        $options = [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];

        try {
            $this->db = new \PDO($dsn, $DB_USER, $DB_PASS, $options);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to connect to the database: " . $e->getMessage());
        }
    }

    /**
     * Prepare an SQL query.
     *
     * @param string $query The SQL query to prepare.
     * @return self
     */
    public function prepare(string $query): self
    {
        $this->stmt = $this->db->prepare($query);
        return $this;
    }

    /**
     * Bind a value to a parameter in the SQL statement.
     *
     * @param string|int $param The parameter identifier (e.g., ':id' or 1).
     * @param mixed $value The value to bind to the parameter.
     * @param int|null $type The PDO parameter type (optional).
     * @return self
     */
    public function bind(string|int $param, mixed $value, ?int $type = null): self
    {
        if (is_null($type)) {
            $type = match (true) {
                is_int($value) => \PDO::PARAM_INT,
                is_bool($value) => \PDO::PARAM_BOOL,
                is_null($value) => \PDO::PARAM_NULL,
                default => \PDO::PARAM_STR,
            };
        }
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    /**
     * Execute the prepared statement.
     *
     * @return bool True on success, false on failure.
     */
    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    /**
     * Fetch a single record from the result set.
     *
     * @return array|null The fetched record as an associative array or null if no result.
     */
    public function fetch(): ?array
    {
        return $this->stmt->fetch() ?: null;
    }

    /**
     * Fetch all records from the result set.
     *
     * @return array The fetched records as an array of associative arrays.
     */
    public function fetchAll(): array
    {
        return $this->stmt->fetchAll();
    }

    /**
     * Get the number of rows affected by the last operation.
     *
     * @return int The number of affected rows.
     */
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    /**
     * Begin a database transaction.
     *
     * @return self
     */
    public function beginTransaction(): self
    {
        $this->db->beginTransaction();
        return $this;
    }

    /**
     * Commit the current transaction.
     *
     * @return self
     */
    public function commit(): self
    {
        $this->db->commit();
        return $this;
    }

    /**
     * Roll back the current transaction.
     *
     * @return self
     */
    public function rollBack(): self
    {
        $this->db->rollBack();
        return $this;
    }

    /**
     * Close the current statement.
     *
     * @return void
     */
    public function close(): void
    {
        $this->stmt = null;
    }
}
