<?php

class Latam_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $latam;

    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    

    
    
    public function saveLatam(){
        
        $latam = R::load('latam', $this->id);
        
        $latam->instrumento = $this->instrumento;
        $latam->coupon = $this->coupon;
        $latam->price = $this->price;
        $latam->yield = $this->yield;
        $latam->ytm = $this->ytm;
        $latam->duration = $this->duration;
        $latam->bp = $this->bp;
        $latam->fechaActualizacion = $this->fechaActualizacion;       
        
        $this->id = R::store($latam);
        return $this->id;
    }
    
    
    public function getFechaActualizacion(){
//        $fechaActualizacion = R::getAll('SELECT DISTINCT fechaActualizacion from estructurabono where bono = ? ', array($this->bono));   
        $fechaActualizacion = R::getAll('SELECT DISTINCT fechaActualizacion from latam');        

        return $fechaActualizacion;
    }
    
    
        public function grillaLatamFecha(){
        
        
        $sql = "SELECT 
                id,
                instrumento,
                coupon,
                price,
                yield,
                ytm,
                duration,
                bp
                FROM latam
                WHERE fechaActualizacion = ?
                ORDER BY id"; 
        
        $resultado = R::getAll($sql, array($this->fechaActualizacion));

        return $resultado;
    }
    
//    public function getLatam(){
//        $latam = R::load('latam', $this->id);
//        return $latam->export();
//    }
//    
//    public function getLatamId(){
//        $latam = $this->latam;    
//        $sql = "select id from latam WHERE nombre = '{$latam}' "; 
//        $latam = R::getRow($sql);         
//        return $latam;
//        
//    }
//    
//    
//    public function getLatamsUsuario(){
//        $latams = R::getCol('select latam_id from latam_usuario where usuario_id = ?', array($this->usuario_id));
//        return $latams;
//    }
//    
//    public function getLatams(){
//        $latams = R::getAll('select * from latam order by nombre');
//        return $latams;
//    }
//    
//    
//    public function getLatamsLatams(){
////        $latams = R::getAll('SELECT * FROM latam WHERE libro = {$this->latam}');
//        
//        $this->latam = 'BONOS.xlsm';           
//        $sql = "select * from latam WHERE libro = '{$this->latam}' order by nombre";
//        $latams = R::getAll($sql); 
//        return $latams;
//    }
//    
//    public function getLatamsProvinciales(){
////        $latams = R::getAll('SELECT * FROM latam WHERE libro = {$this->latam}');
//        
//        $this->latam = 'provinciales.xlsm';           
//        $sql = "select * from latam WHERE libro = '{$this->latam}' order by nombre";
//        $latams = R::getAll($sql); 
//        return $latams;
//    }
//    
//    
//
//    public function getCodigoCaja(){
//        $latam = $this->latam;    
//        $sql = "select codigocaja from latam WHERE id = {$this->latam}"; 
//        $latams = R::getAll($sql);       
//        
//        //foreach ($latams as $latam) {
//        //    $b = $latam['codigocaja'];
//        //}
//                
//        return $latams;
//        
//    }
//
//    public function getAll(){
//
//        $buscador = $this->buscador;
//        $emisor_id = $this->emisor_id;
//        $tipolatam_id = $this->tipolatam_id;
//               
//        $sql = "select * from latam WHERE 1 = 1"; 
//        
//        if(($emisor_id > 0) && ($tipolatam_id > 0) && ($buscador != '')){
//            $sql.=" AND nombre LIKE '%{$this->buscador}%' AND (emisor_id = {$this->emisor_id} AND tipolatam_id = {$this->tipolatam_id})  ";
//        }elseif(($emisor_id > 0) && ($tipolatam_id > 0)){
//            $sql.=" AND emisor_id = {$this->emisor_id} AND tipolatam_id = {$this->tipolatam_id}";
//        
//            
//        }elseif (($buscador > 0) && ($emisor_id > 0) && ($tipolatam_id == '')){
//            $sql.=" AND nombre LIKE '%{$this->buscador}%' AND emisor_id = {$this->emisor_id} ";
//        }elseif (($buscador > 0) && ($emisor_id == '') && ($tipolatam_id > 0)){
//            $sql.=" AND nombre LIKE '%{$this->buscador}%' AND tipolatam_id = {$this->tipolatam_id} ";
//        }
//        
//        elseif ($buscador > 0){
//            $sql.=" AND nombre LIKE '%{$this->buscador}%' ";
//        }elseif($emisor_id > 0){
//            $sql.=" AND emisor_id = {$this->emisor_id}";
//        }elseif ($tipolatam_id > 0){
//            $sql.=" AND tipolatam_id = {$this->tipolatam_id}";
//        }
//        
//        $latams = R::getAll($sql);
//                
//        return $latams;
//    }
    
    public function delLatam(){
        $latam = R::load('latam', $this->id);
        R::trash($latam);
    }
    
    public function assocUsuario(){
        $latam = R::load('latam', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($latam, $usuario);
    }
    
    public function clearRelMenu(){
        $latam = R::load('latam', $this->id);
        R::clearRelations($latam, 'menu');
    }
    
    public function assocMenu(){
        $latam = R::load('latam', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($latam, $menu);
    }
    
    public function clearRelControlador(){
        $latam = R::load('latam', $this->id);
        R::clearRelations($latam, 'controlador');
    }
    
    public function assocControlador(){
        $latam = R::load('latam', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($latam, $controlador);
    }

}