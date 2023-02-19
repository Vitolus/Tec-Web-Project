<?php

namespace Classes;

use Config\Database;
use Exception;

enum QueryOperation
{
  case INSERT;
  case SELECT;
  case UPDATE;
  case DELETE;
}

final class QueryBuilder
{
  private $database = Database::class;
  private readonly mixed $conn;
  private QueryOperation $operation;
  private string $where = '';
  private string $projects = '*';
  private string $insert = '';
  private string $update = '';

  /**
   * Opens a MySQLi connection
   * @param string $tableName the table name on which the CRUD operations will take place
   */
  public function __construct(private readonly string $tableName)
  {
    $this->conn = new \mysqli(
      $this->database::HOST,
      $this->database::USERNAME,
      $this->database::PASSWORD,
      $this->database::DATABASE
    );
    if ($this->conn->connect_error) {
      throw new Exception("Failed to connect to MySQL database {$this->conn->connect_error}");
    }
  }

  /**
   * Adds where condition(s). It is only supported with SELECT, UPDATE and DELETE.
   * @param string $sql the SQL of the WHERE condition(s). Values must be replaced with question marks (?). Example:
   *     `name = ? AND age > ?`
   */
  public function where(string $sql)
  {
    $this->where = "WHERE $sql";
    return $this;
  }

  /**
   * Select which fields to show in a SELECT operation. Defaults to `*`
   */
  public function project(array $fields = ['*'])
  {
    $this->projects = implode(', ', $fields);
    return $this;
  }

  /**
   * Inserts one row in the table
   * @param array $values Associative array where keys are the columns name and values are the actual value we want to
   *     store. Example: `['name' => 'Fra', 'age' => 25, 'hasCar' => true]`
   */
  public function insert(array $values)
  {
    $this->operation = QueryOperation::INSERT;

    $columns = implode(', ', array_keys($values));
    $questionMarks = implode(', ', array_fill(0, count($values), '?'));

    $this->insert = "($columns) VALUES ($questionMarks)";

    $stmt = $this->conn->prepare($this->toSql());
    $this->bindParams($values, $stmt);

    if ($stmt->execute()) {
      $this->conn->close();
      return true;
    } else {
      $this->conn->close();
      throw new Exception("Error in SQL {$this->toSql()} => {$this->conn->error}");
    }
  }

  /**
   * Selects one or multiple rows.
   * @param array $values if you are doing a WHERE, you must specify here an array of values. Example: if the WHERE
   *     condition is `name = ? AND age > ?`, here you will pass `['Fra', 25]`. If you do not have a WHERE condition,
   *     do not pass anything here
   */
  public function select(array $values = [])
  {
    $this->operation = QueryOperation::SELECT;
    $stmt = $this->conn->prepare($this->toSql());
    $this->bindParams($values, $stmt);
    $stmt->execute();
    return array_map(fn ($row) => (object)$row, $stmt->get_result()->fetch_all(MYSQLI_ASSOC));
  }

  /**
   * Updates one or multiple rows.
   * @param string $sql the SQL of the UPDATE value(s). Values must be replaced with question marks (?). Example: `name
   *     = ?, age > ?`
   * @param array $values new values to update the row(s) with. Also specify the values for the WHERE condition(s) if
   *     you have any. Example: if the WHERE condition is `name = ? AND age > ?` and you are updating the age column,
   *     here you will pass `[30, 'Fra', 25]`. Always remember to put first the updated values and the WHERE values at
   *     the end.
   */
  public function update(string $sql, array $values)
  {
    $this->operation = QueryOperation::UPDATE;
    $this->update = $sql;

    $stmt = $this->conn->prepare($this->toSql());
    $this->bindParams($values, $stmt);

    if ($stmt->execute()) {
      $this->conn->close();
      return true;
    } else {
      $this->conn->close();
      throw new Exception("Error in SQL {$this->toSql()} => {$this->conn->error}");
    }
  }

  /**
   * Deletes one or multiple rows.
   * @param array $values an array of values. Example: if the WHERE condition is `name = ? AND age > ?`, here you will
   *     pass `['Fra', 25]`
   */
  public function delete(array $values)
  {
    $this->operation = QueryOperation::DELETE;
    $stmt = $this->conn->prepare($this->toSql());
    $this->bindParams($values, $stmt);
    if ($stmt->execute()) {
      $this->conn->close();
      return true;
    } else {
      $this->conn->close();
      throw new Exception("Error in SQL {$this->toSql()} => {$this->conn->error}");
    }
  }

  /**
   * Prints the corresponding SQL statement
   */
  public function toSql()
  {
    return match ($this->operation) {
      QueryOperation::INSERT => "INSERT INTO {$this->tableName} {$this->insert}",
      QueryOperation::SELECT => "SELECT {$this->projects} FROM {$this->tableName} {$this->where}",
      QueryOperation::UPDATE => "UPDATE {$this->tableName} SET {$this->update} {$this->where}",
      QueryOperation::DELETE => "DELETE FROM {$this->tableName} {$this->where}",
    };
  }

  /**
   * Automatically reads input values, detects their types and binds them correctly to the prepared statement
   */
  private function bindParams(array $values, mixed &$statement)
  {
    if (empty($values)) return;

    $valueTypes = array_values(array_map(function ($value) {
      if (is_string($value)) {
        return 's';
      } else {
        if (is_double($value)) {
          return 'd';
        } else {
          if (is_int($value) || is_bool($value)) {
            return 'i';
          } else {
            throw new Exception("Unknown value type for '$value'");
          }
        }
      }
    }, $values));

    $valuesFormStatement = array_values($values);
    $statement->bind_param(implode('', $valueTypes), ...$valuesFormStatement);
  }
}
