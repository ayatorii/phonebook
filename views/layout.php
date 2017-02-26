<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Справочник</title>

  <!-- Bootstrap Core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- DataTables CSS -->
  <!-- <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css"> -->

  <!-- DataTables Bootstrap styling -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">

  <!-- Кастом CSS -->
  <link rel="stylesheet" type="text/css" href="css/style.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>

<body>

    <!-- Навигация -->
  <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      </div>
        <!-- /.navbar-collapse -->
    </div>

      <!-- /.container -->
  </nav>

  <!-- Контент страницы -->
  <div class="container">
  
    <!-- Вызываем маршрутизатор-->
    <?php require_once 'router.php'; ?>

  </div>
    <!-- /.container -->

  <!-- Модальное окно - новая запись -->
  <div class="modal fade" tabindex="-1" role="dialog" id="newItemModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title"><span class="glyphicon glyphicon-plus"></span> Добавление записи</h4>
        </div>

        <form action="?controller=phonebook&action=add" method="POST" id="newItemForm">
          <div class="modal-body">
            <div class="messages"></div>

            <!-- Форма - новая запись -->
            <div class="form-group">
              <label for="phone">Номер телефона <span class="glyphicon glyphicon-asterisk"></span><small class="text-muted"> (начиная с 8, без разделителей)</small></label>
              <input type="tel" pattern="8[0-9]{10}" class="form-control" id="phone" name="phone" placeholder="Номер телефона">
            </div>
            <div class="form-group">
              <label for="credentials">ФИО <span class="glyphicon glyphicon-asterisk"></span></label>
              <input type="text" class="form-control" id="credentials" name="credentials" placeholder="ФИО">
            </div>
            <div class="form-group">
              <label for="birthday">Дата рождения <span class="glyphicon glyphicon-asterisk"></span></label>
              <input type="date" class="form-control" id="birthday" name="birthday" placeholder="Дата рождения">
            </div>
            <div class="form-group">
              <label for="city">Город <span class="glyphicon glyphicon-asterisk"></span></label>
              <select class="form-control" id="city" name="city">
              </select>
            </div>
            <div class="form-group">
              <label for="street">Улица <span class="glyphicon glyphicon-asterisk"></span></label>
              <select class="form-control" id="street" name="street">
              <option value="0">Не выбрана</option>
              </select>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            <button type="submit" class="btn btn-success">Сохранить</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


  <!-- Модальное окно - удаление -->
  <div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><span class="glyphicon glyphicon-trash"></span> Удаление записи</h4>
        </div>
        <div class="modal-body">
          <p>Вы действительно хотите удалить эту запись?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
          <button type="button" class="btn btn-danger" id="removeBtn">Удалить</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!-- Модальное окно - редактирование -->
  <div class="modal fade" tabindex="-1" role="dialog" id="editItemModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><span class="glyphicon glyphicon-edit"></span> Редактирование записи</h4>
        </div>

        <form action="?controller=phonebook&action=update" method="POST" id="editItemForm">
          <div class="modal-body">
            
              <div class="edit-messages"></div>

              <!-- Форма - редактирование -->
              <div class="form-group">
                <label for="editPhone">Номер телефона <span class="glyphicon glyphicon-asterisk"></span><small class="text-muted"> (начиная с 8, без разделителей)</small></label>
                <input type="tel" pattern="8[0-9]{10}" class="form-control" id="editPhone" name="editPhone" placeholder="Номер телефона">
              </div>
              <div class="form-group">
                <label for="editCredentials">ФИО <span class="glyphicon glyphicon-asterisk"></span></label>
                <input type="text" class="form-control" id="editCredentials" name="editCredentials" placeholder="ФИО">
              </div>
              <div class="form-group">
                <label for="editBirthday">Дата рождения <span class="glyphicon glyphicon-asterisk"></span></label>
                <input type="date" class="form-control" id="editBirthday" name="editBirthday" placeholder="Дата рождения">
              </div>
              <div class="form-group">
                <label for="editCity">Город <span class="glyphicon glyphicon-asterisk"></span></label>
                <select class="form-control" id="editCity" name="editCity">
                </select>
              </div>
              <div class="form-group">
                <label for="editStreet">Улица <span class="glyphicon glyphicon-asterisk"></span></label>
                <select class="form-control" id="editStreet" name="editStreet">
                <option value="0">Не выбрана</option>
                </select>
              </div>

          </div>
          <div class="modal-footer editItemModalFooter">
            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            <button type="submit" class="btn btn-success">Сохранить</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="js/bootstrap.min.js"></script>

  <!-- DataTables JS -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

  <!-- DataTables Bootstrap JS -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
  
  <!-- Кастом JS -->
  <script type="text/javascript" charset="utf8" src="js/script.js"></script>
</body>
</html>