<?php

/* 
    public function getAll(){

        $buscador = $this->buscador;
        $emisor_id = $this->emisor_id;
        $tipobono_id = $this->tipobono_id;
               
        $sql = "select * from bono WHERE 1 = 1"; 
        
        if(($emisor_id > 0) && ($tipobono_id > 0) && ($buscador != '')){
            $sql.=" AND emisor_id = {$this->emisor_id} OR tipobono_id = {$this->tipobono_id} OR nombre LIKE '%{$this->buscador}%' ";
        }elseif(($emisor_id > 0) && ($tipobono_id > 0)){
            $sql.=" AND emisor_id = {$this->emisor_id} OR tipobono_id = {$this->tipobono_id}";
        
            
        }elseif (($buscador > 0) && ($emisor_id > 0) && ($tipobono_id == '')){
            $sql.=" AND nombre LIKE '%{$this->buscador}%' OR emisor_id = {$this->emisor_id} ";
        }elseif (($buscador > 0) && ($emisor_id == '') && ($tipobono_id > 0)){
            $sql.=" AND nombre LIKE '%{$this->buscador}%' OR tipobono_id = {$this->tipobono_id} ";
        }
        
        elseif ($buscador > 0){
            $sql.=" AND nombre LIKE '%{$this->buscador}%' ";
        }elseif($emisor_id > 0){
            $sql.=" AND emisor_id = {$this->emisor_id}";
        }elseif ($tipobono_id > 0){
            $sql.=" AND tipobono_id = {$this->tipobono_id}";
        }
        
        $bonos = R::getAll($sql);
        
        //$bonos = R::getAll($sql, array($this->emisor_id, $this->tipobono_id));
        
        return $bonos;
    }
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 *         if(($emisor_id == 0) && ($tipobono_id == 0) || ($emisor_id == 'null') && ($tipobono_id == 'null'))  { 
            $sql = "select * from bono";
            $bonos = R::getAll($sql);
        }elseif (($emisor_id > 0) && ($tipobono_id == '')){
            $sql = "select * from bono where emisor_id = ?";
            $bonos = R::getAll($sql, array($this->emisor_id));
        }elseif (($emisor_id == '') && ($tipobono_id > 0)){
            $sql = "select * from bono where tipobono_id = ?";
            $bonos = R::getAll($sql, array($this->tipobono_id));
        }elseif (($emisor_id > 0) && ($tipobono_id > 0)){
            $sql = "select * from bono where emisor_id = ? OR tipobono_id = ?";
            
        }
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */