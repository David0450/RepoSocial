<?php


class Config {
    const DB_HOST = 'localhost';
    const DB_PORT = '3306';
    const DB_NAME = 'PFC';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const DB_CHARSET = 'utf8';
    const GITHUB_CLIENT_ID = ' Ov23lijzgiVY3WIWZerl';
    const GITHUB_CLIENT_SECRET = 'ea71066559278001f21d82cd9b98c13d4f3d1195';
    const GITHUB_REDIRECT_URI = 'http://localhost/PFC/github/callback';

    public static function get($key) {
        if (defined("self::" . strtoupper($key))) {
            return constant("self::" . strtoupper($key));
        }
        throw new Exception("La clave de configuración '$key' no existe.");
    }
}