<?php
	namespace Controllers;
	
    class IndexController {
        public function index() {
            require_once 'views/index/index.php';
        }

        public function error() {
            require_once 'views/index/error.php';
        }
    }
?>