<?php

require_once ROOT . "/config/database.php";

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }
}