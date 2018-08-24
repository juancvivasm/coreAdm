<?
require_once 'model/conectar.php';

class Perfil extends Conectar{
	private $conectar;
    protected $table = 'zperfiles';

	public $id;
    public $perfil;
    public $programas = array();

    public function __CONSTRUCT(){
    	try{ 
    		$this->pdo = (new Conectar())->conexion(); 
    	}catch(Exception $e){
    		die($e->getMessage());
		}
	}

    public function obtenerProgramas($aId){
        try {
            $stm = $this->pdo->prepare("SELECT m.id, m.zprograma_id, m.programa, m.orden, m.icono, m.archivo 
                                        FROM zperfil_programas AS a
                                        INNER JOIN zprogramas AS m ON (a.zprograma_id = m.id)
                    WHERE a.zperfil_id = ? AND m.zprograma_id IS NULL ORDER BY m.orden ASC");
            $stm->execute(array($aId));
            //$menuPrincipal = $stm->fetch(PDO::FETCH_OBJ);
            $menuPrincipal = $stm->fetchAll(PDO::FETCH_OBJ);
            //echo "@JC MyClass::iterateVisible:\n";
            foreach ($menuPrincipal as $key => $value) {
                //print_r($value);
                //echo $value->id;
                $stm = $this->pdo->prepare("SELECT a.id, a.zprograma_id, a.programa, a.orden, 
                                                    a.icono, a.archivo 
                                            FROM zprogramas AS a
                                            INNER JOIN zperfil_programas AS p ON (a.id = p.zprograma_id)
                                            WHERE p.zperfil_id = ? AND a.zprograma_id = ? ORDER BY a.orden ASC");
                    $stm->execute(array($aId, $value->id));
                    //$menuSegundoNivel = $stm->fetch(PDO::FETCH_OBJ);
                    $menuSegundoNivel = $stm->fetchAll(PDO::FETCH_OBJ);

                    if($menuSegundoNivel){
                        $value->menuSecundario = $menuSegundoNivel;
                    }
            }
            
            return $menuPrincipal;
            
        }catch (Exception $e) {
            die($e->getMessage());
        }

    } 

    public function agregarPerfil(Perfil $data){        
        try{
            $this->pdo->beginTransaction();
            $sql = "INSERT INTO $this->table (perfil) VALUES (?)"; 

            $this->pdo->prepare($sql)->execute(array(
                    $data->perfil)
            );
            
            $insertId = $this->pdo->lastInsertId();

            $sql = "INSERT INTO zperfil_programas (zperfil_id, zprograma_id) 
                    VALUES (?, ?)"; 

            for ($i=0; $i < count($data->programas); $i++) {
                //echo "DETALLE ==> PROG_ID: ".$data->programas[$i]."\n";
                $this->pdo->prepare($sql)->execute(array($insertId, $data->programas[$i]));
            }

            return $this->pdo->commit();

        }catch (Exception $e){
            $this->pdo->rollback();
            die($e->getMessage());
        }
    }

    public function obtenerPerfil($aId){
        try {
            $stm = $this->pdo->prepare("SELECT id, perfil FROM $this->table WHERE id = ?");
            $stm->execute(array($aId));
            $perfil = $stm->fetch(PDO::FETCH_OBJ);
                        
            $stm = $this->pdo->prepare("SELECT zprograma_id FROM zperfil_programas
                                        WHERE zperfil_id = ?");
            $stm->execute(array($aId));
            $programas = $stm->fetchAll(PDO::FETCH_OBJ);
            
            $perfil->programas = $programas;
            
            return $perfil;
            
        }catch (Exception $e) {
            die($e->getMessage());
        }

    }

    public function actualizar(Perfil $data){        
        try{
            $this->pdo->beginTransaction();
            $sql = "UPDATE $this->table SET perfil = ? WHERE id = ?"; 
            $this->pdo->prepare($sql)->execute(array(
                    $data->perfil,
                    $data->id)
            );
            
            $sql = "DELETE FROM zperfil_programas WHERE zperfil_id = ?"; 
            $this->pdo->prepare($sql)->execute(array($data->id));

            $sql = "INSERT INTO zperfil_programas (zperfil_id, zprograma_id) 
                    VALUES (?, ?)"; 
            for ($i=0; $i < count($data->programas); $i++) {
                //echo "DETALLE ==> PROG_ID: ".$data->programas[$i]."\n";
                $this->pdo->prepare($sql)->execute(array($data->id, $data->programas[$i]));
            }

            return $this->pdo->commit();

        }catch (Exception $e){
            $this->pdo->rollback();
            die($e->getMessage());
        }
    }

    public function eliminar($aId){ 
        try{
            $this->pdo->beginTransaction();

            $sql = "DELETE FROM zperfil_programas WHERE zperfil_id = ?"; 
            $this->pdo->prepare($sql)->execute(array($aId));

            $sql = "DELETE FROM zperfiles WHERE id = ?"; 
            $this->pdo->prepare($sql)->execute(array($aId));

            return $this->pdo->commit();

        }catch (Exception $e){
            $this->pdo->rollback();
            die($e->getMessage());
        }
    }

}
?>