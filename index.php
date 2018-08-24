<?
require_once "controller/maincontroller.php";

if(!isset($_REQUEST['m'])){
	$controller = new MainController;
	$controller->muestraLogin();
}else{
	$accion = isset($_REQUEST['m']) ? $_REQUEST['m'] : 'inicio';

	$controller = new MainController;
	call_user_func(array( $controller, $accion) );
}
?>