<?php

            
           
        
        
/* 
		if (($emisor_id > 0) && ($tipobono_id == 'null')) {
			$sql = "select * from bono where emisor_id = ?";
            $bonos = R::getAll($sql, array($this->emisor_id));
		} elseif (($emisor_id == 'null') && ($tipobono_id > 0)) {
			$sql = "select * from bono where tipobono_id = ?";
            $bonos = R::getAll($sql, array($this->tipobono_id));
		} elseif (($emisor_id > 0) && ($tipobono_id > 0)) {
			$sql = "select * from bono where tipobono_id = ?";
            $bonos = R::getAll($sql, array($this->tipobono_id));
		} elseif (($emisor_id == 'null') && ($tipobono_id == 'null')){ 
            $sql = "select * from bono";
            $bonos = R::getAll($sql);
        }
*/		
		
		
		
/*    
 * 
 *             
            $sql = "select * from bono where emisor_id = ? AND tipobono_id = ?";
            $bonos = R::getAll($sql, array($this->tipobono_id,$this->emisor_id));
 * 
 *     
        if($emisor_id > 0 and $tipobono_id = 'null'){
            $sql = "select * from bono where emisor_id = ?";
        }else{
            $sql = "select * from bono";
        }
*/        
//        $bonos = R::getAll($sql, array($this->emisor_id));
        


        /*
        if(($emisor_id > 0) && ($tipobono_id == 'null')){
            var_dump($emisor_id);
            var_dump($tipobono_id);
            print_r("Entra a emisor");
            $sql = "select * from bono where emisor_id = ?";
            $bonos = R::getAll($sql, array($this->emisor_id));
        }
        elseif(($emisor_id == 'null') && ($tipobono_id > 0)){
            var_dump($emisor_id);
            var_dump($tipobono_id);
            print_r("Entra a tipobono");
            $sql = "select * from bono where tipobono_id = ?";
            $bonos = R::getAll($sql, array($this->tipobono_id));
        }
        elseif(($emisor_id > 0) && ($tipobono_id > 0)){
            var_dump($emisor_id);
            var_dump($tipobono_id);
            print_r("Entra a los dos");
            $sql = "select * from bono where emisor_id = ? OR tipobono_id = ?";
            $bonos = R::getAll($sql, array($this->emisor_id, $this->tipobono_id));

        }
        elseif(($emisor_id = 'null') && ($tipobono_id = 'null')){ 
            var_dump($emisor_id);
            var_dump($tipobono_id);
            print_r("Entra null");
            $sql = "select * from bono";
            $bonos = R::getAll($sql);
        }elseif(($emisor_id == 0) && ($tipobono_id == 0)){ 
            var_dump($emisor_id);
            var_dump($tipobono_id);
            print_r("Entra 0");
            $sql = "select * from bono";
            $bonos = R::getAll($sql);
        }
*/