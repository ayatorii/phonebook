<?php
    namespace Models;

    class Contacts {

        // Объявляем свойства модели
        public $id;
        public $phone;
        public $credentials;
        public $birthday;

        public function __construct($id, $phone, $credentials, $birthday) {
            $this -> id          = $id;
            $this -> phone       = $phone;
            $this -> credentials = $credentials;
            $this -> birthday    = $birthday;
        }

        public static function all() {

            $list = [];
            $dbh = \Database::getInstance();
            $req = $dbh->query('SELECT * FROM phonebook');

            // 
            foreach($req->fetchAll() as $contact) {
                $list[] = new Contacts($contact['ID'], $contact['Phone'], $contact['Credentials'], $contact['Birthday']);
            }

            return $list;
        }

        public static function allDatatables() {

            // Отдаем данные для плагина DataTables
            $dbh = \Database::getInstance();
            $sth = $dbh -> query("SELECT phonebook.ID, phonebook.Phone, phonebook.Credentials, phonebook.Birthday, cities.city_name, streets.street_name
                                    FROM phonebook
                                    JOIN cities
                                    ON phonebook.city_id=cities.id
                                    JOIN streets
                                    ON phonebook.street_id=streets.id;");

            $arr = [];
            $obj = new \stdClass();

            $arr = $sth -> fetchAll(\PDO::FETCH_NUM);

            // Добавляем кнопку действий во все строки таблицы
            foreach ($arr as $key => $value) {

                $actionsButton = '
                    <div class="btn-group">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Действие <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a type="button" data-toggle="modal" data-target="#editItemModal" onclick="editItem(' . $value[0] . ')"><span class="glyphicon glyphicon-edit"></span> Редактировать</a></li>
                        <li><a type="button" data-toggle="modal" data-target="#removeItemModal" onclick="removeItem(' . $value[0] . ')"><span class="glyphicon glyphicon-trash"></span> Удалить</a></li>
                      </ul>
                    </div>';

                array_push($value, $actionsButton);

                // Удаляем ID из данных и реиндексируем массив

                unset($value[0]);
                $value = array_values($value);
                $arr[$key] = $value;
            }

            $obj -> data = $arr;

            $dbh = NULL;
            return $obj;
        }

        public static function delete($id) {

            $dbh = \Database::getInstance();

            $output = array(
                'success' => false,
                'messages'=> array()
            );

            $sth = $dbh -> prepare("DELETE FROM phonebook WHERE id = :id");
            $sth -> bindParam(':id', $id);

            $result = $sth -> execute();

            if ($result === true) {
                    $output['success']  = true;
                    $output['messages'] = "Запись была успешно удалена!";
            }   else {
                    $output['messages'] = "Ошибка удаления записи!";
            }

            $dbh = NULL;
            return $output;
        }

        public static function add($phone, $credentials, $birthday, $city, $street) {

            $dbh = \Database::getInstance();

            $output = array(
                'success' => false,
                'messages' => array()
            );

            $city        = intval($city);
            $street      = intval($street);
            $phone       = htmlspecialchars($phone);
            $credentials = htmlspecialchars($credentials);

            if (empty(Cities::getById($city))) {
                $output['messages'] = "Город не найден в базе";
                return $output;
            }

            $street_record = Streets::getById($street);

            if ($street_record[2] != $city) {
                $output['messages'] = "В выбранном городе нет такой улицы.";
                return $output;
            }

            if ($phone == '' || $credentials == '' || $birthday == '' || $city == 0 || $street == 0) {

                $output['messages'] = "Одно из полей не заполнено или содержит недопустимое значение!";

            } else {

                $birthday_parsed = date_parse($birthday);

                if (!$birthday_parsed || !checkdate($birthday_parsed["month"], $birthday_parsed["day"], $birthday_parsed["year"])) {

                    $output['messages'] = "Некорректно заполнена дата рождения!";

                } else {

                    $birthday_timestamp = strtotime($birthday);
                    $birthday = date('Y-m-d', $birthday_timestamp);
            
                    $sth = $dbh -> prepare("INSERT INTO phonebook (phone, credentials, birthday, city_id, street_id)
                        VALUES (:phone, :credentials, :birthday, :city_id, :street_id)");

                    $sth -> bindParam(':phone', $phone);
                    $sth -> bindParam(':credentials', $credentials);
                    $sth -> bindParam(':birthday', $birthday);
                    $sth -> bindParam(':city_id', $city);
                    $sth -> bindParam(':street_id', $street);

                    $result = $sth -> execute();

                    if ($result === true) {

                        $output['success']  = true;
                        $output['messages'] = "Запись была успешно добавлена!";

                    }   else {
                        
                        $output['messages'] = "Ошибка добавления записи!";
                    }

                }
            }

            $dbh = NULL;
            return $output;
        }

        public static function find($id) {

            $dbh = \Database::getInstance();
            
            $sth = $dbh -> prepare("SELECT * FROM phonebook WHERE id = :id");
            $sth -> bindParam(':id', $id);
            $sth -> execute();
            $itemData = $sth -> fetch(\PDO::FETCH_ASSOC);

            return $itemData;
        }

        public static function update($item_id, $phone, $credentials, $birthday, $city, $street) {

            $dbh = \Database::getInstance();

            $output = array(
            'success' => false,
            'messages' => array()
            );

            $city        = intval($city);
            $street      = intval($street);
            $phone       = htmlspecialchars($phone);
            $credentials = htmlspecialchars($credentials);

            if (empty(Cities::getById($city))) {
                $output['messages'] = "Город не найден в базе";
                return $output;
            }

            $street_record = Streets::getById($street);

            if ($street_record[2] != $city) {
                $output['messages'] = "В выбранном городе нет такой улицы.";
                return $output;
            }

            if ($item_id == '' || $phone == '' || $credentials == '' || $birthday == '' || $city == 0 || $street == 0) {

                $output['messages'] = "Одно из полей не заполнено или содержит недопустимое значение!";

            } else {

                $birthday_parsed = date_parse($birthday);

                if (!$birthday_parsed || !checkdate($birthday_parsed["month"], $birthday_parsed["day"], $birthday_parsed["year"])) {

                    $output['messages'] = "Некорректно заполнена дата рождения!";

                } else {

                    $birthday_timestamp = strtotime($birthday);
                    $birthday = date('Y-m-d', $birthday_timestamp);

                    $sth = $dbh -> prepare("UPDATE phonebook SET phone = :phone, credentials = :credentials, birthday = :birthday, city_id = :city_id, street_id = :street_id
                        WHERE id = :item_id");

                    $sth -> bindParam(':phone', $phone);
                    $sth -> bindParam(':credentials', $credentials);
                    $sth -> bindParam(':birthday', $birthday);
                    $sth -> bindParam(':item_id', $item_id);
                    $sth -> bindParam(':city_id', $city);
                    $sth -> bindParam(':street_id', $street);

                    $result = $sth -> execute();

                    if ($result === true) {

                        $output['success']  = true;
                        $output['messages'] = "Запись была успешно отредактирована!";

                    } else {

                        $output['messages'] = "Ошибка редактирования записи!";
                    }

                }
            }

            $dbh = NULL;
            return $output;
        }
    }
?>