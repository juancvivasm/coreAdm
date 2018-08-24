$(function() {
    var rd;

	var octopusLogin = {
        init: function() {
        	//console.log("Iniciando Controlador");
            viewLogin.init();
        },
        verificaUsuario: function(frm) {
            //console.log( frm.serialize() );
            $.ajax({
                url:"?m=connect",
                method:"POST",
                dataType:"json",
                //async: false, 
                data: frm.serialize(),
                success:function(data){
                    viewLogin.render(data);
                }
            });
        }
    };

    var viewLogin = {
        init: function() {
            //console.log("Iniciando Vista");

            var formSignIn = $('#signin');
            
            formSignIn.submit(function(e){
                octopusLogin.verificaUsuario(formSignIn);
                e.preventDefault();
                
            });
            
            //this.render();
        },

        render: function(datos) {
            //console.log("Actualizando Vista");
            //console.log(datos);
    
            var formSignIn = $('#signin');
            var username = $('#username');
            var inputPassword = $('#inputPassword');

            var modalInfo = $('#informacionModal');

            //console.log("Valor de RD: "+rd);
            modalInfo.on('hidden.bs.modal', function (e) {
                //console.log("Se muestra Informacion del check "+rd);
                if (rd){
                    window.location.href = "?m=inicio";
                }
            });

            if( datos['error']=="" ){
                formSignIn[0].reset();
                rd = 1;
                auxMesg = "Bienvenido "+datos['aMsg'];
            }else{
                username.val("");
                inputPassword.val("");
                auxMesg = datos['error'];
            }
            //console.log(auxMesg+' '+rd);
            modalInfo.find('.modal-body').text(auxMesg);
            modalInfo.modal('show');
            
        }
    };
	octopusLogin.init();
}());