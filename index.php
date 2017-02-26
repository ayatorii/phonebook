<?php

    // Включаем файл конфигурации
    require_once 'config.php';

    function __autoload($class_name) {

        $class_name = strtolower($class_name);
        require_once str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    }

    if (isset($_GET['controller']) && isset($_GET['action'])) {
        $controller = $_GET['controller'];
        $action     = $_GET['action'];
    } else {
        $controller = 'index';
        $action     = 'index';
    }

    /* PhonebookController возвращает данные через Ajax, кроме данных нам ничего не надо.
    *  Поэтому для этого контроллера используем пустой шаблон-заглушку, в остальных случаях загружаем шаблон проекта
    */
    if ($controller == 'phonebook') {
        require_once 'views/layout_json.php';
    } else {
        require_once 'views/layout.php';
    }
?>