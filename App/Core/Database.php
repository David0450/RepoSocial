<?php


class Database extends PDO {
    private $dbh;
    private static $instance;

    private function __construct() {
        $dsn = "mysql:host=".Config::DB_HOST.";dbname=".Config::DB_NAME;
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->dbh = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD, $options);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->dbh;
    }
}