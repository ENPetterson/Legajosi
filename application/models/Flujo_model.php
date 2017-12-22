<?php

class Flujo_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $fechapagos;
    public $amortizacion;
    public $interes;
    
    
    public function getFlujos(){
        
        $flujo = $this->bono;   
        $sql = "select * from flujo WHERE bono = (SELECT nombre FROM bono WHERE id = {$this->bono})"; 
        $flujos = R::getAll($sql);  
        //$flujos = R::getAll("select * from flujo where bono = 'Bono1'");
        return $flujos;
    }
    
    public function getFeriados(){
        $feriados = R::getAll('select * from feriados order by anio');
        return $feriados;
    }
    
    
    public function getCalcularFlujo(){
        
        //Calcular Precio
        $precio = $this->precio;
        $flujo = $precio * -1;
        
        //Traer todos los datos de la tabla Flujo (importados del Excel Bonos)
        $this->load->model('Flujo_model');
        $this->Flujo_model->bono = $this->input->post('bono');
        $datos = $this->Flujo_model->getFlujos();
        
        $this->load->model('Flujo_model');
        $feriados = $this->Flujo_model->getFeriados();
        
        //Calcular fecha sumando dos días hábiles al día de hoy.           
        $mañana = date('n/j/Y', strtotime("+1 days"));
        function esFestivo($mañana, $feriados) {      
            $dias_saltados = array(0,6);
            $w = date("w", strtotime($mañana)); // dia de la semana en formato 0-6
            if(in_array($w, $dias_saltados)) { return true; }
            
            $n = date("n", strtotime($mañana)); // mes en formato 1 - 12
            $j = date("j", strtotime($mañana)); // dia en formato 1 - 31
            $y = date("Y", strtotime($mañana)); // año en formato XXXX                    
            foreach ($feriados as $key => $value) {
                if(($value['anio'] == $y) && ($value['mes'] == $n) && ($value['dia'] == $j)){
                   return true; 
                }
            }
            return false;
        }
        for ($diascontados = 0; $diascontados < 2; $diascontados++){
            if(esFestivo($mañana, $feriados)){
                $diascontados--;
            }
            $fechaFuturaExcel = date('d-M-y', strtotime($mañana)); //Fecha como tendría que mostrarse en pantalla.
            $fechaFutura = strtotime($mañana);
            $mañana = date('n/j/Y',(strtotime('+1 day', strtotime($mañana))));
        }
        
        //Tomar datos necesarios de la tabla flujos (Fechas y Flujos)
        $fechaHoy = date('d-M-y');
        foreach($datos as $dato){
            if(strtotime($dato['fechapagos']) > strtotime($fechaHoy)){
                $fechasExcel[] = $dato['fechapagos']; //Fecha como tendría que mostrarse en el excel.
                $fechas[] = strtotime($dato['fechapagos']);
                $flujos[] = ($dato['interes'] * 100) + ($dato['amortizacion']);
            }
        }
        //Resultados
        array_unshift($flujos, $flujo);
        array_unshift($fechas, $fechaFutura);
        array_unshift($fechasExcel, $fechaFuturaExcel);
        
        $f = new Financial;
        $xirr = $f->XIRR($flujos, $fechas, 0.1) * 100;
        //echo json_encode(array('flujos'=>$flujos, 'fechasExcel'=>$fechasExcel, 'xirr'=>$xirr));
        $flujos = (array('flujos'=>$flujos, 'fechasExcel'=>$fechasExcel, 'xirr'=>$xirr));
        
        return $flujos;
    }
    
}