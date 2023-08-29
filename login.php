<?php
session_start();
session_destroy();
session_start();
unset($_SESSION['acessoNome']);
require ("../class/config.php");
?>
<!doctype html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta name="robots" content="noindex, nofollow">
        <base href="<?php echo URL_ADMIN; ?>/">
        <title><?php echo NOME_PROJETO; ?> - Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="<?php echo URL_ADMIN; ?>/assets/images/favicon.png">
        <link href="<?php echo URL_ADMIN; ?>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL_ADMIN; ?>/assets/css/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="card-box">
                <div class="panel-heading">
                    <h4 class="text-center"> Administrar <strong class="text-custom"><?php echo NOME_PROJETO; ?></strong></h4>
                </div>
                
                <div class="p-20">
                    <form action="#" method="post" name="form_login" id="form_send" class="form-horizontal form_login m-t-10">
                        <div class="form-group ">
                            <div class="col-12">
                                <input autocomplete="off" name="login" class="form-control" type="text" placeholder="Usuário">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12">
                                <input autocomplete="off" name="senha" class="form-control" type="password" placeholder="Senha">
                            </div>
                        </div>

                        <div class="return_form"></div>
                        <input type="hidden" name="action" value="login">
                        <div class="form-group text-center">
                            <div class="col-12">
                                <button type="submit" id="send_login" name="send_login" class="btn btn-pink btn-block text-uppercase waves-effect waves-light">ACESSAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="<?php echo URL_ADMIN; ?>/assets/js/jquery.min.js"></script>
        <script src="<?php echo URL_ADMIN; ?>/assets/js/tether.min.js"></script>
        <script src="<?php echo URL_ADMIN; ?>/assets/js/bootstrap.min.js"></script>
        <script src="<?php echo URL_ADMIN; ?>/assets/js/waves.js"></script>
        <script src="<?php echo URL_ADMIN; ?>/assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo URL_ADMIN; ?>/assets/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo URL_ADMIN; ?>/assets/js/jquery.core.js"></script>
        <script src="<?php echo URL_ADMIN; ?>/assets/js/jquery.app.js"></script>
    </body>
</html>