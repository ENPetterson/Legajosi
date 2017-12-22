<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Formulario_model extends CI_Model{
    
    public $id;
    public $fechaPresentacion;
    public $estado_id;
    public $tramitealta_id;
    public $observaciones;
    /*
    public $actuaPor;
    public $esBeneficiarioFinal;
    public $beneficiarioFinal;
     */
    public $comoNosConocio;
    public $contacto;    
    public $comentarios;
    public $numComitente;
    public $toleranciaRiesgo;
    public $perfilCuenta;
    public $asociarCuenta;
    public $banco;
    public $tipoCuentaBanco;
    public $numeroCuenta;
    public $moneda;
    public $titular;
    public $cbu;
    public $cuitCuenta;
    public $oficial;
    public $administrador;
    public $terceroNoIntermediario;
    public $dniTerceroNoIntermediario;
    public $emailTerceroNoInscripto;
    public $numeroProductor;
    public $responsable_id;
    public $titulares;
    public $titular_id;
    public $numeroEmail;
    
    public $comitentes;
    public $ids;
    
    

    public function __construct() {
        parent::__construct();
    }
    
    public function get(){
        $formularioBean = R::load('formulario', $this->id);
        $formulario = $formularioBean->export();
        $titulares = array();
        foreach ($formularioBean->ownTitular as $titularBean){
            $titular = $titularBean->export();
            $residencias = array();
            foreach ($titularBean->ownResidencia as $residenciaBean){
                array_push($residencias, $residenciaBean->export());
            }
            $titular['residencias'] = $residencias;
            array_push($titulares, $titular);
        }
        $formulario['titulares'] = $titulares;
        $adjuntos = array();
        foreach ($formularioBean->ownAdjunto as $adjuntoBean){
            array_push($adjuntos, array('id'=>$adjuntoBean->id, 'filename'=>$adjuntoBean->filename));
        }
        $formulario['adjuntos'] = $adjuntos;
        return $formulario;
    }
    
    public function getRapido(){
        $formulario = R::getRow('select id, estado_id, fechaEstado, tramitealta_id, observaciones, numComitente from formulario where id = ?', array($this->id));
        $formulario['cuit'] = R::getCol('select cuit from titular where formulario_id = ?', array($this->id));
        return $formulario;
    }
    
    public function saveRapido(){
        $formulario = R::load('formulario', $this->id);        $formulario->fechaPresentacion = $this->fechaPresentacion;
        
        if ($this->estado_id > 0){
            $estado = R::load('estado', $this->estado_id);
            if ($formulario->estado_id != $estado->id){
                $formulario->fechaEstado = date('Y-m-d G:i:s');
            }
            $formulario->estado = $estado;
            
        }
        
        if ($this->tramitealta_id > 0){
            $tramiteAlta = R::load('tramitealta', $this->tramitealta_id);
            $formulario->tramitealta = $tramiteAlta;
        }
                
        $formulario->observaciones = $this->observaciones;
        $formulario->numComitente = $this->numComitente;
        
        R::store($formulario);
        
        $resultado = array('id'=>$formulario->id);
        return $resultado;
    }
    
    public function save(){
        
        $responsable = R::load('usuario', $this->responsable_id);
        
        
        $formulario = R::load('formulario', $this->id);        $formulario->fechaPresentacion = $this->fechaPresentacion;
        
        if ($this->estado_id > 0){
            $estado = R::load('estado', $this->estado_id);
            if ($formulario->estado_id != $estado->id){
                $formulario->fechaEstado = date('Y-m-d G:i:s');
            }
            $formulario->estado = $estado;
            
        }
        if ($this->tramitealta_id > 0){
            $tramiteAlta = R::load('tramitealta', $this->tramitealta_id);
            $formulario->tramitealta = $tramiteAlta;
        }
        
        $formulario->observaciones = $this->observaciones;
        /*
        $formulario->actuaPor = $this->actuaPor;
        $formulario->esBeneficiarioFinal = $this->esBeneficiarioFinal;
        $formulario->beneficiarioFinal = $this->beneficiarioFinal;
         */
        $formulario->comoNosConocio = $this->comoNosConocio;
        $formulario->contacto = $this->contacto;
        $formulario->comentarios = $this->comentarios;
        $formulario->numComitente = $this->numComitente;
        $formulario->toleranciaRiesgo = $this->toleranciaRiesgo;
        $formulario->perfilCuenta = $this->perfilCuenta;
        if ($this->asociarCuenta == 'S'){
            $formulario->asociarCuenta = 'S';
            $formulario->banco = $this->input->post('banco');
            $formulario->tipoCuentaBanco = $this->tipoCuentaBanco;
            $formulario->numeroCuenta = $this->numeroCuenta;
            $formulario->moneda = $this->moneda;
            $formulario->titular = $this->titular;
            $formulario->cbu = $this->cbu;
            $formulario->cuitCuenta = $this->cuitCuenta;
        } else {
            $formulario->asociarCuenta = 'N';
            $formulario->banco = NULL;
            $formulario->tipoCuentaBanco = NULL;
            $formulario->numeroCuenta = NULL;
            $formulario->moneda = NULL;
            $formulario->titular = NULL;
            $formulario->cbu = NULL;
            $formulario->cuitCuenta = NULL;
        }
        $formulario->oficial = $this->oficial;
        $formulario->administrador = $this->administrador;
        $formulario->terceroNoIntermediario = $this->terceroNoIntermediario;
        $formulario->dniTerceroNoIntermediario = $this->dniTerceroNoIntermediario;
        $formulario->emailTerceroNoInscripto = $this->emailTerceroNoInscripto;
        $formulario->numeroProductor = $this->numeroProductor;
        if ($responsable->id > 0){
            $formulario->responsable = $responsable;
        }
        R::store($formulario);
        foreach ($this->titulares as $titularArray){
            $titular = (object) $titularArray;
            $titularBean = R::load('titular', $titular->id);
            $titularBean->formulario = $formulario;
            $titularBean->nombre = $titular->nombre;
            $titularBean->apellido = $titular->apellido;
            $titularBean->tipoDocumento = $titular->tipoDocumento;
            $titularBean->numeroDocumento = $titular->numeroDocumento;
            $titularBean->nacionalidad = $titular->nacionalidad;
            $titularBean->fechaNacimiento = $titular->fechaNacimiento;
            $titularBean->lugarNacimiento = $titular->lugarNacimiento;
            $titularBean->domicilioParticular = $titular->domicilioParticular;
            $titularBean->codigoPostalParticular = $titular->codigoPostalParticular;
            $titularBean->provinciaParticular = $titular->provinciaParticular;
            $titularBean->localidadParticular = $titular->localidadParticular;
            $titularBean->telefonoParticular = $titular->telefonoParticular;
            $titularBean->telefonoCelular = $titular->telefonoCelular;
            $titularBean->email1 = $titular->email1;
            $titularBean->email2 = $titular->email2;
            $titularBean->estadoCivil = $titular->estadoCivil;
            $titularBean->nombreConyuge = $titular->nombreConyuge;
            $titularBean->apellidoConyuge = $titular->apellidoConyuge;
            $titularBean->cuit = $titular->cuit;
            $titularBean->condicionIVA = $titular->condicionIVA;
            $titularBean->condicionGanancias = $titular->condicionGanancias;
            $titularBean->ocupacion = $titular->ocupacion;
            switch ($titular->ocupacion){
                case 'D':
                    $titularBean->empleador = $titular->empleador;
                    $titularBean->actividad = NULL;
                    $titularBean->domicilioLaboral = $titular->domicilioLaboral;
                    $titularBean->codigoPostalLaboral = $titular->codigoPostalLaboral;
                    $titularBean->localidadLaboral = $titular->localidadLaboral;
                    $titularBean->provinciaLaboral = $titular->provinciaLaboral;
                    $titularBean->telefonoLaboral = $titular->telefonoLaboral;
                    break;
                case 'M':
                    $titularBean->empleador = NULL;
                    $titularBean->actividad = $titular->actividad;
                    $titularBean->domicilioLaboral = $titular->domicilioLaboral;
                    $titularBean->codigoPostalLaboral = $titular->codigoPostalLaboral;
                    $titularBean->localidadLaboral = $titular->localidadLaboral;
                    $titularBean->provinciaLaboral = $titular->provinciaLaboral;
                    $titularBean->telefonoLaboral = $titular->telefonoLaboral;
                    break;
                case 'E':
                case 'J':
                    $titularBean->empleador = NULL;
                    $titularBean->actividad = NULL;
                    $titularBean->domicilioLaboral = NULL;
                    $titularBean->codigoPostalLaboral = NULL;
                    $titularBean->localidadLaboral = NULL;
                    $titularBean->provinciaLaboral = NULL;
                    $titularBean->telefonoLaboral = NULL;
                    break;
            }
            $titularBean->esPEP = $titular->esPEP;
            $titularBean->detallePEP = $titular->detallePEP;
            $titularBean->esUIF = $titular->esUIF;
            $titularBean->esCargoPublico = $titular->esCargoPublico;
            $titularBean->detalleCargoPublico = $titular->detalleCargoPublico;
            if ($this->validateDate($titular->fechaIngreso, 'Y-m-d')){
                $titularBean->fechaIngreso = $titular->fechaIngreso;
            }
            if ($this->validateDate($titular->fechaEgreso, 'Y-m-d')){
                $titularBean->fechaEgreso = $titular->fechaEgreso;
            }
            R::store($titularBean);
        }
        
        return $formulario->export();
        
    }
    
    private function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    
    public function getFicha(){
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre','Octubre', 'Noviembre', 'Diciembre');
        
        $dia = date('d');
        $mes = date('m');
        $anio = date('Y');
        $mesLetras = $meses[date('n') - 1];
        
        $formulario = R::load('formulario', $this->id);
        $titulares = 0;
        $titularesNombres = array();
        $imagenes = array();
        
        $indiceImagenes = -1;
        
        foreach ($formulario->ownTitular as $titular){
            $titulares++;
            $indice = str_pad($titulares, 2, '0', STR_PAD_LEFT);
            
            $fichas[$titulares-1]['dia'] = $dia;
            $fichas[$titulares-1]['mes'] = $mes;
            $fichas[$titulares-1]['anio'] = $anio;
            array_push($titularesNombres, $titular->apellido . ', ' . $titular->nombre);
            $fichas[$titulares-1]['apellido'] = $titular->apellido;
            $fichas[$titulares-1]['nombre'] = $titular->nombre;
            switch ($titular->tipoDocumento){
                case 'dni':
                    $fichas[$titulares-1]['dni'] = '';
                    $fichas[$titulares-1]['le'] = 'X';
                    $fichas[$titulares-1]['lc'] = 'X';
                    $fichas[$titulares-1]['numeroDocumento'] = $titular->numeroDocumento;
                    $fichas[$titulares-1]['pasaporte'] = '';
                    break;
                case 'le':
                    $fichas[$titulares-1]['dni'] = 'X';
                    $fichas[$titulares-1]['le'] = '';
                    $fichas[$titulares-1]['lc'] = 'X';
                    $fichas[$titulares-1]['numeroDocumento'] = $titular->numeroDocumento;
                    $fichas[$titulares-1]['pasaporte'] = '';
                    break;
                case 'lc':
                    $fichas[$titulares-1]['dni'] = 'X';
                    $fichas[$titulares-1]['le'] = 'X';
                    $fichas[$titulares-1]['lc'] = '';
                    $fichas[$titulares-1]['numeroDocumento'] = $titular->numeroDocumento;
                    $fichas[$titulares-1]['pasaporte'] = '';
                    break;
                case 'pas':
                    $fichas[$titulares-1]['dni'] = 'X';
                    $fichas[$titulares-1]['le'] = 'X';
                    $fichas[$titulares-1]['lc'] = 'X';
                    $fichas[$titulares-1]['numeroDocumento'] = '';
                    $fichas[$titulares-1]['pasaporte'] = $titular->numeroDocumento;
                    break;
            }
            $fichas[$titulares-1]['nacionalidad'] = $this->nacionalidad($titular->nacionalidad);
            $fichas[$titulares-1]['lugarNacimiento'] = $titular->lugarNacimiento;
            $fechaNacimiento = new DateTime($titular->fechaNacimiento);
            $fichas[$titulares-1]['fechaNacimiento'] = $fechaNacimiento->format('d/m/Y');
            $fichas[$titulares-1]['cuit'] = $titular->cuit;
            $fichas[$titulares-1]['telefonoParticular'] = $titular->telefonoParticular;
            $fichas[$titulares-1]['telefonoCelular'] = $titular->telefonoCelular;
            $fichas[$titulares-1]['domicilioParticular'] = $titular->domicilioParticular . ' (' .  $this->isNull($titular->codigoPostalParticular) . ') - ' . 
                    $this->isNull($titular->localidadParticular) . ' - ' . $this->isNull($titular->provinciaParticular) ;
            if (is_null($titular->ocupacion)){
                $fichas[$titulares-1]['actividad'] = mb_convert_encoding($titular->actividad, 'ISO-8859-1') ;
            } else {
                switch ($titular->ocupacion){
                    case 'D':
                        $fichas[$titulares-1]['actividad'] = 'Relacion de dependencia - Empleador: ' . $titular->empleador;
                        $fichas[$titulares-1]['telefonoLaboral'] = $titular->telefonoLaboral;
                        $fichas[$titulares-1]['domicilioLaboral'] = $titular->domicilioLaboral . '(' . $this->isNull($titular->codigoPostalLaboral) . ') - ' .
                            $this->isNull($titular->localidadLaboral) . ' - ' . $this->isNull($titular->provinciaLaboral);
                        break;
                    case 'M':
                        $fichas[$titulares-1]['actividad'] = 'Autónomo / Monotributista - Actividad: ' . $titular->actividad;
                        $fichas[$titulares-1]['telefonoLaboral'] = $titular->telefonoLaboral;
                        $fichas[$titulares-1]['domicilioLaboral'] = $titular->domicilioLaboral . '(' . $this->isNull($titular->codigoPostalLaboral) . ') - ' .
                            $this->isNull($titular->localidadLaboral) . ' - ' . $this->isNull($titular->provinciaLaboral);
                        break;
                    case 'E':
                        $fichas[$titulares-1]['actividad'] = 'Estudiante / Ama de Casa / Desempleado';
                        $fichas[$titulares-1]['telefonoLaboral'] = '';
                        $fichas[$titulares-1]['domicilioLaboral'] = '';
                        break;
                    case 'J':
                        $fichas[$titulares-1]['actividad'] = 'Jubilado / Desempleado ';
                        $fichas[$titulares-1]['telefonoLaboral'] = '';
                        $fichas[$titulares-1]['domicilioLaboral'] = '';
                        break;
                }
            }
            $email1 =  trim($this->isNull($titular->email1));
            $email2 =  trim($this->isNull($titular->email2));
            if (strlen($email1) > 0){
                $mail = explode('@', $email1);
                $fichas[$titulares-1]['email1Cuenta'] = $mail[0];
                $fichas[$titulares-1]['email1Dominio'] = $mail[1];
            }
            if (strlen($email2) > 0){
                $mail = explode('@', $email2);
                $fichas[$titulares-1]['email2Cuenta01'] = $mail[0];
                $fichas[$titulares-1]['email2Dominio01'] = $mail[1];
            }
            $fichas[$titulares-1]['condicionIva'] = $this->condicionIVA($titular->condicionIVA);
            $fichas[$titulares-1]['condicionGanancias'] = $this->condicionGanancias($titular->condicionGanancias);
            switch ($titular->estadoCivil){
                case 'S':
                    $fichas[$titulares-1]['estadoCivil'] = 'Soltero';
                    break;
                case 'C':
                    $fichas[$titulares-1]['estadoCivil'] = 'Casado';
                    break;
                case 'D':
                    $fichas[$titulares-1]['estadoCivil'] = 'Divorciado';
                    break;
                case 'V':
                    $fichas[$titulares-1]['estadoCivil'] = 'Viudo';
                    break;
            }
            if ($titular->estadoCivil == 'C'){
                $fichas[$titulares-1]['apellidoConyuge'] = $titular->apellidoConyuge;
                $fichas[$titulares-1]['nombreConyuge']  = $titular->nombreConyuge;
            }
            if ($titular->esCargoPublico == 'N'){
                $fichas[$titulares-1]['cargoPublicoNo'] = '';
                $fichas[$titulares-1]['cargoPublicoSi'] = 'X';
                $fichas[$titulares-1]['periodoCargoPublico'] = '';
                $fichas[$titulares-1]['detalleCargoPublico'] = '';
            } else {
                $fichas[$titulares-1]['cargoPublicoNo'] = 'X';
                $fichas[$titulares-1]['cargoPublicoSi'] = '';
                $fichas[$titulares-1]['detalleCargoPublico'] = $titular->detalleCargoPublico;
                $cargoPublicoDesde = new DateTime($titular->cargoPublicoDesde);
                if ($titular->esCargoPublico  == 'P'){
                    $cargoPublicoHasta = new DateTime($titular->cargoPublicoHasta);
                    $fichas[$titulares-1]['periodoCargoPublico'] = 'Desde el ' . $cargoPublicoDesde->format('d/m/Y') . ' hasta el ' . $cargoPublicoHasta->format('d/m/Y');
                } else {
                    $fichas[$titulares-1]['periodoCargoPublico'] = 'Desde el ' . $cargoPublicoDesde->format('d/m/Y') . ' hasta la actualidad';
                }
                $fichas[$titulares-1]['periodoCargoPublico'] = '';
            }
            if ($formulario->esBeneficiarioFinal == 'S'){
                $fichas[$titulares-1]['beneficiarioFinal'] = $titular->apellido . ', ' . $titular->nombre;
            } else {
                $fichas[$titulares-1]['beneficiarioFinal'] = $formulario->beneficiarioFinal;
            }
            $fichas[$titulares-1]['oficial'] = $formulario->oficial;
            $fichas[$titulares-1]['administrador'] = $formulario->administrador;
            
            $indiceForm = intval(($titulares -1) / 4);
            $lugar = (string) $titulares - (4 * intval(($titulares -1)/4));
            
            $convenios[$indiceForm]['dia'] = $dia;
            $convenios[$indiceForm]['mes'] = $mesLetras;
            $convenios[$indiceForm]['anio'] = $anio;
            $convenios[$indiceForm]['titular' . $lugar] = $titular->nombre . ' ' . $titular->apellido;
            
            if ($titulares == 1){
                $perfil['dia'] = $dia;
                $perfil['mes'] = $mes;
                $perfil['anio'] = $anio;
                switch ($formulario->toleranciaRiesgo){
                    case 'B':
                        $perfil['riesgoBajo'] = 'X';
                        $perfil['riesgoMedio'] = '';
                        $perfil['riesgoAlto'] = '';
                        break;
                    case 'M':
                        $perfil['riesgoBajo'] = '';
                        $perfil['riesgoMedio'] = 'X';
                        $perfil['riesgoAlto'] = '';
                        break;
                    case 'A':
                        $perfil['riesgoBajo'] = '';
                        $perfil['riesgoMedio'] = '';
                        $perfil['riesgoAlto'] = 'X';
                        break;
                }
                $perfil['titular'] = $titular->nombre . ' ' . $titular->apellido;
                
                $autorizacionAllaria['dia'] = $dia;
                $autorizacionAllaria['mes'] = $mesLetras;
                $autorizacionAllaria['anio'] = $anio;
                $autorizacionAllaria['titular'] = $titular->nombre . ' ' . $titular->apellido;
                
                if (strlen(trim($formulario->terceroNoIntermediario)) > 0 ){
                    $autorizacionTercero['dia'] = $dia;
                    $autorizacionTercero['mes'] = $mesLetras;
                    $autorizacionTercero['anio'] = $anio;
                    $autorizacionTercero['operador'] = $formulario->terceroNoIntermediario;
                    $autorizacionTercero['dniOperador'] = $formulario->dniTerceroNoIntermediario;
                    if (!is_null($formulario->emailTerceroNoInscripto) && strlen(trim($formulario->emailTerceroNoInscripto)) > 0){
                        $emailOperador = explode('@', $formulario->emailTerceroNoInscripto);
                        $autorizacionTercero['cuentaEmailOperador'] = $emailOperador[0];
                        $autorizacionTercero['dominioEmailOperador'] = $emailOperador[1];
                    }
                    $autorizacionTercero['titular'] = $titular->nombre . ' ' . $titular->apellido;
                    
                }
                if ($formulario->numeroProductor > 0){
                    $autorizacionProductor['dia'] = $dia;
                    $autorizacionProductor['mes'] = $mesLetras;
                    $autorizacionProductor['anio'] = $anio;
                    $autorizacionProductor['nombreTitular'] = $titular->nombre . ' ' . $titular->apellido;;
                    $autorizacionProductor['dniTitular'] = $titular->numeroDocumento;
                    $autorizacionProductor['numeroProductor'] = $formulario->numeroProductor;
                    $autorizacionProductor['titular'] = $titular->nombre . ' ' . $titular->apellido;
                    $autorizacionProductor['dni'] = $titular->numeroDocumento;
                }
                
                $instructivo['asociarCuenta'] = $formulario->asociarCuenta;
                if ($formulario->asociarCuenta == 'S'){
                    $instructivo['dia'] = $dia;
                    $instructivo['mes'] = $mesLetras;
                    $instructivo['anio'] = $anio;
                    $instructivo['banco'] = $formulario->banco;
                    switch ($formulario->tipoCuentaBanco){
                        case 'CA':
                            $tipoCuentaBanco = 'Caja de Ahorro';
                            break;
                        case 'CC':
                            $tipoCuentaBanco = 'Cuenta Corriente';
                            break;
                    }
                    $instructivo['tipoyNumeroCuenta'] = $tipoCuentaBanco . ' - ' . $formulario->numeroCuenta;
                    switch ($formulario->moneda){
                        case 'P':
                            $moneda = 'Pesos';
                            break;
                        case 'D':
                            $moneda = 'Dólares';
                            break;
                    }
                    $instructivo['moneda'] = $moneda;
                    $instructivo['titular'] = $formulario->titular;
                    $cbu = str_split(str_replace(' ', '', $formulario->cbu));
                    for ($i = 0; $i<=21;$i++){
                        $instructivo['cbu' . $i] = $cbu[$i];
                    }
                    $cuitCuenta = str_split(str_replace('-', '', $formulario->cuitCuenta));
                    for ($i = 0; $i<=10; $i++){
                        $instructivo['cuitCuenta' . $i] = $cuitCuenta[$i];
                    }
                    $instructivo['aclaracion'] = $formulario->titular;
                    $instructivo['numeroDocumento'] = $titular->numeroDocumento;
                }
            }
            $pep[$titulares-1]['titular'] = $titular->nombre . ' ' . $titular->apellido;
            if ($titular->esPEP == 'N'){
                $pep[$titulares-1]['pepSi'] = 'X';
                $pep[$titulares-1]['pepNo'] = '';
                $pep[$titulares-1]['motivoPep'] = '';
            } else {
                $pep[$titulares-1]['pepSi'] = '';
                $pep[$titulares-1]['pepNo'] = 'X';
                $pep[$titulares-1]['motivoPep'] = $titular->detallePEP;
            }
            $pep[$titulares-1]['tipoDocumento'] = strtoupper($titular->tipoDocumento);
            $pep[$titulares-1]['numeroDocumento'] = $titular->numeroDocumento;
            $pep[$titulares-1]['cuit'] = $titular->cuit;
            $pep[$titulares-1]['lugarYFecha'] = 'Buenos Aires, ' . $dia . ' de ' . $mesLetras . ' de ' . $anio;

            $ocde[$titulares-1]['titular'] = $titular->nombre . ' ' . $titular->apellido;
            $ocde[$titulares-1]['fatcaNo'] = 'X';
            $ocde[$titulares-1]['fatcaSi'] = '';


            $residencias = 0;
            foreach ($titular->ownResidencia as $residencia){
                $residencias++;
                if ($residencia->paisResidencia == 'US'){
                    $ocde[$titulares-1]['fatcaNo'] = '';
                    $ocde[$titulares-1]['fatcaSi'] = 'X';                            
                }
                if ($residencias == 4) {
                    break;
                }
                $ocde[$titulares-1]['pais' . $residencias] = $this->nacionalidad($residencia->paisResidencia);
                if ($residencia->paisResidencia == 'AR'){
                    $ocde[$titulares-1]['nit' . $residencias] = $titular->cuit;
                } else {
                    $ocde[$titulares-1]['nit' . $residencias] = $residencia->idTributaria;
                }
            }

            $ocde[$titulares-1]['aclaracion'] = $titular->nombre . ' ' . $titular->apellido;
            $ocde[$titulares-1]['fecha'] = $dia . '/' . $mes . '/' . $anio;
            
            $imagenDocumento = $this->convertirImagen($titular->imagenDocumento);
            
            $imagenDorso = $this->convertirImagen($titular->imagenDorso);
            
            if ($imagenDocumento !== false || $imagenDorso !== false){
                $elemento = array();
                $elemento['nombre'] = $titular->nombre . ' ' . $titular->apellido;
                if ($imagenDocumento !== false){
                    $elemento['imagenDocumento'] = $titular->imagenDocumento;
                }
                if ($imagenDorso !== false){
                    $elemento['imagenDorso'] = $titular->imagenDorso;
                }
                array_push($imagenes, $elemento);
            }
        }
        //Aca tengo que poner todos los titulares
        for ($i=0; $i<$titulares; $i++){
            $fichas[$i]['titulares'] = implode($titularesNombres, ' - ');
        }
        
        $datos['id'] = $this->id;
        $datos['fichas'] = $fichas;
        $datos['convenios'] = $convenios;
        $datos['perfil'] = $perfil;
        $datos['autorizacionAllaria'] = $autorizacionAllaria;
        if (isset($autorizacionTercero['dia'])){
            $datos['autorizacionTercero'] = $autorizacionTercero;
        }
        if (isset($autorizacionProductor['dia'])){
            $datos['autorizacionProductor'] = $autorizacionProductor;
        }
        $datos['instructivo'] = $instructivo;
        
        $datos['pep'] = $pep;
        $datos['ocde'] = $ocde;
        
        $datos =  $this->utf8_converter($datos);
        $datos['imagenes'] = $imagenes;
        
        return $datos;
    }    
    
    public function getOficiales(){
        $sql = "select distinct oficial 
                from     formulario 
                where    oficial is not null
                order by oficial";
        $oficiales = R::getAll($sql);
        return $oficiales;
    }
    
    public function getAdministradores(){
        $sql = "select distinct administrador 
                from     formulario 
                where    administrador is not null
                order by administrador";
        $administradores = R::getAll($sql);
        return $administradores;
    }
    
    public function getTercerosNoIntermediarios(){
        $sql = "select distinct terceroNoIntermediario
                from     formulario 
                where    terceroNoIntermediario is not null
                order by terceroNoIntermediario";
        $tercerosNoIntermediarios = R::getAll($sql);
        return $tercerosNoIntermediarios;
    }
    
    public function getDatosTercero(){
        $sql = "select 	dniTerceroNoIntermediario,
                        emailTerceroNoInscripto,
                        numeroProductor
                from	formulario
                where	id = (
                        select max(id)
                        from     formulario 
                        where    terceroNoIntermediario = ?
                        order by terceroNoIntermediario)";
        $terceroNoItermediario = R::getRow($sql, array($this->terceroNoIntermediario));
        return $terceroNoItermediario;
    }
    
    private function nacionalidad($codigo){
        $pais = R::findOne('pais', 'codigo = ?', array($codigo));
        return $pais->nombre;
    }
    
    private function condicionIVA($clave){
        $condicionIVA = R::findOne('condicioniva', 'clave = ?', array($clave));
        return $condicionIVA->descripcion;
    }
    
    private function condicionGanancias($clave){
        $condicionGanancias = R::findOne('condicionganancias', 'clave = ?', array($clave));
        return $condicionGanancias->descripcion;
    }
    
    public function verificarEmail(){
        $titular = R::load('titular', $this->titular_id);
        if ($this->numeroEmail == 1){
            $titular->email1Verificado = 1;
        }
        if ($this->numeroEmail == 2){
            $titular->email2Verificado = 1;
        }
        R::store($titular);
        $resultado = array('resultado'=>'exitoso');
        return $resultado;
    }
    
    public function del(){
        $formulario = R::load('formulario', $this->id);
        foreach ($formulario->ownTitular as $titular){
            foreach ($titular->ownResidencia as $residencia){
                R::trash($residencia);
            }
            R::trash($titular);
        }
        foreach ($formulario->ownAdjunto as $adjunto){
            R::trash($adjunto);
        }
        R::trash($formulario);
        $resultado = array('resultado'=>'Se ha borrado la solicitud exitosamente');
        return $resultado;
    }
    
    private function isNull($variable, $default = ''){
        $resultado = $default;
        if (!is_null($variable)){
            $resultado = $variable;
        }
        return  $resultado;
    }
    
    function utf8_converter($array){
        array_walk_recursive($array, function(&$item, $key){
            $item = strtoupper($this->ConvertToUTF8($item));
        });
        return $array;
    }
    
    function ConvertToUTF8($text){
        $encoding = mb_detect_encoding($text, mb_detect_order(), false);
        if($encoding == "UTF-8") {
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');    
        }
        $out = iconv(mb_detect_encoding($text, mb_detect_order(), false), "UTF-8//IGNORE", $text);
        return $out;
    }
    
    function convertirImagen($imagen){
        if (is_null($imagen)){
            $resultado = false;
        } else {
            if (strlen(trim($imagen)) == 0){
                $resultado = false;
            } else {
                $imagenEncoded = explode(',',$imagen)[1];
                $imagenDecoded = base64_decode($imagenEncoded);
                if ($imagenEncoded === base64_encode($imagenDecoded)){
                    $resultado = imagecreatefromstring($imagenDecoded);
                } else {
                    $resultado = false;
                }
            }
        }
        return $resultado;
    }
    
    public function getAdjunto(){
        $adjunto = R::load('adjunto', $this->id);
        return $adjunto->export();
    }
    
    public function getComitentesPresenciales(){
        $presenciales = R::getCol('select numComitente from formulario where numComitente > 0 and tramitealta_id in (1,2) ');
        return $presenciales;
    }
    
    public function getComitentesNoPresenciales(){
        $noPresenciales = R::getCol('select numComitente from formulario where numComitente > 0 and tramitealta_id = 3');
        return $noPresenciales;
    }
    
    public function getFormulariosSinComitente(){
        $formularioSinComitente = R::getAll('select * from v_formulario where id in (select id from formulario where estado_id = 5 and (numComitente is NULL or numComitente = 0))');
        return $formularioSinComitente;
    }
    
    public function getFormulariosByComitente(){
        $formularios = R::getAll('select * from v_formulario where numComitente in ('. implode(',', $this->comitentes) . ')');
        return $formularios;
    }
    
    public function getFormulariosById(){
        $formularios = R::getAll('select * from v_formulario where id in ('. implode(',', $this->ids) . ')');
        return $formularios;
    }

}

class Model_Formulario extends RedBean_SimpleModel {
    private $prev;
    
    function open(){
        $this->prev = $this->bean->export();
    }
    
    function after_update(){
        if (json_encode($this->prev) != json_encode($this->bean->export())){
            $CI =& get_instance();
            $usuarioParam = $CI->session->userdata('usuario');
            $usuario = R::load('usuario', $usuarioParam['id']);
            $auditoria = R::dispense('auditoria');
            if ($usuario->id > 0){
                $auditoria->usuario = $usuario;
            }
            $auditoria->table = 'formulario';
            $auditoria->tableId = $this->bean->id;
            $auditoria->anterior = json_encode($this->prev);
            $auditoria->actual = json_encode($this->bean->export());
            R::store($auditoria);
        }
    }

    function after_delete(){
        $CI =& get_instance();
        $usuarioParam = $CI->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);
        $auditoria = R::dispense('auditoria');
        if ($usuario->id > 0){
            $auditoria->usuario = $usuario;
        }
        $auditoria->table = 'formulario';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
    
}

class Model_Titular extends RedBean_SimpleModel {
    private $prev;
    
    function open(){
        $this->prev = $this->bean->export();
    }
    
    function after_update(){
        if (json_encode($this->prev) != json_encode($this->bean->export())){
            $CI =& get_instance();
            $usuarioParam = $CI->session->userdata('usuario');
            $usuario = R::load('usuario', $usuarioParam['id']);
            $auditoria = R::dispense('auditoria');
            if ($usuario->id > 0){
                $auditoria->usuario = $usuario;
            }
            $auditoria->table = 'titular';
            $auditoria->tableId = $this->bean->id;
            $auditoria->anterior = json_encode($this->prev);
            $auditoria->actual = json_encode($this->bean->export());
            R::store($auditoria);
        }
    }

    function after_delete(){
        $CI =& get_instance();
        $usuarioParam = $CI->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);
        $auditoria = R::dispense('auditoria');
        if ($usuario->id > 0){
            $auditoria->usuario = $usuario;
        }
        $auditoria->table = 'titular';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
    
}

class Model_Residencia extends RedBean_SimpleModel {
    private $prev;
    
    function open(){
        $this->prev = $this->bean->export();
    }
    
    function after_update(){
        if (json_encode($this->prev) != json_encode($this->bean->export())){
            $CI =& get_instance();
            $usuarioParam = $CI->session->userdata('usuario');
            $usuario = R::load('usuario', $usuarioParam['id']);
            $auditoria = R::dispense('auditoria');
            if ($usuario->id > 0){
                $auditoria->usuario = $usuario;
            }
            $auditoria->table = 'residencia';
            $auditoria->tableId = $this->bean->id;
            $auditoria->anterior = json_encode($this->prev);
            $auditoria->actual = json_encode($this->bean->export());
            R::store($auditoria);
        }
    }

    function after_delete(){
        $CI =& get_instance();
        $usuarioParam = $CI->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioParam['id']);
        $auditoria = R::dispense('auditoria');
        if ($usuario->id > 0){
            $auditoria->usuario = $usuario;
        }
        $auditoria->table = 'residencia';
        $auditoria->tableId = $this->prev['id'];
        $auditoria->anterior = json_encode($this->prev);
        $auditoria->actual = json_encode(array('operacion'=>'Registro Borrado'));
        R::store($auditoria);        
    }    
    
}
