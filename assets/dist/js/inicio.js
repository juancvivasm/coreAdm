$(function() {
	var octopusMenu = {
        init: function() {
        	//console.log("Iniciando Controlador");
            viewMenu.init();
        },
        getProgramas: function() {
            $.ajax({
                url:"?m=buscaProgramas",
                method:"POST",
                dataType:"json",
                //async: false, 
                success:function(data){
                    viewMenu.render(data);
                }
            });
        }
    };

    var viewMenu = {
        init: function() {
            //console.log("Iniciando Vista");
            this.$listaOpciones = $('#side-menu');

            octopusMenu.getProgramas();
            //this.render();
        },

        render: function(opciones) {
            //console.log("Actualizando Vista");
            var navOpc;
            var $listaOpciones = this.$listaOpciones;
            
            $listaOpciones.html('');
            $listaOpciones.metisMenu();
            $listaOpciones.metisMenu('dispose');
            
            navOpc = '<li class="sidebar-search">&nbsp;</li>';
            navOpc += '<li><a href="?m=inicio" id="prg0"><i class="fa fa-dashboard fa-fw"></i> Inicio</a></li>';
            $listaOpciones.append(navOpc);

            //console.log(opciones);
            //console.log(opciones.length);
            for (var i = 0; i < opciones.length; i++) {
                navOpc = null;
                //console.log(opciones[i].id+" - "+opciones[i].programa+" - "+opciones[i].icono);
                if (opciones[i].menuSecundario){
                    navOpc = '<li>';
                    navOpc += '<a href="#"><i class="'+opciones[i].icono+' fa-fw"></i> '+opciones[i].programa+'<span class="fa arrow"></span></a>';
                    navOpc += '<ul class="nav nav-second-level">';
                    //console.log(opciones[i].menuSecundario.length);
                    for (var j = 0; j < opciones[i].menuSecundario.length; j++) {
                        //console.log(opciones[i].menuSecundario[j].programa);
                        navOpc += '<li>';
                        navOpc += '<a href="?m=inicio&p='+opciones[i].menuSecundario[j].id+'" id="prg'+opciones[i].menuSecundario[j].id+'">'+opciones[i].menuSecundario[j].programa+'</a>';
                        navOpc += '</li>';
                    }
                    navOpc += '</ul>';
                    navOpc += '</li>';
                    
                }else{
                    navOpc = '<li>';
                    navOpc += '<a href="?m=inicio&p='+opciones[i].id+'" id="prg'+opciones[i].menuSecundario[j].id+'"><i class="'+opciones[i].icono+' fa-fw"></i> '+opciones[i].programa+'</a>';
                    navOpc += '</li>';
                }
                $listaOpciones.append(navOpc);
            }
            //console.log("Pagina: "+pagAct);
            
            $listaOpciones.metisMenu();
            $("#prg"+prgAct).addClass( "active" );

        }
    };
	octopusMenu.init();
}());