
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?=$settings['project_name']?></title>

    <!-- Bootstrap core CSS -->
    <link href="https://bootswatch.com/slate/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container">

    <div class="masthead">
        <h3 class="text-muted"><?=$settings['project_name']?></h3>
        <nav>
            <ul class="nav nav-justified">
                <li class="active"><a href="/">Home</a></li>
            </ul>
        </nav>
    </div>

    <h3>Результат:</h3>
    <pre>
        <?=$output?>
    </pre>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    <!-- Site footer -->
    <footer class="footer">
        <p>&copy; <a href="http://ershov.pw/" target="_blank">Илья Ершов</a> 2015</p>
    </footer>

</div> <!-- /container -->


</body>
</html>
