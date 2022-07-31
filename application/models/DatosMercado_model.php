<?php

class DatosMercado_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $datosMercado;

    
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    

    
    
    public function saveDatosMercado(){
        
        $datosMercado = R::load('datosmercado', $this->id);
        
        $datosMercado->nombre = $this->nombre;
        $datosMercado->input = $this->input;
        $datosMercado->fechaActualizacion = $this->fechaActualizacion;
        
        
        $this->id = R::store($datosMercado);
        return $this->id;
    }
    
    
    
    public function getFechaActualizacion(){
//        $fechaActualizacion = R::getAll('SELECT DISTINCT fechaActualizacion from estructurabono where bono = ? ', array($this->bono));   
        $fechaActualizacion = R::getAll('SELECT DISTINCT fechaActualizacion from datosmercado');        

        return $fechaActualizacion;
    }
    
    
    public function grillaDatosMercadoFecha(){

        $sql = "SELECT 
                id,
                nombre,
                input
                FROM datosmercado
                WHERE fechaActualizacion = ?
                ORDER BY id"; 
        
        $resultado = R::getAll($sql, array($this->fechaActualizacion));

        return $resultado;
    }
    
    
//    public function getDatosMercado(){
//        $datosMercado = R::load('datosMercado', $this->id);
//        return $datosMercado->export();
//    }
//    
//    public function getDatosMercadoId(){
//        $datosMercado = $this->datosMercado;    
//        $sql = "select id from datosMercado WHERE nombre = '{$datosMercado}' "; 
//        $datosMercado = R::getRow($sql);         
//        return $datosMercado;
//        
//    }
//    
//    
//    public function getDatosMercadosUsuario(){
//        $datosMercados = R::getCol('select datosMercado_id from datosMercado_usuario where usuario_id = ?', array($this->usuario_id));
//        return $datosMercados;
//    }
//    
//    public function getDatosMercados(){
//        $datosMercados = R::getAll('select * from datosMercado order by nombre');
//        return $datosMercados;
//    }
//    
//    
//    public function getDatosMercadosDatosMercados(){
////        $datosMercados = R::getAll('SELECT * FROM datosMercado WHERE libro = {$this->datosMercado}');
//        
//        $this->datosMercado = 'BONOS.xlsm';           
//        $sql = "select * from datosMercado WHERE libro = '{$this->datosMercado}' order by nombre";
//        $datosMercados = R::getAll($sql); 
//        return $datosMercados;
//    }
//    
//    public function getDatosMercadosProvinciales(){
////        $datosMercados = R::getAll('SELECT * FROM datosMercado WHERE libro = {$this->datosMercado}');
//        
//        $this->datosMercado = 'provinciales.xlsm';           
//        $sql = "select * from datosMercado WHERE libro = '{$this->datosMercado}' order by nombre";
//        $datosMercados = R::getAll($sql); 
//        return $datosMercados;
//    }
//    
//    
//
//    public function getCodigoCaja(){
//        $datosMercado = $this->datosMercado;    
//        $sql = "select codigocaja from datosMercado WHERE id = {$this->datosMercado}"; 
//        $datosMercados = R::getAll($sql);       
//        
//        //foreach ($datosMercados as $datosMercado) {
//        //    $b = $datosMercado['codigocaja'];
//        //}
//                
//        return $datosMercados;
//        
//    }
//
//    public function getAll(){
//
//        $buscador = $this->buscador;
//        $emisor_id = $this->emisor_id;
//        $tipodatosMercado_id = $this->tipodatosMercado_id;
//               
//        $sql = "select * from datosMercado WHERE 1 = 1"; 
//        
//        if(($emisor_id > 0) && ($tipodatosMercado_id > 0) && ($buscador != '')){
//            $sql.=" AND nombre LIKE '%{$this->buscador}%' AND (emisor_id = {$this->emisor_id} AND tipodatosMercado_id = {$this->tipodatosMercado_id})  ";
//        }elseif(($emisor_id > 0) && ($tipodatosMercado_id > 0)){
//            $sql.=" AND emisor_id = {$this->emisor_id} AND tipodatosMercado_id = {$this->tipodatosMercado_id}";
//        
//            
//        }elseif (($buscador > 0) && ($emisor_id > 0) && ($tipodatosMercado_id == '')){
//            $sql.=" AND nombre LIKE '%{$this->buscador}%' AND emisor_id = {$this->emisor_id} ";
//        }elseif (($buscador > 0) && ($emisor_id == '') && ($tipodatosMercado_id > 0)){
//            $sql.=" AND nombre LIKE '%{$this->buscador}%' AND tipodatosMercado_id = {$this->tipodatosMercado_id} ";
//        }
//        
//        elseif ($buscador > 0){
//            $sql.=" AND nombre LIKE '%{$this->buscador}%' ";
//        }elseif($emisor_id > 0){
//            $sql.=" AND emisor_id = {$this->emisor_id}";
//        }elseif ($tipodatosMercado_id > 0){
//            $sql.=" AND tipodatosMercado_id = {$this->tipodatosMercado_id}";
//        }
//        
//        $datosMercados = R::getAll($sql);
//                
//        return $datosMercados;
//    }
    
    public function delDatosMercado(){
        $datosMercado = R::load('datosmercado', $this->id);
        R::trash($datosMercado);
    }
    
    public function assocUsuario(){
        $datosMercado = R::load('datosmercado', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($datosMercado, $usuario);
    }
    
    public function clearRelMenu(){
        $datosMercado = R::load('datosmercado', $this->id);
        R::clearRelations($datosMercado, 'menu');
    }
    
    public function assocMenu(){
        $datosMercado = R::load('datosmercado', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($datosMercado, $menu);
    }
    
    public function clearRelControlador(){
        $datosMercado = R::load('datosmercado', $this->id);
        R::clearRelations($datosMercado, 'controlador');
    }
    
    public function assocControlador(){
        $datosMercado = R::load('datosmercado', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($datosMercado, $controlador);
    }

}