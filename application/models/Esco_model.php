<?php
class Esco_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        if (defined('ENVIRONMENT')) {

            switch (ENVIRONMENT) {
                case 'javierdev':
                    $this->dbh = new PDO ("sqlsrv:server=srv-vbolsa0;database=VBolsa","sa","25DeMayo");
                    break;
                case 'desajavier':
                    
                case 'produccion':
                    $this->dbh = new PDO("dblib:host=srv-vbolsa0.allaria.local;dbname=VBolsa", "sa", "25DeMayo");
                    break;
                default:
                    exit('The application environment is not set correctly.');
            }
        }
    }
    
    private $dbh;
    public $numComitente;
    public $in;
    
    public function getComitente(){
        $sql = "select  c.Descripcion comitente,
                        c.EsFisico esFisico,
                        o.Apellido + ', ' + o.Nombre as oficial,
                        ISNULL(jur.CUIT, isnull(p.CUIL, p.CUIT)) as cuit,
                        c.NoPresencial noPresencial
                from    COMITENTES c
                join    OPERATIVOSROLCMT orc
                on      orc.CodComitente = c.CodComitente
                join    OPERATIVOS o
                on      o.CodOperativo   = orc.CodOperativo
                and     orc.CodRol       = 'OC'
                left outer join CONDOMINIOS con
                on      con.CodComitente = c.CodComitente
                join    PERSONAS p
                on      p.CodPersona     = con.CodPersona
                left outer join CMTJURIDICOS jur
                on      c.CodComitente   = jur.CodComitente
                where   c.NumComitente   = {$this->numComitente}
                and     con.CodTpCondominio = 'TI'
                and     con.EstaAnulado     = 0
                and     c.EstaAnulado       = 0
                order by con.Posicion";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetch(PDO::FETCH_ASSOC);
        unset($stmt);
        if ($results){
            return $this->utf8_converter($results);
        } else {
            return false;
        }
        
    }
    
    public function getComitentesPresencialesMalCargados(){
        $sql = "select NumComitente
                from   COMITENTES
                where  NumComitente in ({$this->in})
                and    NoPresencial <> 0
                ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt);
        if ($results){
            return array_column($results, 'NumComitente');
        } else {
            return [];
        }
                
    }
    
    public function getComitentesNoPresencialesMalCargados(){
        $sql = "select NumComitente
                from   COMITENTES
                where  NumComitente in ({$this->in})
                and    NoPresencial <> -1
                ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt);
        if ($results){
            return array_column($results, 'NumComitente');
        } else {
            return [];
        }
    }
    
    
    
    function utf8_converter($array){
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });

        return $array;
    }
}
