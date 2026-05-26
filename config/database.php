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
    $this->pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);
        } catch (Exception $e) {
            die("DB Error: " . $e->getMessage());
        }
    }
}