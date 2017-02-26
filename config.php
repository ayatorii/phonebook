<?php

    /* Singleton для работы с базой данных.
    *  Возвращает объект соединения с БД
    */
    class Database {
        private static $instance = NULL;

        private function __construct() {}

        private function __clone() {}

        public static function getInstance() {
            if (!isset(self::$instance)) {
                self::$instance = new PDO('mysql:host=localhost;dbname=phonebook;charset=utf8', 'root', '');
            }

        return self::$instance;
        }
    }
?>