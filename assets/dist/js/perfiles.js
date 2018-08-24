$(function() {
	var octopusPerfiles = {
        init: function() {
        	console.log("Iniciando Controlador");
            viewPerfiles.init();
            perFrmView.init();
        },
        getPerfiles: function() {
            //console.log("Iniciando Data Table @JC");
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
                    url:"?m=muestraPerfiles",
                    type:"POST"
                },
                "columns": [
                    { "data": "perfil" },
                    { "data": null, render: function ( data, type, row ) { 
                        var btn = '<button type="button" class="btn btn-warning btn-circle" id="update" aId="'+data.id+'"><i class="fa fa-pencil-square"></i>';
                        btn += '<button type="button" class="btn btn-danger btn-circle" id="delete" aId="'+data.id+'"><i class="fa fa-trash"></i>';
                        return btn;
                       } 
                    }
                ],
                columnDefs: [
                    { className: 'text-center', targets: [1] },
                ]
            }).on( 'xhr', function ( e, settings, json, data ) {
                //console.log( 'Ajax event occurred. Returned settings: ', settings );
                //console.log( 'Ajax event occurred. Returned data: ', json );
                //console.log( 'Ajax event occurred. Returned data: ', data.responseText );
            });
        },
        getProgramas: function() {
            $.ajax({
                url:"?m=verProgramas",
                method:"POST",
                dataType:"json",
                //async: false, 
                success:function(data){
                    //console.log(data);
                    var container = $('#prg-list');
                    
                    for (var i = 0; i < data.length; i++) {
                        var programa = data[i];
                        //console.log(programa.id+' - '+programa.zprograma_id+' - '+programa.programa);
                        
                        var $checkbox = "";
                        if (programa.zprograma_id === null){
                            //$checkbox += '<label>'+programa.programa+'</label>';
                            $checkbox += '<div class="checkbox">';
                            $checkbox += '<label>';
                            $checkbox += '<input type="checkbox" id="prog[]" bId="'+programa.zprograma_id+'" name="prog[]" value="'+programa.id+'"><strong>'+programa.programa+'</strong>';
                            $checkbox += '</label>';
                            $checkbox += '</div>';
                        }else{
                            $checkbox += '<div class="checkbox custom-checkbox">';
                            $checkbox += '<label>';
                            $checkbox += '<input type="checkbox" id="prog[]" bId="'+programa.zprograma_id+'" name="prog[]" value="'+programa.id+'">'+programa.programa;
                            $checkbox += '</label>';
                            $checkbox += '</div>';
                        }
                        $(container).append($checkbox);
                    }
                }
            });
        },
        agregaPerfil: function(frm) {
            //console.log( frm.serialize() );
            var modalFrm = $('#userModal');
            $.ajax({
                url:"?m=addPerfil",
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
        buscarPerfil: function(perfil_id) {
            //console.log("Buscar Perfil: "+perfil_id);
            var modalFrm = $('#userModal');
            $.ajax({
                url:"?m=buscaPerfil",
                method:"POST",
                dataType:"json",
                //async: false, 
                data: {'perfil_id':perfil_id},
                beforeSend:function(){
                    modalFrm.addClass('loading');
                },
                success:function(data){
                    modalFrm.removeClass('loading');
                    //console.log(data);
                    
                    $('#perfil').val(data.perfil);
                    $.each( data.programas, function( index, value ){
                        //console.log(index+' - '+value.zprograma_id);
                        $('input[type=checkbox][value='+value.zprograma_id+']').prop('checked', true);
                    });

                }
            });
        },
        modificaPerfil: function(frm) {
            //console.log( frm.serialize() );
            var modalFrm = $('#userModal');
            $.ajax({
                url:"?m=actualizaPerfil",
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
        eliminarPerfil: function(perfil_id) {
            //console.log("Eliminar: "+perfil_id);
            $.ajax({
                url:"?m=eliminarPerfil",
                method:'POST',
                dataType:"json",
                data: {'perfil_id':perfil_id},
                success:function(data){
                    //console.log(data);
                    if(data['error']===""){
                        dataTable.ajax.reload();
                    }
                }
            });
        }
    };

    var viewPerfiles = {
        init: function() {
            //console.log("Iniciando Vista");
            octopusPerfiles.getPerfiles();

            this.render();
        },

        render: function() {
            //console.log("Actualizando Vista de Lista ");
            
            $('#user_data').on('click', '#update', function(){
                var perfil_id = $(this).attr("aId");
                //console.log("UPDATE: "+perfil_id);
                $('#user_form')[0].reset();
                //$("#zprograma_id").empty();
                $('.modal-title').text("Editar Perfil");
                $('#action').val("Editar");
                $('#operation').val("Editar");
                $('#userModal').modal('show');  
                $('#perfil_id').val(perfil_id);
                octopusPerfiles.buscarPerfil(perfil_id);
            });

            $('#user_data').on('click', '#delete', function(){
                var perfil_id = $(this).attr("aId");
                //console.log("DELETE: "+perfil_id);
                $('.modal-title').text("Eliminar Perfil");
                $('#confirmEliminar').modal('show');
                $('#confirmEliminar').on('click', '.btn-ok', function(e) {
                    $('#confirmEliminar').modal('hide');
                    var modalDiv = $(e.delegateTarget);
                    octopusPerfiles.eliminarPerfil(perfil_id);
                });
            });
                              
        }
    };

    var perFrmView = {
        init: function(){
            console.log("Iniciando Vista Frm");
            
            this.$addPerfilBtn = $('#add_button');
            var frmPerfil = $('#user_form');
            octopusPerfiles.getProgramas();

            this.$addPerfilBtn.on('click', function(){
                $('#user_form')[0].reset();
                //octopusPerfiles.getProgramas();
                $('.modal-title').text("Agregar Perfil");
                $('#action').val("Agregar");
                $('#operation').val("Agregar");
            });

            frmPerfil.submit(function(e){
                console.log("Envio Frm");

                if ( $('input:checkbox', this).is(':checked') ){
                    //console.log("Todo esta bien");
                    if( $('#perfil_id').val()==='' ){
                        //console.log("NUEVO");
                        octopusPerfiles.agregaPerfil(frmPerfil);
                    }else{
                        //console.log("MODIFICANDO");
                        octopusPerfiles.modificaPerfil(frmPerfil);
                    }
                }else{
                    console.log("Seleccione algo");
                }

                e.preventDefault();
                
            });

            $('#user_form').on('change', 'input[type=checkbox]', function() {
                var cId = $(this).val(); // this gives me null
                var cZprograma_id = $(this).attr("bId");
                var cStatus = $(this).prop('checked');
                var cont = 0;
                //console.log("Click en mi checkbox: "+cId+" - "+cZprograma_id+" - "+cStatus);
                
                if(cZprograma_id === 'null'){
                    $('input[type=checkbox]').each(function(){ //iterate all listed checkbox items
                        var zprograma_id = $(this).attr("bId");  

                        if ( cId === zprograma_id ){
                            $(this).prop('checked', cStatus);
                        }
                    });  
                    

                }else{
                    $('input[type=checkbox]').each(function(){ //iterate all listed checkbox items
                        //var id = $(this).val(); 
                        var zprograma_id = $(this).attr("bId");   
                        var status = $(this).prop('checked'); 
                        //console.log(status + " - "+zprograma_id);
                        if (status === true && zprograma_id != 'null'){
                            cont++;
                        }
                    });
                    //console.log("CONTADOR ACTIVOS: "+cont+ " HIJO: "+cId+ " PADRE: "+cZprograma_id);
                    
                    if (cont > 0){
                        $('input[type=checkbox][value='+cZprograma_id+']').prop('checked', true);
                    }else{
                        $('input[type=checkbox][value='+cZprograma_id+']').prop('checked', cStatus);
                    }
                    

                } 

            });
            
            //this.render();
        },
        render: function() {
            console.log("Actualizando Vista Frm");            
        }
    }
	octopusPerfiles.init();
}());