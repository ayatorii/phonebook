<?php
    namespace Models;

    class Cities {

        // Объявляем свойства модели
        public $id;
        public $name;

        public function __construct($id, $name) {
            $this -> id          = $id;
            $this -> phone       = $name;
        }

        public static function getNames() {

            $dbh = \Database::getInstance();
            $sth = $dbh -> query("SELECT * FROM cities");

            $itemData = $sth -> fetchAll(\PDO::FETCH_NUM);

            return $itemData;

        }

        public static function getById($id) {

            $dbh = \Database::getInstance();
            $sth = $dbh -> prepare("SELECT * FROM cities WHERE id = :id");
            $sth -> bindParam(':id', $id);
            $sth -> execute();

            $itemData = $sth -> fetch(\PDO::FETCH_NUM);
            return $itemData;
        }
    }
?>