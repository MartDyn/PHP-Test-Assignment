<?php
class DatabaseConnection
{
    private PDO $PDO;
    private array $queryResults;

    public function __construct($dsn) {
        try {
            $this->PDO = new PDO($dsn);
            $this->PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Create array to store prepare or 'prime' data for use
            $this->queryResults = array('user_data' => array(), 'sector_data' => array());
        } catch(PDOException $e) {
            exit(print($e->getMessage()));
        }
    }

    public function getPDO(): PDO {
        return $this->PDO;
    }

    // Return primed data
    public function getData(string $dataType): array {
        return $this->queryResults[$dataType];
    }

    // Execute prepared SQL query and return results
    public function query(string $statement, string $dataType, array $variables = null): array {
        $PDOStatement = $this->PDO->prepare($statement);
        $PDOStatement->execute($variables);

        return $this->queryResults[$dataType] = $PDOStatement->fetchAll();
    }

    // Execute prepared SQL statement
    public function execute(string $statement, array $variables = null): PDOStatement {
        $PDOStatement = $this->PDO->prepare($statement);
        $PDOStatement->execute($variables);

        return $PDOStatement;
    }
}
