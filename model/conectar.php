<? 
class Conectar{
	private $driver;
    private $host, $user, $pass, $database, $charset;

    protected $table = null;
  
    public function __construct() {    
    	$db_cfg = require 'config/database.php';
    	
        $this->driver=$db_cfg["driver"];
        $this->host=$db_cfg["host"];
        $this->user=$db_cfg["user"];
        $this->pass=$db_cfg["pass"];
        $this->database=$db_cfg["database"];
        $this->charset=$db_cfg["charset"];
    }

    public function conexion(){    	
        if($this->driver=="mysql" || $this->driver==null){
            $pdo = new PDO('mysql:host='.$this->host.';dbname='.$this->database.';charset='.$this->charset, $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
                
        return $pdo;
    }

    public function countRow($aCon = ''){
        try{
            $aSql = "SELECT COUNT(*) as count FROM $this->table WHERE 1 {$aCon}";

            $stm = $this->pdo->prepare($aSql);
            $stm->execute();

            $rs = $stm->fetch(PDO::FETCH_OBJ);
            return $rs->count;

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function getAllData($aCon = '',$aOrder = '',$aLimit = ''){
        try{
            $aSql = "SELECT * FROM $this->table WHERE 1 {$aCon} {$aOrder} {$aLimit}";

            $stm = $this->pdo->prepare($aSql);
            $stm->execute();
            //echo $aSql."<br>";
            return $stm->fetchAll(PDO::FETCH_OBJ);

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function listar(){
        try{
            $stm = $this->pdo->prepare("SELECT * FROM $this->table");
            $stm->execute();

            return $stm->fetchAll(PDO::FETCH_OBJ);

        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function obtener($aId){
        try {
            $stm = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = ?");
            $stm->execute(array($aId));

            return $stm->fetch(PDO::FETCH_OBJ);

        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function obtenerPor($column,$value){
        try {
            $stm = $this->pdo->prepare("SELECT * FROM $this->table WHERE $column = ?");
            $stm->execute(array($value));

            return $stm->fetchAll(PDO::FETCH_OBJ);

        }catch (Exception $e) {
            die($e->getMessage());
        }

    }

    public function eliminar($aId){ 
        try{
            $stm = $this->pdo->prepare("DELETE FROM $this->table WHERE id = ?");
            return $stm->execute(array($aId));

        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    /**
     * close the database connection
     */
    public function __destruct() {
        // close the database connection
        $this->pdo = null;
    }

}
?>