<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Derivador_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function getDestino(){
        $usuarioLogueado = $this->session->userdata('usuario');
        $usuario = R::load('usuario', $usuarioLogueado['id']);
        //Cargando el grupo Retail con id = 3
        $grupo = R::load('grupo', 3);
        if (R::areRelated($usuario, $grupo)){
            $destino = '/retail';
        } else {
            $destino = '/formulario';
        }
        return $destino;
    }
}