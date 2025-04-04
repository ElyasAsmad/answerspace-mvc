<?php
/**
 * Summary of Database:
 * A Database Wrapper Class
 *  
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db_name = DB_NAME;

    private $db;
    private $stmt;

    public function __construct() {
        try{
            $dsn = "mysql:host=". $this->host .";dbname=". $this->db_name;
            $option = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            $this->db = new PDO($dsn, $this->user, $this->pass, $option);
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    public function query($query){  
        $this->stmt = $this-> db ->prepare($query);
    }

    public function prepare($query){
        $this->stmt = $this->db->prepare($query);
    }

    public function bind($param, $value, $type=null){
        if (is_null($type)){
            switch( true ) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        // to avoid sql injection
        $this->stmt->bindValue($param, $value, $type);
        // var_dump($value);
    }

    public function execute(){
        // var_dump($this->stmt);
        // echo "<pre>";
        $this->stmt->execute();
        // echo "</pre>";
    }

    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }
}