<?php

namespace App\Core;

use PDO;

class PDOManager
{
    private static $instance = null;
    private static $connection = null;
    private $dotenv = null;
    
    private function __construct()
    {
        $this->dotenv = \Dotenv\Dotenv::create(__DIR__);
        $this->dotenv->load();

        /* Connect to a MySQL database using driver invocation */
        $dsn = "mysql:dbname=".$this->db_name().";host=".$this->db_host();

        try {
            self::$connection = new PDO($dsn, $this->db_user(), $this->db_password());
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new PDOManager();
        }
    
        return self::$instance;
    }

    public function getConnection()
    {
        return self::$connection;
    }

    private function db_name()
    {
        return getenv('DB_NAME');
    }

    private function db_host()
    {
        return getenv('DB_HOST');
    }

    private function db_user()
    {
        return getenv('DB_USER');
    }

    private function db_password()
    {
        return getenv('DB_PASSWORD');
    }
}