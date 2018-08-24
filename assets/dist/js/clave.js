$(function() {
	var octopusClave = {
        init: function() {
        	//console.log("Iniciando Controlador");
            clvFrmView.init();
        },
        modificaClave: function(frm) {
            //console.log( frm.serialize() );
            $.ajax({
                url:"?m=actualizaClave",
                method:"POST",
                dataType:"json",
                //async: false, 
                data: frm.serialize(),
                success:function(data){
                    //console.log(data);
                    var modal = $('#informacionModal'); 
                    
                    if(data['error']===null  || data['error']===""){
                        //console.log("S/E");
                        $('#claveActual').val("");
                        $('#claveNueva').val("");
                        $('#claveConfirmacion').val("");
                        modal.find('.modal-body').text(data['aMsg'])                
                    }else{
                        //console.log("C/E");
                        modal.find('.modal-body').text(data['error'])
                    }
                    $('#informacionModal').modal('show');   
                }
            });
        }
    };

    var clvFrmView = {
        init: function(){
            //console.log("Iniciando Vista Frm");
            var frmClave = $('#frmClave');

            frmClave.submit(function(e){
                //console.log("Envio Frm");
                if ( $('#claveNueva').val() === $('#claveConfirmacion').val() ){
                    octopusClave.modificaClave(frmClave);
                }else{
                    var modal = $('#informacionModal'); 
                    modal.find('.modal-body').text("La contraseña nueva no coincide");
                    $('#informacionModal').modal('show'); 
                }

                e.preventDefault();
                
            });

            //this.render();
        },
        render: function() {
            console.log("Actualizando Vista Frm");            
        }
    }
	octopusClave.init();
}());