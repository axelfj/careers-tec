<?php
class Conectar {
    public static function conexion() {
        $dsn = getenv('CAREERS_DB_DSN');
        $user = getenv('CAREERS_DB_USER');
        $password = getenv('CAREERS_DB_PASSWORD');
        if (!$dsn || !$user || !$password) throw new RuntimeException('Configure CAREERS_DB_DSN, CAREERS_DB_USER and CAREERS_DB_PASSWORD');
        return new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]);
    }
}
