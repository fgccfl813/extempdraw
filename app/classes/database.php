<?php

class Database
{
    private static $instance = null;
    private static $dbPass = null;
    private $connection;

    private function __construct()
    {
        if (self::$dbPass == null) {
            self::$dbPass = trim(file_get_contents(APP . '/.private/db'));
        }
        $this->connection = new PDO('mysql:host=db.fgccfl.net;dbname=extempprep4',
            'extprep2021', self::$dbPass);
    }

    private function __clone()
    {}

    public static function connect(): Database
    {
        return self::$instance = self::$instance ?? new Database();
    }

    public function open(): PDO
    {
        return $this->connection;
    }

}