<?php
    namespace Models;

    class Streets {

        // Объявляем свойства модели
        public $id;
        public $name;
        public $city_id;

        public function __construct($id, $name, $city_id) {
            $this -> id          = $id;
            $this -> phone       = $name;
            $this -> city_id     = $city_id;
        }

        public static function getNamesByCityId($id) {

            $dbh = \Database::getInstance();
            $sth = $dbh -> prepare("SELECT id, street_name FROM streets WHERE city_id = :id");
            $sth -> bindParam(':id', $id);
            $sth -> execute();

            $itemData = $sth -> fetchAll(\PDO::FETCH_NUM);
            return $itemData;
        }

        public static function getById($id) {

            $dbh = \Database::getInstance();
            $sth = $dbh -> prepare("SELECT * FROM streets WHERE id = :id");
            $sth -> bindParam(':id', $id);
            $sth -> execute();

            $itemData = $sth -> fetch(\PDO::FETCH_NUM);
            return $itemData;
        }
    }
?>