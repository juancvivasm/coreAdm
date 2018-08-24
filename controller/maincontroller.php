<?
class MainController{
	public function __CONSTRUCT(){
		session_start();
		require_once "assets/libraries/php/lib.funciones.php";
	}

	public function muestraLogin(){
		$state = random_alphanumeric_string(32);
		$_SESSION['state'] = $state;
		require_once 'view/login.php';
	}

	public function connect(){
		//echo json_encode($_REQUEST);
		require_once 'model/usuarios.php';
		
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['state'] != $_POST["state"] ){
			$aError = "Estado Inválido";
			goto end;		
		}else{
			$usuario = new Usuario;
			$datos = $usuario->obtenerPor('usuario', $_POST["username"]); 
			//print_r($datos);
			if($datos){
				$usuario_id		= $datos[0]->id;
				$user			= $datos[0]->usuario;
				$num_intentos	= $datos[0]->intentos;
				$bool_bloqueado = $datos[0]->bloqueado;
				$clave			= $datos[0]->clave;
				$password = md5( $_POST['inputPassword'] );

				if ($bool_bloqueado=='1') {
					$aError = "El usuario ha sido bloqueado por seguridad, comuniquese con el Administrador";
					goto end;	
				}
				if ($password != $clave) {
					$aError = "Usuario o Contraseña Incorrecta";
					goto end;		
				}
				// Paranoia: destruimos las variables login y password usadas
				unset($user);
				unset ($clave);
				unset ($password);

				$_SESSION['usuario_id'] 	= $datos[0]->id;
				$_SESSION['zperfil_id'] 	= $datos[0]->zperfil_id;
				$_SESSION['usuario'] 		= $datos[0]->usuario;
				$_SESSION['username'] 		= $datos[0]->nombres." ".$datos[0]->apellidos;
				$aMsg = $_SESSION['username'];

			}else{
				$aError = "Error en Usuario";	
			}

		}
		end:	
		$output = array(
			"error" => $aError,
			"aMsg" => $aMsg
			);
		echo json_encode($output);
		
	}

	public function disconnect(){
		// Finally, destroy the session. 
		session_destroy();
		header('Location: index.php');
		exit;
	}

	public function inicio(){
		//print_r($_REQUEST);
		//echo $_REQUEST['p'];
		if ( $_SESSION['username'] ){
			if ($_REQUEST['p']){
				require_once 'model/programas.php';
				$programa = new Programa;
				$prgDatos = $programa->obtener($_REQUEST['p']);	
				$filename = 'view/'.$prgDatos->archivo;
				$prgAct = $prgDatos->id;
				if (file_exists($filename)) {
				    require_once $filename;
				} else {
				    require_once 'view/blank.php';
				}

			}else{
				$prgAct = 0;
				require_once 'view/inicio.php';
			}
		}else{
			header('Location: ?m=muestraLogin');
		}
	}

	public function buscaProgramas(){
		//echo json_encode($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/perfil.php';
			$perfil = new Perfil;
			$programas = $perfil->obtenerProgramas( $_SESSION['zperfil_id'] );

			echo json_encode($programas);
		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}


	/*
	 *****************************************************************************
	 * Programas
	 *****************************************************************************
	 */
	public function muestraProgramas(){
		//echo json_encode($_REQUEST);
		//print_r($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/programas.php';
			$programa = new Programa;
			$recordsTotal = $programa->countRow();
			$fil = "";
			//$ord = "ORDER BY disposicion, a.zprograma_id, a.orden ASC";
			//$ord = "ORDER BY disposicion, a.orden ASC";
			$ord = "ORDER BY disposicion, a.zprograma_id IS NOT NULL, a.orden";
			$lim = "";

			if($_POST["search"]["value"]!=""){
			 	$fil .= "AND a.programa LIKE '%".$_POST["search"]["value"]."%' ";
			 	$fil .= "OR b.programa LIKE '%".$_POST["search"]["value"]."%' ";
			 	$fil .= "OR a.archivo LIKE '%".$_POST["search"]["value"]."%' ";
			}

			if($_POST["length"] != -1){
				$lim .= "LIMIT " . $_POST['start'] . ", " . $_POST['length'];
			}

			//$aRows = $aClassDb->getProgramas($fil,$ord,$lim);
			$programas = $programa->getProgramas($fil,$ord,$lim);
			$recordsFiltered = count($programas);

			$output = array(
				"draw" => intval($_POST["draw"]),
		  	    "recordsTotal" => (int)$recordsFiltered,
				"recordsFiltered" => (int)$recordsTotal,
				"data"    => $programas
			);
			echo json_encode($output);

		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function buscaModulos(){
		//echo json_encode($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/programas.php';
			$programa = new Programa;
			$fil = "";
			$fil .= "AND a.zprograma_id IS NULL";
			$modulos = $programa->getProgramas($fil);

			echo json_encode($modulos);
		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function addPrograma(){
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			require_once 'model/programas.php';
			$programa = new Programa;
		    $programa->zprograma_id = $_REQUEST['zprograma_id'];
		    $programa->orden = ( $_REQUEST['orden'] != '' ) ?  $_REQUEST['orden'] : NULL;
		    $programa->programa = $_REQUEST['des_pro'];
		    $programa->icono = $_REQUEST['icono'];
		    $programa->archivo = $_REQUEST['archivo'];

		    $rs = $programa->agregar($programa);
		    if($rs){		    	
		    	$aMsg = "El Programa ha sido registrado";
		    }else{
		    	$aError = "Error";
		    	$aMsg = "Error al registrar el Programa";
		    }

		    $output = array(
				"error" => $aError,
				"aMsg" => $aMsg
			);
			echo json_encode($output);

		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function buscaPrograma(){
		//echo json_encode($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/programas.php';
			$programas = new Programa;
			$programa = $programas->obtener($_REQUEST['programa_id']);

			echo json_encode($programa);
		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function actualizaPrograma(){
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			require_once 'model/programas.php';
			$programa = new Programa;
		    $programa->zprograma_id = $_REQUEST['zprograma_id'];
		    $programa->orden = ( $_REQUEST['orden'] != '' ) ?  $_REQUEST['orden'] : NULL;
		    $programa->programa = $_REQUEST['des_pro'];
		    $programa->icono = $_REQUEST['icono'];
		    $programa->archivo = $_REQUEST['archivo'];
		    $programa->id = $_REQUEST['programa_id'];

		    $rs = $programa->actualizar($programa);
		    if($rs){		    	
		    	$aMsg = "El registro ha sido actualizado";
		    }else{
		    	$aError = "Error";
		    	$aMsg = "Error al actualizar";
		    }

		    $output = array(
				"error" => $aError,
				"aMsg" => $aMsg
			);
			echo json_encode($output);

		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function eliminarPrograma(){
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			require_once 'model/programas.php';	
			$programa = new Programa;
		
			$rs = $programa->eliminar($_REQUEST['programa_id']);
			if($rs){
			   	$aError = "";
			}else{
			    $aError = "Error";
			}
			$output = array(
				"error" => $aError,
			);
			echo json_encode($output);
		}else{
			header('Location: ?m=muestraLogin');
		}
	}

	public function verProgramas(){
		//echo json_encode($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/programas.php';
			$fil = "";
			$ord = "ORDER BY disposicion, a.zprograma_id IS NOT NULL, a.orden";
			$lim = "";
			$programa = new Programa;
			$programas = $programa->getProgramas($fil, $ord, $lim);

			echo json_encode($programas);
		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}


	/*
	 *****************************************************************************
	 * Usuarios
	 *****************************************************************************
	 */
	public function muestraUsuarios(){
		//echo json_encode($_REQUEST);
		//print_r($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/usuarios.php';
			$usuario = new Usuario;
			$recordsTotal = $usuario->countRow();
			$fil = "";
			$ord = "ORDER BY a.apellidos";
			$lim = "";

			if($_POST["search"]["value"]!=""){
			 	$fil .= "a.usuario LIKE '%".$_POST["search"]["value"]."%' ";
		 		$fil .= "OR a.cedula LIKE '%".$_POST["search"]["value"]."%' ";
				$fil .= "OR a.nombres LIKE '%".$_POST["search"]["value"]."%' ";
				$fil .= "OR a.apellidos LIKE '%".$_POST["search"]["value"]."%' ";
			}

			if($_POST["length"] != -1){
				$lim .= "LIMIT " . $_POST['start'] . ", " . $_POST['length'];
			}

			//$aRows = $aClassDb->getProgramas($fil,$ord,$lim);
			$usuarios = $usuario->getUsuarios($fil,$ord,$lim);
			$recordsFiltered = count($usuarios);

			$output = array(
				"draw" => intval($_POST["draw"]),
		  	    "recordsTotal" => (int)$recordsFiltered,
				"recordsFiltered" => (int)$recordsTotal,
				"data"    => $usuarios
			);
			echo json_encode($output);

		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function buscaPerfiles(){
		//echo json_encode($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/perfil.php';
			$perfil = new Perfil;
			$perfiles = $perfil->listar();

			echo json_encode($perfiles);
		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function addUser(){
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			require_once 'model/usuarios.php';
			$usuario = new Usuario;

			$aCon = "AND usuario = '".trim( $_REQUEST['usuario'] )."'";	
			$aCheck = $usuario->countRow($aCon);
			if($aCheck > 0){
				$aError = "Disculpe, ya existe un nombre de usuario igual!";
			}else{
			    $usuario->zusuario_id 	= $_SESSION['usuario_id'];
			    $usuario->zorganismo_id = NULL;
			    $usuario->zperfil_id 	= $_REQUEST['zperfil_id'];
			    $usuario->usuario 		= trim( $_REQUEST['usuario'] );
			    $usuario->clave 		= md5($_REQUEST['clave']);
			    //$usuario->fechacreacion = "NOW()";
			    $usuario->intentos 		= 0;
			    $usuario->bloqueado 	= $_REQUEST['bloqueado'];
			    $usuario->nombres 		= $_REQUEST['nombres'];
			    $usuario->apellidos 	= $_REQUEST['apellidos'];
			    $usuario->cedula 		= $_REQUEST['cedula'];
			    $usuario->direccion 	= $_REQUEST['direccion'];
			    $usuario->telefonos 	= $_REQUEST['telefono'];
			    //$usuario->ultimavisita 	= NULL;

			    $rs = $usuario->agregar($usuario);
			    if($rs){		    	
			    	$aMsg = "El Usuario ha sido registrado";
			    }else{
			    	$aError = "Error";
			    	$aMsg = "Error al registrar el Usuario";
			    }
			}

		    $output = array(
				"error" => $aError,
				"aMsg" => $aMsg
			);
			echo json_encode($output);

		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function buscaUsuario(){
		//echo json_encode($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/usuarios.php';
			$usuarios = new Usuario;
			$usuario = $usuarios->obtener($_REQUEST['usuario_id']);

			echo json_encode($usuario);
		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function actualizaUsuario(){
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			require_once 'model/usuarios.php';
			$usuario = new Usuario;

			$aCon = "AND usuario = '".trim( $_REQUEST['usuario'] )."' AND id <> ".$_REQUEST['usuario_id'];	
			$aCheck = $usuario->countRow($aCon);
			if($aCheck > 0){
				$aError = "Disculpe, ya existe un nombre de usuario igual!";
			}else{
			    $usuario->zusuario_id 	= $_SESSION['usuario_id'];
			    $usuario->zorganismo_id = NULL;
			    $usuario->zperfil_id 	= $_REQUEST['zperfil_id'];
			    $usuario->usuario 		= trim( $_REQUEST['usuario'] );
			    if( strlen($_POST['clave']) != 32){
			    	$usuario->clave 		= md5($_REQUEST['clave']);
			    }
			    $usuario->bloqueado 	= $_REQUEST['bloqueado'];
			    $usuario->nombres 		= $_REQUEST['nombres'];
			    $usuario->apellidos 	= $_REQUEST['apellidos'];
			    $usuario->cedula 		= $_REQUEST['cedula'];
			    $usuario->direccion 	= $_REQUEST['direccion'];
			    $usuario->telefonos 	= $_REQUEST['telefono'];
			    $usuario->id 			= $_REQUEST['usuario_id'];

			    $rs = $usuario->actualizar($usuario);
			    if($rs){		    	
			    	$aMsg = "El registro ha sido actualizado";
			    }else{
			    	$aError = "Error";
			    	$aMsg = "Error al actualizar";
			    }
			}

		    $output = array(
				"error" => $aError,
				"aMsg" => $aMsg
			);
			echo json_encode($output);

		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function eliminarUsuario(){
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			if ($_SESSION['usuario_id'] <> $_REQUEST['usuario_id']){
				require_once 'model/usuarios.php';	
				$usuario = new Usuario;
			
				$rs = $usuario->eliminar($_REQUEST['usuario_id']);
				if($rs){
				   	$aError = "";
				}else{
				    $aError = "Error";
				}
			}else{
				$aError = "Disculpe, no debe eliminar su usuario!";
			}
			$output = array(
					"error" => $aError,
				);
			echo json_encode($output);
		}else{
			header('Location: ?m=muestraLogin');
		}
	}


	/*
	 *****************************************************************************
	 * Cambiar Contrasena
	 *****************************************************************************
	 */
	public function actualizaClave(){
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			require_once 'model/usuarios.php';
			$usuario = new Usuario;

			$aCon = "AND id = ".$_SESSION['usuario_id']." AND clave = '".md5($_REQUEST['claveActual'])."'";	
			$aCheck = $usuario->countRow($aCon);			
			if($aCheck > 0){
			    $usuario->clave = md5($_REQUEST['claveNueva']);
			    $usuario->id = $_SESSION['usuario_id'];

			    $rs = $usuario->actualizarClave($usuario);
			    if($rs){		    	
			    	$aMsg = "Su contraseña ha sido cambiada";
			    }else{
			    	$aError = "Error";
			    	$aMsg = "Error al actualizar su contraseña";
			    }
			}else{
				$aError = "Su contraseña actual no coincide!";
			}

		    $output = array(
				"error" => $aError,
				"aMsg" => $aMsg
			);
			echo json_encode($output);

		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}


	/*
	 *****************************************************************************
	 * Perfiles
	 *****************************************************************************
	 */
	public function muestraPerfiles(){
		//echo json_encode($_REQUEST);
		//print_r($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/perfil.php';
			$perfil = new Perfil;
			$recordsTotal = $perfil->countRow();
			$fil = "";
			$ord = "ORDER BY perfil";
			$lim = "";

			if($_POST["search"]["value"]!=""){
			 	$fil .= "perfil LIKE '%".$_POST["search"]["value"]."%' ";
			}

			if($_POST["length"] != -1){
				$lim .= "LIMIT " . $_POST['start'] . ", " . $_POST['length'];
			}

			//$aRows = $aClassDb->getProgramas($fil,$ord,$lim);
			$perfiles = $perfil->getAllData($fil,$ord,$lim);
			$recordsFiltered = count($perfiles);

			$output = array(
				"draw" => intval($_POST["draw"]),
		  	    "recordsTotal" => (int)$recordsFiltered,
				"recordsFiltered" => (int)$recordsTotal,
				"data"    => $perfiles
			);
			echo json_encode($output);

		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function addPerfil(){
		//echo print_r($_REQUEST);
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			require_once 'model/perfil.php';
			$perfil = new Perfil;

			$perfil->perfil = $_REQUEST['perfil'];
			$perfil->programas = $_REQUEST['prog'];
			
			$rs = $perfil->agregarPerfil($perfil);
			if($rs){		    	
				$aMsg = "El Perfil ha sido registrado";
			}else{
				$aError = "Error";
				$aMsg = "Error al registrar el Perfil";
			}

		    $output = array(
				"error" => $aError,
				"aMsg" => $aMsg
			);
			echo json_encode($output);
			
		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function buscaPerfil(){
		//echo json_encode($_REQUEST);
		if ( $_SESSION['username'] ){
			require_once 'model/perfil.php';
			$perfiles = new Perfil;
			$perfil = $perfiles->obtenerPerfil($_REQUEST['perfil_id']);

			echo json_encode($perfil);
		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function actualizaPerfil(){
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			require_once 'model/perfil.php';
			$perfil = new Perfil;
		    $perfil->perfil = $_REQUEST['perfil'];
			$perfil->programas = $_REQUEST['prog'];
			$perfil->id = $_REQUEST['perfil_id'];

		    $rs = $perfil->actualizar($perfil);
		    if($rs){		    	
		    	$aMsg = "El registro ha sido actualizado";
		    }else{
		    	$aError = "Error";
		    	$aMsg = "Error al actualizar";
		    }

		    $output = array(
				"error" => $aError,
				"aMsg" => $aMsg
			);
			echo json_encode($output);

		}else{
			header('Location: ?m=muestraLogin');
		}
		
	}

	public function eliminarPerfil(){
		//echo json_encode($_REQUEST);
		$aError = "";
		$aMsg = "";
		if ( $_SESSION['username'] ){
			require_once 'model/perfil.php';	
			$perfil = new Perfil;
		
			$rs = $perfil->eliminar($_REQUEST['perfil_id']);
			if($rs){
			   	$aError = "";
			}else{
			    $aError = "Error";
			}
			$output = array(
				"error" => $aError,
			);
			echo json_encode($output);
		}else{
			header('Location: ?m=muestraLogin');
		}
	}

}
?>