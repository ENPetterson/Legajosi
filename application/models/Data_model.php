<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $datos;
    
    public function importar(){
        $formulario = R::dispense('formulario');
        
        $formulario->solicitud = $this->datos->id;
        $formulario->actuaPor = $this->datos->actuaPor;
        $formulario->esBeneficiarioFinal = $this->datos->esBeneficiarioFinal;
        if ($this->datos->esBeneficiarioFinal == 'N'){
            $formulario->beneficiarioFinal = $this->datos->beneficiarioFinal;
        }
        $formulario->completarPerfil = $this->datos->completarPerfil;
        if ($this->datos->completarPerfil == 'W'){
            $formulario->conocimientoBonos = $this->datos->conocimientoBonos;
            $formulario->conocimientoAcciones = $this->datos->conocimientoAcciones;
            $formulario->conocimientoOpciones = $this->datos->conocimientoOpciones;
            $formulario->conocimientoFuturos = $this->datos->conocimientoFuturos;
            $formulario->experienciaBonos = $this->datos->experienciaBonos;
            if ($this->datos->experienciaBonos == 'S'){
                $formulario->aniosExperienciaBonos = $this->datos->aniosExperienciaBonos;
            }
            $formulario->experienciaAcciones = $this->datos->experienciaAcciones;
            if ($this->datos->experienciaAcciones == 'S'){
                $formulario->aniosExperienciaAcciones = $this->datos->aniosExperienciaAcciones;
            }
            $formulario->experienciaOpciones = $this->datos->experienciaOpciones;
            if ($this->datos->experienciaOpciones == 'S'){
                $formulario->aniosExperienciaOpciones = $this->datos->aniosExperienciaOpciones;
            }
            $formulario->experienciaFuturos = $this->datos->experienciaFuturos;
            if ($this->datos->experienciaFuturos == 'S'){
                $formulario->aniosExperianciaFuturos = $this->datos->aniosExperianciaFuturos;
            }
            $formulario->objetivoInversion = $this->datos->objetivoInversion;
            $formulario->porcentajeAhorro = $this->datos->porcentajeAhorro;
            $formulario->ingresosLiquidos = $this->datos->ingresosLiquidos;
            $formulario->patrimonioLiquido = $this->datos->patrimonioLiquido;
            $formulario->patrimonioNetoTotal = $this->datos->patrimonioNetoTotal;
            $formulario->horizonteInversion = $this->datos->horizonteInversion;
            $formulario->toleranciaRiesgo = $this->datos->toleranciaRiesgo;
        }
        /*
        $formulario->asociarCuenta = $this->datos->asociarCuenta;
        if ($this->datos->asociarCuenta == 'S'){
            $formulario->banco = $this->datos->banco;
            $formulario->tipoCuentaBanco = $this->datos->tipoCuentaBanco;
            $formulario->numeroCuenta = $this->datos->numeroCuenta;
            $formulario->moneda = $this->datos->moneda;
            $formulario->titular = $this->datos->titular;
            $formulario->cbu = $this->datos->cbu;
            $formulario->cuitCuenta = $this->datos->cuitCuenta;
        }
         * 
         */
        $formulario->comoNosConocio = $this->datos->comoNosConocio;
        if ($this->datos->comoNosConocio == 'P'){
            $formulario->productor = $this->datos->productor;
        }
        $formulario->emailVerificado = $this->datos->emailVerificado;
        R::store($formulario);
        
        foreach ($this->datos->ownTitular as $datosTitular){
            $titular = R::dispense('titular');
            $titular->formulario = $formulario;
            $titular->nombre = $datosTitular->nombre;
            $titular->apellido = $datosTitular->apellido;
            $titular->tipoDocumento = $datosTitular->tipoDocumento;
            $titular->numeroDocumento = $datosTitular->numeroDocumento;
            $titular->imagenDocumento = $datosTitular->imagenDocumento;
            $titular->imagenDorso = $datosTitular->imagenDorso;
            $titular->nacionalidad = $datosTitular->nacionalidad;
            $titular->fechaNacimiento = $datosTitular->fechaNacimiento;
            $titular->lugarNacimiento = $datosTitular->lugarNacimiento;
            $titular->domicilioParticular = $datosTitular->domicilioParticular;
            $titular->imagenServicio = $datosTitular->imagenServicio;
            $titular->telefonoParticular = $datosTitular->telefonoParticular;
            $titular->telefonoCelular = $datosTitular->telefonoCelular;
            $titular->email1 = $datosTitular->email1;
            $titular->email2 = $datosTitular->email2;
            $titular->estadoCivil = $datosTitular->estadoCivil;
            if ($datosTitular->estadoCivil == 'C'){
                $titular->nombreConyuge = $datosTitular->nombreConyuge;
                $titular->apellidoConyuge = $datosTitular->apellidoConyuge;
            }            
            $titular->cuit = $datosTitular->cuit;
            $titular->condicionIVA = $datosTitular->condicionIVA;
            $titular->condicionGanancias = $datosTitular->condicionGanancias;
            $titular->actividad = $datosTitular->actividad;
            $titular->domicilioLaboral = $datosTitular->domicilioLaboral;
            $titular->telefonoLaboral = $datosTitular->telefonoLaboral;
            $titular->esCargoPublico = $datosTitular->esCargoPublico;
            switch ($datosTitular->esCargoPublico){
                case 'P':
                    $titular->fechaEgreso = $datosTitular->fechaEgreso;
                case 'A':
                    $titular->detalleCargoPublico = $datosTitular->detalleCargoPublico;
                    $titular->fechaIngreso = $datosTitular->fechaIngreso;
                break;
            }
            $titular->esPEP = $datosTitular->esPEP;
            if ($datosTitular->esPEP == 'S'){
                $titular->detallePEP = $datosTitular->detallePEP;
            }
            $titular->esUIF = $datosTitular->esUIF;
            if ($datosTitular->esUIF == 'S'){
                $titular->imagenUIF = $datosTitular->imagenUIF;
            }
            R::store($titular);
            
            foreach ($datosTitular->ownResidencia as $datosResidencia){
                $residencia = R::dispense('residencia');
                $residencia->titular = $titular;
                $residencia->paisResidencia = $datosResidencia->paisResidencia;
                if ($datosResidencia->paisResidencia != 'AR'){
                    $residencia->idTributaria = $datosResidencia->idTributaria;
                }
                R::store($residencia);
            }
        }
        
        return $formulario->export();
    }
    
    public function importarProductores(){
        $this->load->model('Esco_model');
        $productores = $this->Esco_model->getProductores();
        foreach ($productores as $itemProductor) {
            $productor = R::findOne('productor', 'codigo = ?', array($itemProductor['CodOperativo']));
            if (is_null($productor)){
                $productor = R::dispense('productor');
            }
            $productor->apellido = $itemProductor['Apellido'];
            $productor->nombre = $itemProductor['Nombre'];
            $productor->codigo = $itemProductor['CodOperativo'];
            R::store($productor);
        }
    }
    
}
