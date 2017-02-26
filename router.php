<?php
    function call($controller, $action) {

        // Ищем и включаем контроллер
        require_once 'controllers/' . $controller . 'controller.php';

        // Создаем объект нужного контроллера
        switch($controller) {
            case 'index':
            $controller = new Controllers\IndexController();
            break;

            case 'phonebook':
            // Включаем модели для использования в контроллере
            require_once('models/contacts.php');
            require_once('models/cities.php');
            require_once('models/streets.php');
            $controller = new Controllers\PhonebookController();
            break;
        }

        // Вызываем запрашиваемый метод контроллера
        $controller -> { $action }();
    }

    // Список доступных для обращения контроллеров
    $controllers = array('index'     => ['index', 'error'],
                         'phonebook' => ['get', 'delete', 'add', 'find', 'update', 'getCityNames', 'getStreetNamesByCity', 'getDataForEditModal']);

    /* Проверяем, доступен ли контроллер.
    *  Обращения к недоступным контроллерам перенаправляем на index/error для обработки ошибки.
    */

    if (array_key_exists($controller, $controllers)) {
        if (in_array($action, $controllers[$controller])) {
            call($controller, $action);
        } else {
            call('index', 'error');
        }
    } else {
        call('index', 'error');
    }
?>