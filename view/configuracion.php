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
                                                <th width="30%">Modulo</th>
                                                <th width="30%">Componente</th>
                                                <th width="15%">Orden</th>
                                                <th width="15%">Archivo</th>
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
                    <h4 class="modal-title">Agregar Programa</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group input-group-sm">
                                <label>Programa</label>
                                <input type="text" name="des_pro" id="des_pro" class="form-control" required autofocus 
                                oninvalid="this.setCustomValidity('Debe ingresar el nombre del nuevo programa!')" 
                                oninput="setCustomValidity('')" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group input-group-sm">
                                <label>Icono</label>
                                <input type="text" name="icono" id="icono" class="form-control"/>
                            </div>
                        </div>
                    </div>
                 
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                              <label for="es_mod">Es un componente?</label>
                              <select class="form-control" name="es_mod" id="es_mod" required 
                              oninvalid="this.setCustomValidity('Debe seleccionar modo del programa!')" 
                                oninput="setCustomValidity('')">
                                <option value=""></option>
                                <option value="S">SI</option>
                                <option value="N">NO</option>
                              </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="form-group input-group-sm">
                                <label for="es_mod">Modulo</label>
                                    <select class="form-control" name="zprograma_id" id="zprograma_id"></select>
                            </div>
                        </div>
                    </div>
                         
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group input-group-sm">
                             <label>Archivo</label>
                             <input type="text" name="archivo" id="archivo" class="form-control" />
                            </div>
                        </div>
                     </div>
                     
                    <div class="row">
                        <div class="col-lg-12">                
                            <div class="form-group input-group-sm">
                             <label>Orden</label>
                             <input type="number" name="orden" id="orden" class="form-control" />
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="programa_id" id="programa_id" />
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

<script src="assets/dist/js/programas.js"></script>