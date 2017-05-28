<?php require_once('config.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo $CONFIG->wwwroot . "/css/styles.css"; ?>">
    <link rel="stylesheet" href="<?php echo $CONFIG->wwwroot . "/css/bootstrap.min.css"; ?>">
    <link rel="stylesheet" href="<?php echo $CONFIG->wwwroot . "/css/bootstrap-datepicker.min.css"; ?>">
    <script type="text/javascript" src="<?php echo $CONFIG->wwwroot . "/js/jquery-3.1.1.min.js"; ?>"></script>
    <script type="text/javascript" src="<?php echo $CONFIG->wwwroot . "/js/bootstrap-datepicker.min.js"; ?>"></script>
    <script type="text/javascript" src="<?php echo $CONFIG->wwwroot . "/js/bootstrap-datepicker.ru.min.js"; ?>"></script>
    <script type="text/javascript" src="<?php echo $CONFIG->wwwroot . "/js/bootstrap.min.js"; ?>"></script>
    <script type="text/javascript" src="<?php echo $CONFIG->wwwroot . "/js/api.js"; ?>"></script>
    <script type="text/javascript" src="<?php echo $CONFIG->wwwroot . "/js/mustache.js"; ?>"></script>
    <title>Анкетирование</title>
</head>
<body data-wwwroot="<?php echo $CONFIG->wwwroot; ?>">
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">AXIOMA-test</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li>
                    <a id="main-public" href="#">Публичная часть</a>
                </li>
                <li>
                    <a id="main-admin" href="#">Административная часть</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div id="content"></div>
</div>
</body>
</html>
