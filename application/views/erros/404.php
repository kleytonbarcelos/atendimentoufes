<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>404 - Página não encontrada!</title>

    <!-- BOOTSTRAP 3.7.3 -->
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/libs/bootstrap/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/libs/bootstrap/css/bootstrap-theme.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="<?=base_url()?>assets/libs/bootstrap/js/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?=base_url()?>assets/libs/bootstrap/js/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?=base_url()?>assets/libs/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="<?=base_url()?>assets/libs/jquery/jquery-1.11.3.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libs/font-awesome/css/font-awesome.min.css">
</head>
    <body>
        <br><br><br><br><br><br><br><br>
        <div class="jumbotron">
            <div class="container" style="text-align:center;">
                <h1>Oops!</h1>
                <h3>404 - Página não encontrada!</h3>
                <p>
                    <h5>Desculpe, a página requisita não foi encontrada!</h5>
                    <br><br><br>
                    <button class="btn btn-success btn-lg" onclick="window.history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</button>
                    &nbsp;
                    <button class="btn btn-primary btn-lg" onclick="window.location.href='<?=base_url()?>'"><i class="fa fa-home"></i> Página inicial</button>
                </p>
            </div>
        </div>
    </body>
</html>