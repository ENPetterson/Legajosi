<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Retail_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function grillaProspectos(){
        switch (ENVIRONMENT) {
            case 'javierdev':
            case 'desajavier':
            case 'test.gestionsgr':
            case 'jleisubuntu':
                R::addDatabase('remoto', 'mysql:host=alta.local;dbname=altaweb', 'root','Cuervo10');
                break;
            default :
                R::addDatabase('remoto', 'mysql:host=alta.allaria.com.ar;dbname=altaalla_datos', 'altaalla_user',';J.f+cBfUrUk');
        }
        
        R::selectDatabase('remoto');
        
        $grilla = R::getAll('select * from retail where id not in (select retail_id from formulario where retail_id is not null)');
        return $grilla;
        
    }
    
}

