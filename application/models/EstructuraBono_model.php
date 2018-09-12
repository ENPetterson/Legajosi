<?php

class EstructuraBono_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $estructuraByma;
    
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    

    
    
    public function saveEstructuraBono(){
        
        $estructuraBono = R::load('estructurabono', $this->id);
        
        $bono = R::load('bono', $this->bono);
        
        $estructuraBono->bono = $bono;
        $estructuraBono->tipoInstrumentoImpuesto = $this->tipoInstrumentoImpuesto;
        $estructuraBono->tipoAjuste = $this->tipoAjuste;
        $estructuraBono->tipoInstrumento = $this->tipoInstrumento;
        $estructuraBono->nombreConocido = $this->nombreConocido;
        $estructuraBono->tipoEmisor = $this->tipoEmisor;
        $estructuraBono->emisor = $this->emisor;
        $estructuraBono->monedacobro = $this->monedacobro;
        $estructuraBono->monedaEmision = $this->monedaEmision;
        $estructuraBono->cerInicial = $this->cerInicial;
        $estructuraBono->diasPreviosCer = $this->diasPreviosCer;
        $estructuraBono->especieCaja = $this->especieCaja;
        $estructuraBono->isin = $this->isin;
        $estructuraBono->nombre = $this->nombre;
        $estructuraBono->fechaEmision = $this->fechaEmision;
        $estructuraBono->fechaVencimiento = $this->fechaVencimiento;
        $estructuraBono->oustanding = $this->oustanding;
        $estructuraBono->ley = $this->ley;
        $estructuraBono->amortizacion = $this->amortizacion;
        $estructuraBono->tipoTasa = $this->tipoTasa;
        $estructuraBono->tipoTasaVariable = $this->tipoTasaVariable;
        $estructuraBono->spread = $this->spread;
        $estructuraBono->tasaMinima = $this->tasaMinima;
        $estructuraBono->tasaMaxima = $this->tasaMaxima;
        $estructuraBono->cuponAnual = $this->cuponAnual;
        $estructuraBono->cantidadCuponesAnio = $this->cantidadCuponesAnio;
        $estructuraBono->frecuenciaCobro = $this->frecuenciaCobro;
        $estructuraBono->fechasCobroCupon = $this->fechasCobroCupon;
        $estructuraBono->formulaCalculoInteres = $this->formulaCalculoInteres;
        $estructuraBono->diasPreviosRecord = $this->diasPreviosRecord;
        $estructuraBono->proximoCobroInteres = $this->proximoCobroInteres;
        $estructuraBono->proximoCobroCapital = $this->proximoCobroCapital;
        $estructuraBono->duration = $this->duration;
        $estructuraBono->precioMonedaOrigen = $this->precioMonedaOrigen;
        $estructuraBono->lastYtm = $this->lastYtm;
        $estructuraBono->paridad = $this->paridad;
        $estructuraBono->currentYield = $this->currentYield;
        $estructuraBono->interesesCorridos = $this->interesesCorridos;
        $estructuraBono->valorResidual = $this->valorResidual;
        $estructuraBono->valorTecnico = $this->valorTecnico;
        $estructuraBono->mDuration = $this->mDuration;
        $estructuraBono->convexity = $this->convexity;
        $estructuraBono->denominacionMinima = $this->denominacionMinima;
        $estructuraBono->spreadSinTasa = $this->spreadSinTasa;
        $estructuraBono->ultimaTna = $this->ultimaTna;
        $estructuraBono->diasInicioCupon = $this->diasInicioCupon;
        $estructuraBono->diasFinalCupon = $this->diasFinalCupon;
        $estructuraBono->capitalizacionInteres = $this->capitalizacionInteres;
        $estructuraBono->precioPesos = $this->precioPesos;
        $estructuraBono->fechaActualizacion = $this->fechaActualizacion;

        $this->id = R::store($estructuraBono);
        return $this->id;
    }
    
    
    
    public function getEstructuraBono(){
        $estructuraBono = R::load('estructurabono', $this->id);
        return $estructuraBono->export();
    }
    
    public function getEstructuraBonos(){
        $estructuraBonos = R::getAll('select * from estructurabono order by bono_id');
        return $estructuraBonos;
    }
    
    public function getFechaActualizacion(){
//        $fechaActualizacion = R::getAll('SELECT DISTINCT fechaActualizacion from estructurabono where bono = ? ', array($this->bono));   
        $fechaActualizacion = R::getAll('SELECT DISTINCT fechaActualizacion from estructurabono');        

        return $fechaActualizacion;
    }
    
    
    public function grillaEstructuraBono(){
        
        
        $sql = "SELECT 
                e.id,
                b.nombre as bono,
                e.tipoInstrumentoImpuesto,
                e.tipoAjuste,
                e.tipoInstrumento,
                e.nombreConocido,
                e.tipoEmisor,
                e.emisor,
                e.monedacobro,
                e.monedaEmision,
                e.cerInicial,
                e.diasPreviosCer,
                e.especieCaja,
                e.isin,
                e.nombre,
                e.fechaEmision,
                e.fechaVencimiento,
                e.oustanding,
                e.ley,
                e.amortizacion,
                e.tipoTasa,
                e.tipoTasaVariable,
                e.spread,
                e.tasaMinima,
                e.tasaMaxima,
                e.cuponAnual,
                e.cantidadCuponesAnio,
                e.frecuenciaCobro,
                e.fechasCobroCupon,
                e.formulaCalculoInteres,
                e.diasPreviosRecord,
                e.proximoCobroInteres,
                e.proximoCobroCapital,
                e.duration,
                e.precioMonedaOrigen,
                e.lastYtm,
                e.paridad,
                e.currentYield,
                e.interesesCorridos,
                e.valorResidual,
                e.valorTecnico,
                e.mDuration,
                e.convexity,
                e.denominacionMinima,
                e.spreadSinTasa,
                e.ultimaTna,
                e.diasInicioCupon,
                e.diasFinalCupon,
                e.capitalizacionInteres,
                e.precioPesos

                FROM estructurabono e
                INNER JOIN bono b
                ON e.bono_id = b.id 
                WHERE b.nombre = ?
                ORDER BY id"; 
        
        $resultado = R::getAll($sql, array($this->bono));
        
        return $resultado;
    }
    
    
    public function grillaEstructuraBonoFecha(){
        
        
        $sql = "SELECT 
                e.id,
                b.nombre as bono,
                e.tipoInstrumentoImpuesto,
                e.tipoAjuste,
                e.tipoInstrumento,
                e.nombreConocido,
                e.tipoEmisor,
                e.emisor,
                e.monedacobro,
                e.monedaEmision,
                e.cerInicial,
                e.diasPreviosCer,
                e.especieCaja,
                e.isin,
                e.nombre,
                e.fechaEmision,
                e.fechaVencimiento,
                e.oustanding,
                e.ley,
                e.amortizacion,
                e.tipoTasa,
                e.tipoTasaVariable,
                e.spread,
                e.tasaMinima,
                e.tasaMaxima,
                e.cuponAnual,
                e.cantidadCuponesAnio,
                e.frecuenciaCobro,
                e.fechasCobroCupon,
                e.formulaCalculoInteres,
                e.diasPreviosRecord,
                e.proximoCobroInteres,
                e.proximoCobroCapital,
                e.duration,
                e.precioMonedaOrigen,
                e.lastYtm,
                e.paridad,
                e.currentYield,
                e.interesesCorridos,
                e.valorResidual,
                e.valorTecnico,
                e.mDuration,
                e.convexity,
                e.denominacionMinima,
                e.spreadSinTasa,
                e.ultimaTna,
                e.diasInicioCupon,
                e.diasFinalCupon,
                e.capitalizacionInteres,
                e.precioPesos

                FROM estructurabono e
                INNER JOIN bono b
                ON e.bono_id = b.id 
                WHERE e.fechaActualizacion = ?
                ORDER BY id"; 
        
        $resultado = R::getAll($sql, array($this->fechaActualizacion));

        return $resultado;
    }
    
    
    
//    public function delBono(){
//        $bono = R::load('bono', $this->id);
//        R::trash($bono);
//    }
//    
//    public function assocUsuario(){
//        $bono = R::load('bono', $this->id);
//        $usuario = R::load('usuario', $this->usuario_id);
//        R::associate($bono, $usuario);
//    }
//    
//    public function clearRelMenu(){
//        $bono = R::load('bono', $this->id);
//        R::clearRelations($bono, 'menu');
//    }
//    
//    public function assocMenu(){
//        $bono = R::load('bono', $this->id);
//        $menu = R::load('menu', $this->menu_id);
//        R::associate($bono, $menu);
//    }
//    
//    public function clearRelControlador(){
//        $bono = R::load('bono', $this->id);
//        R::clearRelations($bono, 'controlador');
//    }
//    
//    public function assocControlador(){
//        $bono = R::load('bono', $this->id);
//        $controlador = R::load('controlador', $this->controlador_id);
//        R::associate($bono, $controlador);
//    }

}