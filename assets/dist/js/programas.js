$(function() {
	var octopusProgramas = {
        init: function() {
        	//console.log("Iniciando Controlador");
            viewProgramas.init();
            prgFrmView.init();
        },
        getProgramas: function() {
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
                    url:"?m=muestraProgramas",
                    type:"POST"
                },
                "columns": [
                    { "data": null, render: function ( data, type, row ) { 
                           return ( data.subprograma === null ) ? data.programa : data.subprograma;
                       } 
                    },
                    { "data": null, render: function ( data, type, row ) { 
                           return ( data.subprograma === null ) ? '' : data.programa; 
                       } 
                    },
                    { "data": "orden" },
                    { "data": "archivo" },
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
        getModulos: function(programa_id) {
            $.ajax({
                url:"?m=buscaModulos",
                method:"POST",
                dataType:"json",
                //async: false, 
                success:function(data){
                    //console.log(data);
                    if(data.length){
                        $("#zprograma_id").append(
                            $("<option></option>").attr(
                                "value", "").text("Seleccione")
                        );
                    }
                    for (var i = 0; i < data.length; i++) {
                        var modulo = data[i];
                        //console.log(modulo.id + ' - ' +modulo.programa);
                        $("#zprograma_id").append(
                            $("<option></option>").attr(
                                "value", modulo.id).text(modulo.programa)
                        );
                    }
                    $('#zprograma_id').val(programa_id);
                }
            });
        },
        agregaPrograma: function(frm) {
            //console.log( frm.serialize() );
            var modalFrm = $('#userModal');
            $.ajax({
                url:"?m=addPrograma",
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
        buscarPrograma: function(programa_id) {
            //console.log("Buscar Programa: "+programa_id);
            var modalFrm = $('#userModal');
            $.ajax({
                url:"?m=buscaPrograma",
                method:"POST",
                dataType:"json",
                //async: false, 
                data: {'programa_id':programa_id},
                beforeSend:function(){
                    modalFrm.addClass('loading');
                },
                success:function(data){
                    modalFrm.removeClass('loading');
                    //console.log(data);
                    $('#des_pro').val(data.programa);
                    $('#icono').val(data.icono);
                    if (data.zprograma_id === null){
                        document.getElementById("es_mod").value = "N";
                        $('#es_mod').val("N");
                        $("#zprograma_id").empty();
                    }else{
                        $('#es_mod').val("S");
                        octopusProgramas.getModulos(data.zprograma_id);
                    }
                    $('#archivo').val(data.archivo);
                    $('#orden').val(data.orden);
                      
                }
            });
        },
        modificaPrograma: function(frm) {
            //console.log( frm.serialize() );
            var modalFrm = $('#userModal');
            $.ajax({
                url:"?m=actualizaPrograma",
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
        eliminarPrograma: function(programa_id) {
            //console.log("Elimanar: "+programa_id);
            $.ajax({
                url:"?m=eliminarPrograma",
                method:'POST',
                dataType:"json",
                data: {'programa_id':programa_id},
                success:function(data){
                    //console.log(data);
                    if(data['error']===""){
                        dataTable.ajax.reload();
                    }
                }
            });
        } 
    };

    var viewProgramas = {
        init: function() {
            //console.log("Iniciando Vista");
            octopusProgramas.getProgramas();

            this.render();
        },

        render: function() {
            //console.log("Actualizando Vista de Lista ");
            $('#user_data').on('click', '#update', function(){
                var programa_id = $(this).attr("aId");
                //console.log("UPDATE: "+programa_id);
                $('#user_form')[0].reset();
                $("#zprograma_id").empty();
                $('.modal-title').text("Editar Programa");
                $('#action').val("Editar");
                $('#operation').val("Editar");
                $('#userModal').modal('show');  
                $('#programa_id').val(programa_id);
                octopusProgramas.buscarPrograma(programa_id);
            });

            $('#user_data').on('click', '#delete', function(){
                var programa_id = $(this).attr("aId");
                //console.log("DELETE: "+programa_id);
                $('.modal-title').text("Eliminar Programa");
                $('#confirmEliminar').modal('show');
                $('#confirmEliminar').on('click', '.btn-ok', function(e) {
                    $('#confirmEliminar').modal('hide');
                    var modalDiv = $(e.delegateTarget);
                    octopusProgramas.eliminarPrograma(programa_id);
                });
            });
                   
        }
    };

    var prgFrmView = {
        init: function(){
            //console.log("Iniciando Vista Frm");
            this.$addProgramaBtn = $('#add_button');
            var frmPrograma = $('#user_form');
            this.$componente = $("#es_mod"); 

            this.$addProgramaBtn.on('click', function(){
                $('#user_form')[0].reset();
                $("#zprograma_id").empty();
                $('.modal-title').text("Agregar Programa");
                $('#action').val("Agregar");
                $('#operation').val("Agregar");
            });

            this.$componente.change(function() {
                var es_mod = $(this).val();
                var aux_zprograma_id = "";
                $("#zprograma_id").empty();
                if (es_mod=='S'){
                    //console.log("Cargo el combo para que seleccione Modulo al que pertenece");
                    octopusProgramas.getModulos("");
                }
            });

            frmPrograma.submit(function(e){
                //console.log("Envio Frm");
                if( $('#programa_id').val()==='' ){
                    //console.log("NUEVO");
                    octopusProgramas.agregaPrograma(frmPrograma);
                }else{
                    //console.log("MODIFICANDO");
                    octopusProgramas.modificaPrograma(frmPrograma);
                }

                e.preventDefault();
                
            });

            this.render();
        },
        render: function() {
            //console.log("Actualizando Vista Frm");            
        }
    }
	octopusProgramas.init();
}());