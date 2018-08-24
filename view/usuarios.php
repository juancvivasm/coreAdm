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
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div align="right">
                                    <button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info btn-sm">Agregar</button>
                                </div>
                                <br />
                                <div class="dataTable_wrapper">
                                    <table width="100%" class="table table-striped table-bordered table-hover" cellspacing="0" id="user_data">
                                        <thead>
                                            <tr>
                                                <th width="30%">Nombre Completo</th>
                                                <th width="20%">Usuario</th>
                                                <th width="20%">Perfil</th>
                                                <th width="10%">Bloqueo</th>
                                                <th width="10%">Accion</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
<? include_once "layouts/footer.php"; ?>

<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="user_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Agregar Usuario</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                             <label>Usuario</label>
                             <input type="text" name="usuario" id="usuario" class="form-control" required autofocus 
                            oninvalid="this.setCustomValidity('Debe ingresar el nombre del nuevo usuario!')" 
                            oninput="setCustomValidity('')" />
                            </div>
                        </div>
                         
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                                <label for="zperfil_id">Perfil</label>
                                <select class="form-control" name="zperfil_id" id="zperfil_id" required 
                        oninvalid="this.setCustomValidity('Debe seleccionar el perfil del nuevo usuario!')" 
                        oninput="setCustomValidity('')"></select>
                            </div>
                        </div>
                    </div>
                 
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                             <label>Contrase&ntilde;a</label>
                             <input type="password" name="clave" id="clave" class="form-control" required 
                             oninvalid="this.setCustomValidity('Debe ingresar la clave del nuevo usuario!')" 
                        oninput="setCustomValidity('')" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                             <label>Debe volver a escribir la contrase&ntilde;a</label>
                             <input type="password" name="clave2" id="clave2" class="form-control" data-rule-equalTo="#clave" required oninvalid="this.setCustomValidity('Debe ingresar nuevamente la clave del nuevo usuario!')" 
                        oninput="setCustomValidity('')" />
                            </div>
                        </div>
                    </div>
                         
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                             <label>Nombres</label>
                             <input type="text" name="nombres" id="nombres" class="form-control" required 
                        oninvalid="this.setCustomValidity('Debe ingresar el nombre del nuevo usuario!')" 
                        oninput="setCustomValidity('')" />
                            </div>
                        </div>
                    
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                             <label>Apellidos</label>
                             <input type="text" name="apellidos" id="apellidos" class="form-control" />
                            </div>
                        </div>
                    </div>
                     
                    <div class="row">
                        <div class="col-lg-6">                
                            <div class="form-group input-group-sm">
                             <label>Cedula</label>
                             <input type="text" name="cedula" id="cedula" class="form-control" />
                            </div>
                        </div>
                        
                        <div class="col-lg-6">        
                            <div class="form-group input-group-sm">
                             <label>Telefono</label>
                             <input type="text" name="telefono" id="telefono" class="form-control" />
                            </div>                
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group input-group-sm">
                             <label>Direccion</label>
                             <input type="text" name="direccion" id="direccion" class="form-control" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">        
                            <div class="form-group input-group-sm">
                              <label for="bloqueado">Bloqueado</label>
                              <select class="form-control" name="bloqueado" id="bloqueado">
                                <option value="0">NO</option>
                                <option value="1">SI</option>
                              </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="usuario_id" id="usuario_id" />
                    <input type="hidden" name="operation" id="operation" />
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Agregar" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </form>
    </div>
</div>

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

<!-- Modal 2 -->
    <div class="modal fade" id="confirmEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmar eliminación del registro</h4>
                </div>
                <div class="modal-body">
                    <p>Está a punto de eliminar el registro, este procedimiento es irreversible.</p>
                    <p>Quieres proceder?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger btn-ok">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
<!-- /.modal 2 -->
<script src="assets/dist/js/usuarios.js"></script>