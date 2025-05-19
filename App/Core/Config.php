<?php


class Config {
    const DB_HOST = 'localhost';
    const DB_PORT = '3306';
    const DB_NAME = 'PFC';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const DB_CHARSET = 'utf8';
    const GITHUB_CLIENT_ID = 'Ov23lijzgiVY3WIWZerl';
    const GITHUB_CLIENT_SECRET = '37676bfac8d1b856c112fb634289ac7cfc4307f7';
    const PATH = 'http://localhost/programacion/PFC/';

    public static function get($key) {
        if (defined("self::" . strtoupper($key))) {
            return constant("self::" . strtoupper($key));
        }
        throw new Exception("La clave de configuración '$key' no existe.");
    }
}