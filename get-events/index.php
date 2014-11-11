<?php
    $campaign= $_GET['campaign'];
    if(empty($campaign)) $campaign='mfpa';
?>
<!DOCTYPE html>
<html lang="ru" ng-app="app"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>Emarsys API</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse">
    <div class="container">
        <ul class="nav navbar-nav">
            <li><a class="blog-nav-item active" href="/get-events/?campaign=mfpa">mfpa</a></li>
            <li><a class="blog-nav-item active" href="/get-events/?campaign=sbs">sbs</a></li>
            <li><a class="blog-nav-item active" href="/get-events/?campaign=egemetr">egemetr</a></li>
            <li><a class="blog-nav-item active" href="/get-events/?campaign=megacampus">megacampus</a></li>
        </ul>
    </div>
</div>

<div class="container" ng-controller="GemController as gems">
    <h1><?= $campaign; ?></h1>
    <div ng-repeat="row in gems.rows" style="display: inline-block; margin:20px;">
        <h3> {{row.id}}</h3>
        <h5>{{row.name}}</h5>
    </div>
</div><!-- /.container -->

<!-- Placed at the end of the document so the pages load faster -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
<script src="app.js"></script>

<script type="text/javascript">
    var docState={campaign:"<?= $campaign; ?>"};
    // $(document).ready(function(){ console.log('Doc ready'); });
</script>

</body>
</html>
