$(function() {
	var octopusUsuarios = {
        init: function() {
        	//console.log("Iniciando Controlador");
            viewUsuarios.init();
            usrFrmView.init();
        },
        getUsuarios: function() {
            //console.log("Iniciando DataTable");
            dataTable = $('#user_data').DataTable({ 
                responsive: true,
                "language": {
                    "url": "assets/vendor/datatables/Spanish.json"
                }, 
                "processing": true,
                "serverSide": true,
                "paging": true,
                deferRender: true,
                "order": [],
                "ajax": {
                    url:"?m=muestraUsuarios",
                    type:"POST"
                },
                "columns": [
                    { "data": "datos" },
                    { "data": "usuario" },
                    { "data": "perfil" },
                    { "data": null, render: function ( data, type, row ) { 
                           return ( data.bloqueado === "0" ) ? "NO" : "SI";
                       } 
                    },
                    { "data": null, render: function ( data, type, row ) { 
                        var btn = '<button type="button" class="btn btn-warning btn-circle" id="update" aId="'+data.id+'"><i class="fa fa-pencil-square"></i>';
                        btn += '<button type="button" class="btn btn-danger btn-circle" id="delete" aId="'+data.id+'"><i class="fa fa-trash"></i>';
                        return btn;
                       } 
                    }
                ],
                columnDefs: [
                    { className: 'text-center', targets: [4] },
                ]
            }).on( 'xhr', function ( e, settings, json, data ) {
                //console.log( 'Ajax event occurred. Returned settings: ', settings );
                //console.log( 'Ajax event occurred. Returned data: ', json );
                //console.log( 'Ajax event occurred. Returned data: ', data.responseText );
            });
        },
        getPerfiles: function(perfil_id) {
            $.ajax({
                url:"?m=buscaPerfiles",
                method:"POST",
                dataType:"json",
                //async: false, 
                success:function(data){
                    //console.log(data.length);
                    if(data.length){
                        $("#zperfil_id").append(
                            $("<option></option>").attr(
                                "value", "").text("Seleccione")
                        );
                    }
                    for (var i = 0; i < data.length; i++) {
                        var perfil = data[i];
                        //console.log(perfil.id + ' - ' +perfil.perfil);
                        $("#zperfil_id").append(
                            $("<option></option>").attr(
                                "value", perfil.id).text(perfil.perfil)
                        );
                    }
                    $('#zperfil_id').val(perfil_id);
                }
            });
        },
        agregaUsuario: function(frm) {
            //console.log( frm.serialize() );
            var modalFrm = $('#userModal');
            $.ajax({
                url:"?m=addUser",
                method:"POST",
                dataType:"json",
                //async: false, 
                data: frm.serialize(),
                beforeSend:function(){
                    modalFrm.addClass('loading');
                },
                success:function(data){
                    modalFrm.removeClass('loading');
                    console.log(data);
                    var modal = $('#informacionModal'); 
                    
                    if(data['error']===null || data['error']===""){
                        //console.log("S/E");
                        $('#user_form')[0].reset();
                        $('#userModal').modal('hide');
                        dataTable.ajax.reload();
                        modal.find('.modal-body').text(data['aMsg'])                
                    }else{
                        //console.log("C/E");
                        modal.find('.modal-body').text(data['error'])
                    }
                    $('#informacionModal').modal('show');   
                }
            });
        },
        buscarUsuario: function(usuario_id) {
            //console.log("Buscar Usuario: "+usuario_id);
            var modalFrm = $('#userModal');
            $.ajax({
                url:"?m=buscaUsuario",
                method:"POST",
                dataType:"json",
                //async: false, 
                data: {'usuario_id':usuario_id},
                beforeSend:function(){
                    modalFrm.addClass('loading');
                },
                success:function(data){
                    modalFrm.removeClass('loading');
                    //console.log(data);
                    $('#usuario').val(data.usuario);
                    $("#zperfil_id").empty();
                    octopusUsuarios.getPerfiles(data.zperfil_id);
                    $('#clave').val(data.clave);
                    $('#clave2').val(data.clave);
                    $('#nombres').val(data.nombres);
                    $('#apellidos').val(data.apellidos);
                    $('#cedula').val(data.cedula);
                    $('#telefono').val(data.telefonos);
                    $('#direccion').val(data.direccion);
                    $('#bloqueado').val(data.bloqueado);
                      
                }
            });
        },
        modificaUsuario: function(frm) {
            //console.log( frm.serialize() );
            var modalFrm = $('#userModal');
            $.ajax({
                url:"?m=actualizaUsuario",
                method:"POST",
                dataType:"json",
                //async: false, 
                data: frm.serialize(),
                beforeSend:function(){
                    modalFrm.addClass('loading');
                },
                success:function(data){
                    modalFrm.removeClass('loading');
                    //console.log(data);
                    var modal = $('#informacionModal'); 
                    
                    if(data['error']===null  || data['error']===""){
                        //console.log("S/E");
                        $('#user_form')[0].reset();
                        $('#userModal').modal('hide');
                        dataTable.ajax.reload();
                        modal.find('.modal-body').text(data['aMsg'])                
                    }else{
                        //console.log("C/E");
                        modal.find('.modal-body').text(data['error'])
                    }
                    $('#informacionModal').modal('show');   
                }
            });
        },
        eliminarUsuario: function(usuario_id) {
            //console.log("Elimanar: "+usuario_id);
            $.ajax({
                url:"?m=eliminarUsuario",
                method:'POST',
                dataType:"json",
                data: {'usuario_id':usuario_id},
                success:function(data){
                    console.log(data);
                    if(data['error']===""){
                        dataTable.ajax.reload();
                    }
                }
            });
        } 
    };

    var viewUsuarios = {
        init: function() {
            //console.log("Iniciando Vista");
            octopusUsuarios.getUsuarios();

            this.render();
        },

        render: function() {
            //console.log("Actualizando Vista de Lista ");
            $('#user_data').on('click', '#update', function(){
                var usuario_id = $(this).attr("aId");
                //console.log("UPDATE: "+programa_id);
                $('#user_form')[0].reset();
                $("#zperfil_id").empty();
                octopusUsuarios.getPerfiles("");

                $('.modal-title').text("Editar Usuario");
                $('#action').val("Editar");
                $('#operation').val("Editar");
                $('#userModal').modal('show');  
                $('#usuario_id').val(usuario_id);
                octopusUsuarios.buscarUsuario(usuario_id);
            });

            $('#user_data').on('click', '#delete', function(){
                var usuario_id = $(this).attr("aId");
                //console.log("DELETE: "+programa_id);
                $('.modal-title').text("Eliminar Usuario");
                $('#confirmEliminar').modal('show');
                $('#confirmEliminar').on('click', '.btn-ok', function(e) {
                    $('#confirmEliminar').modal('hide');
                    var modalDiv = $(e.delegateTarget);
                    octopusUsuarios.eliminarUsuario(usuario_id);
                });
            });
                   
        }
    };
    
    var usrFrmView = {
        init: function(){
            //console.log("Iniciando Vista Frm");
            this.$addUserBtn = $('#add_button');
            var frmUsuario = $('#user_form');

            $("#zperfil_id").empty();
            octopusUsuarios.getPerfiles("");

            this.$addUserBtn.on('click', function(){
                $('#user_form')[0].reset();
                $('.modal-title').text("Agregar Usuario");
                $('#action').val("Agregar");
                $('#operation').val("Agregar");
            });

            frmUsuario.submit(function(e){
                //console.log("Envio Frm");
                if ( $('#clave').val() === $('#clave2').val() ){
                    if( $('#usuario_id').val()==='' ){
                        //console.log("NUEVO");
                        octopusUsuarios.agregaUsuario(frmUsuario);
                    }else{
                        //console.log("MODIFICANDO");
                        octopusUsuarios.modificaUsuario(frmUsuario);
                    }
                }else{
                    console.log("Claves diferentes");
                }

                e.preventDefault();
                
            });

            //this.render();
        },
        render: function() {
            //console.log("Actualizando Vista Frm");            
        }
    }
    

	octopusUsuarios.init();
}());