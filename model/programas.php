<?
require_once 'model/conectar.php';

class Programa extends Conectar{
	private $conectar;
    protected $table = 'zprogramas';

	public $id;
    public $zprograma_id;
    public $orden;
    public $programa;
    public $icono;
    public $archivo;

    public function __CONSTRUCT(){
    	try{ 
    		$this->pdo = (new Conectar())->conexion(); 
    	}catch(Exception $e){
    		die($e->getMessage());
		}
	}

    public function getProgramas($aCon = '',$aOrder = '',$aLimit = ''){
        try{
            /*
            $sql = "SELECT COALESCE(a.zprograma_id, a.id) AS disposicion, a.id, a.zprograma_id, a.programa, a.orden, b.programa AS subprograma, a.archivo 
                    FROM zprogramas AS a
                    LEFT JOIN zprogramas AS b ON (a.zprograma_id = b.id)
                    WHERE 1 {$aCon} {$aOrder} {$aLimit}";
            */
            $sql = "SELECT COALESCE((SELECT orden FROM zprogramas WHERE id = COALESCE(a.zprograma_id, a.id)), a.id) AS disposicion, a.id, a.zprograma_id, a.programa, a.orden, b.programa AS subprograma, a.archivo 
                    FROM zprogramas AS a
                    LEFT JOIN zprogramas AS b ON (a.zprograma_id = b.id)
                    WHERE 1 {$aCon} {$aOrder} {$aLimit}";
            //echo $sql;
            $stm = $this->pdo->prepare($sql);
            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_OBJ);

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function agregar(Programa $data){
        try{
            $sql = "INSERT INTO $this->table (zprograma_id, orden, programa, icono, archivo) 
                    VALUES (?, ?, ?, ?, ?)"; 

            return $this->pdo->prepare($sql)->execute(array(
                    $data->zprograma_id,
                    $data->orden,
                    $data->programa,
                    $data->icono,
                    $data->archivo)
            );

        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    public function actualizar(Programa $data){
        try{
            $sql = "UPDATE $this->table SET     zprograma_id = ?,
                                                orden = ?,
                                                programa = ?,
                                                icono = ?,
                                                archivo = ?
                    WHERE id = ?";

            return $this->pdo->prepare($sql)->execute(array(
                    $data->zprograma_id,
                    $data->orden,
                    $data->programa,
                    $data->icono,
                    $data->archivo,
                    $data->id)
            );
        }catch (Exception $e){
            die($e->getMessage());
        }
    }
}
?>