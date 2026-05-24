<?php

class Database
{
    private $host = "localhost";
    private $dbname = "vuonminh";
    private $user = "root";
    private $pass = "";

    public function connect()
    {
        try {
            return new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8",
                $this->user,
                $this->pass
            );
        } catch (Exception $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
}