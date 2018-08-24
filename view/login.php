<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistema Obras</title>

    <!-- Bootstrap Core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="assets/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Iniciar Sesión</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="signin">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Ingresa tu usuario" id="username" name="username" type="text" autofocus required
                                oninvalid="setCustomValidity('Por favor ingresa un usuario valido')"
                                oninput="setCustomValidity('')">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Contraseña" id="inputPassword" name="inputPassword" type="password" value="" required
                                oninvalid="setCustomValidity('Por favor ingresa una contraseña valida')"
                                oninput="setCustomValidity('')">
                                </div>
                                <input type="hidden" name="state" id="state" value="<?=$_SESSION['state']?>" />
                                <!-- Change this to a button or input when using this as a form -->
                                <!-- <a href="#" class="btn btn-lg btn-success btn-block">Ingresar</a> -->
                                <button class="btn btn-lg btn-success btn-block" type="submit">Ingresar</button>
                                <!-- <button class="btn btn-lg btn-success btn-block" type="button" id="btnIngresar">Ingresar</button> -->
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="assets/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="assets/dist/js/sb-admin-2.js"></script>

    <script src="assets/dist/js/login.js"></script>

</body>

</html>

<!-- Modal -->
    <div class="modal fade" id="informacionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">              
                    <h4 class="modal-title">Informaci&oacute;n</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>             
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
<!-- /.modal -->
