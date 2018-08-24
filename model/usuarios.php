<?php
require_once 'model/conectar.php';

class Usuario extends Conectar{
	private $conectar;
    protected $table = 'zusuarios';

	public $id;
    public $zusuario_id;
    public $zorganismo_id;
    public $zperfil_id;
    public $usuario;
    public $clave;
    public $fechacreacion;
    public $intentos;
    public $bloqueado;
    public $nombres;
    public $apellidos;
    public $cedula;
    public $direccion;
    public $telefonos;
    public $ultimavisita;

    public function __CONSTRUCT(){
    	try{ 
    		$this->pdo = (new Conectar())->conexion(); 
    	}catch(Exception $e){
    		die($e->getMessage());
		}
	}

    public function getUsuarios($aCon = '',$aOrder = '',$aLimit = ''){
        try{
            $sql = "SELECT a.id, a.zorganismo_id, a.zperfil_id, b.perfil, a.usuario, a.clave, 
                            a.bloqueado, CONCAT(a.nombres,' ',a.apellidos) AS datos, a.nombres, a.apellidos, 
                            a.cedula, a.direccion, a.telefonos
                    FROM zusuarios AS a
                    LEFT JOIN zperfiles AS b ON (b.id = a.zperfil_id) 
                    {$aCon} {$aOrder} {$aLimit}";
            //echo $sql;
            $stm = $this->pdo->prepare($sql);
            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_OBJ);

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function agregar(Usuario $data){
        try{
            $sql = "INSERT INTO $this->table (zusuario_id, zorganismo_id, zperfil_id, usuario, 
                                                clave, fechacreacion, intentos, bloqueado, 
                                                nombres, apellidos, cedula, direccion, telefonos) 
                    VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)"; 

            return $this->pdo->prepare($sql)->execute(array(
                    $data->zusuario_id,
                    $data->zorganismo_id,
                    $data->zperfil_id,
                    $data->usuario,
                    $data->clave,
                    //$data->fechacreacion,
                    $data->intentos,
                    $data->bloqueado,
                    $data->nombres,
                    $data->apellidos,
                    $data->cedula,
                    $data->direccion,
                    $data->telefonos)
            );

        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    public function actualizar(Usuario $data){
        try{
            $sql = "UPDATE $this->table SET     zusuario_id = ?, 
                                                zorganismo_id = ?, 
                                                zperfil_id = ?, 
                                                usuario = ?, 
                                                clave = IFNULL(?, clave), 
                                                bloqueado = ?, 
                                                nombres = ?, 
                                                apellidos = ?, 
                                                cedula = ?, 
                                                direccion = ?, 
                                                telefonos = ?
                    WHERE id = ?";

            return $this->pdo->prepare($sql)->execute(array(
                    $data->zusuario_id,
                    $data->zorganismo_id,
                    $data->zperfil_id,
                    $data->usuario,
                    $data->clave,
                    $data->bloqueado,
                    $data->nombres,
                    $data->apellidos,
                    $data->cedula,
                    $data->direccion,
                    $data->telefonos,
                    $data->id)
            );
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    public function actualizarClave(Usuario $data){
        try{
            $sql = "UPDATE $this->table SET     clave = ?
                    WHERE id = ?";

            return $this->pdo->prepare($sql)->execute(array(
                    $data->clave,
                    $data->id)
            );
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

}
?>