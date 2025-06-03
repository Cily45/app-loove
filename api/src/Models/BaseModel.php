<?php

namespace App\Models;

use LogicException;
use PDO;
use PDOStatement;


abstract class BaseModel {

    private PDO $connection;
    private ?PDOStatement $current_statement = null;

    public function __construct(string $dsn = '')
    {
        if(empty($dsn)) {
            $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8";
        }

        $this->connection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    protected function query(string $query) {
        $this->current_statement = $this->connection->prepare($query);

        return $this;
    }

    protected function execute(array $parameters=[]) {
        if($this->current_statement === null) {
            throw new LogicException("You should use function query before execute");
        }

        return $this->current_statement->execute($parameters);
    }

    protected function fetch(array $parameters = []) {
        if($this->current_statement === null) {
            throw new LogicException("You should use function query before execute");
        }

        foreach($parameters as $key => $value) {
            $this->current_statement->bindValue(":$key", $value);
        }

        $this->current_statement->execute();
        return $this->current_statement->fetch(PDO::FETCH_ASSOC);
    }

    protected function fetchAll(array $parameters = []) {
        if($this->current_statement === null) {
            throw new LogicException("You should use function query before execute");
        }

        foreach($parameters as $key => $value) {
            $this->current_statement->bindValue(":$key", $value);
        }

        $this->current_statement->execute();
        return $this->current_statement->fetchAll();
    }
    protected function fetchColumn(array $parameters = [], int $column = 0)
    {
        if($this->current_statement === null) {
            throw new LogicException("You should use function query before execute");
        }

        foreach($parameters as $key => $value) {
            $this->current_statement->bindValue(":$key", $value);
        }

        $this->current_statement->execute();
        return $this->current_statement->fetchColumn($column);
    }
    protected function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

}