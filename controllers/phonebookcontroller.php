<?php
    namespace Controllers;

    class PhonebookController {

        public function index() {

            require_once 'views/phonebook/index.php';
        }

        public function get() {

            $posts = \Models\Contacts::allDatatables();

            echo json_encode($posts); 
        }

        public function delete() {

            if (!isset($_POST['item_id'])) {
                return call('index', 'error');
            }

            $post_id = $_POST['item_id'];
            $result = \Models\Contacts::delete($post_id);

            echo json_encode($result);
        }

        public function add() {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $phone          = $_POST['phone'];
                $credentials    = $_POST['credentials'];
                $birthday       = $_POST['birthday'];
                $city           = $_POST['city'];
                $street         = $_POST['street'];

                $result = \Models\Contacts::add($phone, $credentials, $birthday, $city, $street);

                echo json_encode($result);
            }
        }

        public function find() {

            if (!isset($_POST['item_id'])) {
                return call('index', 'error');
            }

            $item_id = $_POST['item_id'];
            $result = \Models\Contacts::find($item_id);

            echo json_encode($result);
        }

        public function update() {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $item_id        = $_POST['item_id'];
                $phone          = $_POST['editPhone'];
                $credentials    = $_POST['editCredentials'];
                $birthday       = $_POST['editBirthday'];
                $city           = $_POST['editCity'];
                $street         = $_POST['editStreet'];

                $result = \Models\Contacts::update($item_id, $phone, $credentials, $birthday, $city, $street);

                echo json_encode($result);
            }
        }

        public function getCityNames() {

            $city_names = \Models\Cities::getNames();

            echo json_encode($city_names); 
        }

        public function getStreetNamesByCity() {

            if (!isset($_POST['item_id'])) {
                return call('index', 'error');
            }

            $street_id = $_POST['item_id'];
            $result = \Models\Streets::getNamesByCityId($street_id);

            echo json_encode($result);
        }

        public function getDataForEditModal() {

            if (!isset($_POST['item_id'])) {
                return call('index', 'error');
            }

            $item_id      = $_POST['item_id'];
            $contact      = \Models\Contacts::find($item_id);
            $city_names   = \Models\Cities::getNames();
            $street_names = \Models\Streets::getNamesByCityId($contact["city_id"]);

            $modal_data = [];
            $modal_data["cities"]  = $city_names;
            $modal_data["streets"] = $street_names;
            $modal_data["contact"] = $contact;

            echo json_encode($modal_data);
        }

    }
?>