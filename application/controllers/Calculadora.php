<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calculadora extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'calculadora/home', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value);
        }
    }
    
    public function calculadora(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'calculadora/calculadora', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value);
        }
    }
    
    
    public function resultado(){
        $datos['flujos'] = $this->input->post('flujos');
        $views = array(
            'template/encabezado', 
            'template/menu',
            'calculadora/resultado', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
}