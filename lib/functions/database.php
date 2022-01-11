<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/lib/config/db_config.php';

class DB_PDO extends Dbconfig {

    public $connectionString;
    public $dataSet;
    private $sqlQuery;

    protected $databaseName;
    protected $hostName;
    protected $userName;
    protected $passCode;

    function __construct()
    {
      $this->connectionString = NULL;
      $this->sqlQuery = NULL;
      $this->dataSet = NULL;

      $dbPara = new Dbconfig();
      $this->databaseName = $dbPara->dbName;
      $this->hostName = $dbPara->serverName;
      $this->userName = $dbPara->userName;
      $this->passCode = $dbPara ->passCode;
      $dbPara = NULL;

      try {
      	$this->connectionString = new PDO("mysql:host={$this->serverName};dbname={$this->databaseName};charset=utf8",$this->userName, $this->passCode, [
      		PDO::ATTR_EMULATE_PREPARES => false,
      		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      	]);
      } catch (\PDOException $e) {
      	throw new \PDOException($e->getMessage(), (int)$e->getCode());
      }
      return $this->connectionString;
    }

    public function dbDisconnect()
    {
        $this->connectionString = NULL;
        $this->sqlQuery = NULL;
        $this->dataSet = NULL;
        $this->databaseName = NULL;
        $this->hostName = NULL;
        $this->userName = NULL;
        $this->passCode = NULL;
    }

    public function selectAll($tableName)
    {
        $this->sqlQuery = 'SELECT * FROM '.$this->databaseName.'.'.$tableName;
        $this->dataSet = $this->connectionString->query($this->sqlQuery);
        return $this->dataSet;
    }

    public function selectWhere($tableName,$rowName,$operator,$value,$valueType)
    {
        $this->sqlQuery = 'SELECT * FROM '.$tableName.' WHERE '.$rowName.' '.$operator.' ';
        if($valueType == 'int') { $this->sqlQuery .= $value; } else if($valueType == 'char') { $this->sqlQuery .= "'".$value."'"; }
        $this->dataSet = $this->connectionString->query($this->sqlQuery);
        return $this->dataSet;
    }

    public function insertInto($tableName,$values)
    {
        $i = NULL;

        $this->sqlQuery = 'INSERT INTO '.$tableName.' VALUES (';
        $i = 0;
        $x = 0;
        $count = count($values);
        while($i < $count) {
            if($values[$i]["type"] == "char") {
                $this->sqlQuery .= '"';
                $this->sqlQuery .= $values[$i]["val"];
                $this->sqlQuery .= '"';
                if($i < $count - 1) {
                  $this->sqlQuery .= ',';
                }
            }
            else if($values[$i]["type"] == 'int') {
                $this->sqlQuery .= $values[$i]["val"];
            }
            $i++;
        }
        $this->sqlQuery .= ')';
        $this->dataSet = $this->connectionString->query($this->sqlQuery);
        return $this->sqlQuery;
    }

    public function selectFreeRun($query) {
        $this->sqlQuery = $query;
        $this->dataSet = $this->connectionString->query($this->sqlQuery);
        return $this->dataSet;
    }

    public function freeRun($query)
    {
        $this->sqlQuery = $query;
        $this->dataSet = $this->connectionString->query($this->sqlQuery);
        return $this->dataSet;
    }

    function __destruct()
    {
      $this->connectionString = NULL;
      $this->sqlQuery = NULL;
      $this->dataSet = NULL;
      $this->databaseName = NULL;
      $this->hostName = NULL;
      $this->userName = NULL;
      $this->passCode = NULL;
    }
}
?>
