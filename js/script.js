// Глобальная переменная для объекта таблицы

var phonebookTable;

$(document).ready( function () {
    phonebookTable = $("#table_id").DataTable({
      "ajax"        : '?controller=phonebook&action=get',
      "pageLength"  : 20,
      "lengthChange": false,
      "dom"         : 'flrtip',
      "columns"     : [
        { width: "15%" },
        { width: "25%" },
        { width: "15%" },
        { width: "15%" },
        { width: "15%" },
        { width: "15%" }
      ],
      "language"    : {
        "url" : "//cdn.datatables.net/plug-ins/1.10.13/i18n/Russian.json"
      }
    });

    $("#newItemModalBtn").on('click', function() {

        // Обнуляем форму и очищаем классы-помощники
        $("#newItemForm")[0].reset();
        $(".form-group").removeClass('has-error').removeClass('has-success');
        $(".text-danger").remove();

        // Очищаем блок сообщения
        $(".messages").html("");

        // Отправляем запрос на список городов, доступных для выбора, и пакуем их в select
        $.ajax({
            "url"     : '?controller=phonebook&action=getCityNames',
            "success" : function(response) {
                var cities = JSON.parse(response);

                var options = '<option value="0">Не выбран</option>';
                cities.forEach(function(item, i) {
                    options = options + '<option value="' + item[0] + '">' + item[1] + '</option>';
                });
                
                $("select#city").html(options);

                // К смене города привязываем подгрузку улиц этого города
                $("select#city").unbind('change').bind('change', function() {

                    var id = $("select#city").val();

                    $.ajax({
                        "url"     : "?controller=phonebook&action=getStreetNamesByCity",
                        "type"    : "post",
                        "data"    : { item_id : id },
                        "success" : function(response) {
                            var streets = JSON.parse(response);

                            var options = '<option value="0">Не выбрана</option>';
                            streets.forEach(function(item, i) {
                                options = options + '<option value="' + item[0] + '">' + item[1] + '</option>';
                            });
                            
                            $("select#street").html(options);
                        }
                    });
                });
            }
        });

        // Биндим отправку
        $("#newItemForm").unbind('submit').bind('submit', function() {
            var form = $(this);

            $(".text-danger").remove();

            // Валидация формы

            var phone          = $("#phone").val();
            var credentials    = $("#credentials").val();
            var birthday       = $("#birthday").val();
            var city           = $("#city").val();
            var street         = $("#street").val();

            var formData = {
                "phone"          : phone, 
                "credentials"    : credentials, 
                "birthday"       : birthday,
                "city"           : city,
                "street"         : street
            };

            for (var key in formData) {
                var itemID = '#' + key;
                if (((key == "street" || key == "city") && formData[key] == 0) || formData[key] == '') {
                    $(itemID).closest('.form-group').addClass('has-error');
                    $(itemID).after('<p class="text-danger">Поле обязательно к заполнению!</p>');                    
                } else {
                    $(itemID).closest('.form-group').removeClass('has-error');
                    $(itemID).closest('.form-group').addClass('has-success');                   
                }
            };

            // Отправляем форму

            if (phone && credentials && birthday && city != 0 && street != 0) {
                $.ajax({
                    "url"     : form.attr('action'),
                    "type"    : form.attr('method'),
                    "data"    : form.serialize(),
                    "dataType": 'json',
                    "success" : function(response) {

                        // Очищаем классы-помощники
                        $(".form-group").removeClass('has-error').removeClass('has-success');

                        if (response.success === true) {
                            $(".messages").html('<div class="alert alert-success alert-dismissible" role="alert">' + 
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            '<strong><span class="glyphicon glyphicon-ok"></span></strong> ' + response.messages + '</div>');

                            // Снова обнуляем форму
                            $("#newItemForm")[0].reset();

                            // Обновляем таблицу с новой записью
                            phonebookTable.ajax.reload(null, false);

                        } else {
                            $(".messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' + 
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            '<strong><span class="glyphicon glyphicon-exclamation"></span></strong> ' + response.messages + '</div>');
                        }
                    }
                });
            }

            return false;
        });

    });

});

function removeItem(id = null) {
    if (id) {

        // Кнопка удаления записи
        $("#removeBtn").unbind('click').bind('click', function() {
            $.ajax({
                "url"     : '?controller=phonebook&action=delete',
                "type"    : 'post',
                "data"    : { item_id : id },
                "dataType": 'json',
                "success" : function(response) {
                    if (response.success === true ) {
                        $(".removeMessages").html('<div class="alert alert-success alert-dismissible" role="alert">' + 
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<strong><span class="glyphicon glyphicon-ok"></span></strong> ' + response.messages + '</div>');

                        // Обновление таблицы после удаления
                        phonebookTable.ajax.reload(null, false);

                        // Закрываем модальное окно удаления
                        $("#removeItemModal").modal('hide');

                    } else {
                        $(".removeMessages").html('<div class="alert alert-warning alert-dismissible" role="alert">' + 
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<strong><span class="glyphicon glyphicon-exclamation"></span></strong> ' + response.messages + '</div>');
                    }
                }
            });
        });

    }

}

function editItem(id = null) {

    if (id) {

        // Обнуляем форму и очищаем классы-помощники
        $(".form-group").removeClass('has-error').removeClass('has-success');
        $(".text-danger").remove();

        // Очищаем блок сообщения
        $(".edit-messages").html("");

        // Удаляем input c ID
        $("#item_id").remove();

        // Написать БОЛЬШОЙ КОММЕНТ
        $.ajax({
            "url"     : '?controller=phonebook&action=getDataForEditModal',
            "type"    : 'post',
            "data"    : { item_id: id },
            "dataType": 'json',
            "success" : function(response) {

                var modal_data = response;

                var cities = modal_data["cities"];
                var city_options = '<option value="0">Не выбран</option>';
                cities.forEach(function(item, i) {
                    city_options = city_options + '<option value="' + item[0] + '">' + item[1] + '</option>';
                });
                
                $("select#editCity").html(city_options);

                // К смене города привязываем подгрузку улиц этого города
                $("select#editCity").unbind('change').bind('change', function() {

                    var id = $("select#editCity").val();

                    $.ajax({
                        "url"     : "?controller=phonebook&action=getStreetNamesByCity",
                        "type"    : "post",
                        "data"    : { item_id : id },
                        "success" : function(response) {
                            var streets = JSON.parse(response);

                            var options = '<option value="0">Не выбрана</option>';
                            streets.forEach(function(item, i) {
                                options = options + '<option value="' + item[0] + '">' + item[1] + '</option>';
                            });
                            
                            $("select#editStreet").html(options);
                        }
                    });
                });

                var streets = modal_data["streets"];
                var street_options = '<option value="0">Не выбрана</option>';
                streets.forEach(function(item, i) {
                    street_options = street_options + '<option value="' + item[0] + '">' + item[1] + '</option>';
                });
                $("select#editStreet").html(street_options);

                var contact = modal_data["contact"];
                $("#editPhone").val(contact["Phone"]);
                $("#editCredentials").val(contact["Credentials"]);
                $("#editBirthday").val(contact["Birthday"]);
                $("#editCity").val(contact["city_id"]);
                $("#editStreet").val(contact["street_id"]);

                // Обновляем данные записи
                $("#editItemForm").unbind('submit').bind('submit', function() {

                    $(".text-danger").remove();

                    var form = $(this);

                    // Валидация формы
                    var editPhone         = $("#editPhone").val();
                    var editCredentials   = $("#editCredentials").val();
                    var editBirthday      = $("#editBirthday").val();
                    var editCity          = $("#editCity ").val();
                    var editStreet        = $("#editStreet").val();

                    // Пробрасываем ID записи
                    $(".editItemModalFooter").append('<input type="hidden" name="item_id" id="item_id" value="' +  contact["ID"] + '">');

                    var formData = {
                        "editPhone"          : editPhone, 
                        "editCredentials"    : editCredentials, 
                        "editBirthday"       : editBirthday,
                        "editCity"           : editCity,
                        "editStreet"         : editStreet,
                    };

                    for (var key in formData) {
                        var itemID = '#' + key;
                        if (((key == "editStreet" || key == "editCity") && formData[key] == 0) || formData[key] == '') {
                            $(itemID).closest('.form-group').addClass('has-error');
                            $(itemID).after('<p class="text-danger">Поле обязательно к заполнению!</p>');
                        } else {
                            $(itemID).closest('.form-group').removeClass('has-error');
                            $(itemID).closest('.form-group').addClass('has-success');
                        }
                    };

                    if (editPhone && editCredentials && editBirthday && editCity != 0 && editStreet != 0) {

                        // Отправляем форму
                        $.ajax({
                            "url"     : form.attr('action'),
                            "type"    : form.attr('method'),
                            "data"    : form.serialize(),
                            "dataType": 'json',
                            "success" : function(response) {

                                // Очищаем классы-помощники
                                $(".form-group").removeClass('has-error').removeClass('has-success');
                                $(".text-danger").remove();

                                if (response.success === true) {
                                    $(".edit-messages").html('<div class="alert alert-success alert-dismissible" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                    '<strong><span class="glyphicon glyphicon-ok"></span></strong> ' + response.messages + '</div>');

                                    // Обновляем таблицу с новой записью
                                    phonebookTable.ajax.reload(null, false);

                                } else {
                                    $(".edit-messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                                    '<strong><span class="glyphicon glyphicon-exclamation"></span></strong> ' + response.messages + '</div>');
                                }
                            }
                        });
                    }
                    return false;
                });

            }
        });

        // Получаем данные записи - ОСТАНОВИЛСЯ ЗДЕСЬ! Переписать - данные для редактирования берем с фронта
        // $.ajax({
        //     "url"     : '?controller=phonebook&action=find',
        //     "type"    : 'post',
        //     "data"    : { item_id: id },
        //     "dataType": 'json',
        //     "success" : function(response) {
        //         $("#editPhone").val(response["Phone"]);
        //         $("#editCredentials").val(response["Credentials"]);
        //         $("#editBirthday").val(response["Birthday"]);
        //         $("#editCity").val(response["city_id"]);
        //         $("#editStreet").val(response["street_id"]);

        //         // Обновляем данные записи
        //         $("#editItemForm").unbind('submit').bind('submit', function() {

        //             $(".text-danger").remove();

        //             var form = $(this);

        //             // Валидация формы
        //             var editPhone         = $("#editPhone").val();
        //             var editCredentials   = $("#editCredentials").val();
        //             var editBirthday      = $("#editBirthday").val();

        //             // Пробрасываем ID записи
        //             $(".editItemModalFooter").append('<input type="hidden" name="item_id" id="item_id" value="' +  response["ID"] + '">');

        //             var formData = {
        //                 "editPhone"          : editPhone, 
        //                 "editCredentials"    : editCredentials, 
        //                 "editBirthday"       : editBirthday
        //             };

        //             for (var key in formData) {
        //                 var itemID = '#' + key;
        //                 if (formData[key] == '') {
        //                     $(itemID).closest('.form-group').addClass('has-error');
        //                     $(itemID).after('<p class="text-danger">Поле обязательно к заполнению!</p>');
        //                 } else {
        //                     $(itemID).closest('.form-group').removeClass('has-error');
        //                     $(itemID).closest('.form-group').addClass('has-success');
        //                 }
        //             };

        //             if (editPhone && editCredentials && editBirthday) {

        //                 // Отправляем форму
        //                 $.ajax({
        //                     "url"     : form.attr('action'),
        //                     "type"    : form.attr('method'),
        //                     "data"    : form.serialize(),
        //                     "dataType": 'json',
        //                     "success" : function(response) {

        //                         // Очищаем классы-помощники
        //                         $(".form-group").removeClass('has-error').removeClass('has-success');
        //                         $(".text-danger").remove();

        //                         if (response.success === true) {
        //                             $(".edit-messages").html('<div class="alert alert-success alert-dismissible" role="alert">' + 
        //                             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        //                             '<strong><span class="glyphicon glyphicon-ok"></span></strong> ' + response.messages + '</div>');

        //                             // Обновляем таблицу с новой записью
        //                             phonebookTable.ajax.reload(null, false);

        //                         } else {
        //                             $(".edit-messages").html('<div class="alert alert-warning alert-dismissible" role="alert">' + 
        //                             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        //                             '<strong><span class="glyphicon glyphicon-exclamation"></span></strong> ' + response.messages + '</div>');
        //                         }
        //                     }
        //                 });
        //             }
        //             return false;
        //         });
        //     }


        // });

    }
}
