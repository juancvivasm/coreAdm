<? include_once "layouts/header.php"; ?> 
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header"><?=$prgDatos->programa?></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Información 
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post" id="frmClave">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control" placeholder="Usuario" value="<?=$_SESSION['usuario']?>" readonly>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-unlock"></i></span>
                                            <input type="password" id="claveActual" name="claveActual" class="form-control" placeholder="Contraseña anterior" required autofocus 
                             oninvalid="this.setCustomValidity('Debe ingresar su contraseña actual!')" 
                        oninput="setCustomValidity('')">
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                            <input type="password" id="claveNueva" name="claveNueva" class="form-control" placeholder="Contraseña nueva" required 
                             oninvalid="this.setCustomValidity('Debe ingresar su contraseña nueva!')" 
                        oninput="setCustomValidity('')">
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                            <input type="password" id="claveConfirmacion" name="claveConfirmacion" class="form-control" placeholder="Confirmar contraseña" required 
                             oninvalid="this.setCustomValidity('Debe confirmar su contraseña nueva!')" 
                        oninput="setCustomValidity('')">
                                        </div>
                                        <button type="submit" class="btn btn-default pull-right">Cambiar Contraseña</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
<? include_once "layouts/footer.php"; ?>

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

<script src="assets/dist/js/clave.js"></script>