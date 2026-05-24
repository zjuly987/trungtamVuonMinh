<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

define("ROOT", dirname(__DIR__));

require_once ROOT . "/core/App.php";
require_once ROOT . "/core/Controller.php";
require_once ROOT . "/core/Model.php";

$app = new App();