<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perfil extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'perfil/grilla', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value);
        }
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $views = array(
            'template/encabezado', 
            'template/menu',
            'perfil/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function savePerfil(){
        $this->load->model('Perfil_model');
        $this->Perfil_model->id = $this->input->post('id');
        $this->Perfil_model->nombre = $this->input->post('nombre');
        $this->Perfil_model->apellido = $this->input->post('apellido');
        $this->Perfil_model->esSoltero = $this->input->post('esSoltero');
        $this->Perfil_model->color = $this->input->post('color');
        $this->Perfil_model->comida = $this->input->post('comida');
        $this->Perfil_model->musica = $this->input->post('musica');
        $this->Perfil_model->pelicula = $this->input->post('pelicula');
        $this->Perfil_model->esDeportista = $this->input->post('esDeportista');
        $this->Perfil_model->esVegetariano = $this->input->post('esVegetariano');                                      
        $id = $this->Perfil_model->savePerfil();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }



    
    function getPerfil(){
        $this->load->model('Perfil_model');
        $this->Perfil_model->id = $this->input->post('id');
        $perfil = $this->Perfil_model->getPerfil();
        echo json_encode($perfil);
    }

    function getColor(){
        $this->load->model('Perfil_model');
        $this->Perfil_model->id = $this->input->post('id');
        $color = $this->Perfil_model->getColor();
        echo json_encode($color);
    }
    
    function getComida(){
        $this->load->model('Perfil_model');
        $this->Perfil_model->id = $this->input->post('id');
        $comida = $this->Perfil_model->getComida();
        echo json_encode($comida);
    }

    function getMusica(){
        $this->load->model('Perfil_model');
        $this->Perfil_model->id = $this->input->post('id');
        $musica = $this->Perfil_model->getMusica();
        echo json_encode($musica);
    }

    function getPelicula(){
        $this->load->model('Perfil_model');
        $this->Perfil_model->id = $this->input->post('id');
        $pelicula = $this->Perfil_model->getPelicula();
        echo json_encode($pelicula);
    }

    function getPerfiles(){
        $this->load->model('Perfil_model');
        $perfil = $this->Perfil_model->getPerfiles();
        echo json_encode($perfil);
    }
    
    function getColores(){
        $this->load->model('Perfil_model');
        $color = $this->Perfil_model->getColores();
        echo json_encode($color);
    }

    function getComidas(){
        $this->load->model('Perfil_model');
        $comida = $this->Perfil_model->getComidas();
        echo json_encode($comida);
    }

    function getMusicas(){
        $this->load->model('Perfil_model');
        $musica = $this->Perfil_model->getMusicas();
        echo json_encode($musica);
    }

    function getPeliculas(){
        $this->load->model('Perfil_model');
        $pelicula = $this->Perfil_model->getPeliculas();
        echo json_encode($pelicula);
    }


    function delPerfil(){
        $this->load->model('Perfil_model');
        $this->Perfil_model->id = $this->input->post('id');
        $this->Perfil_model->delPerfil();
        echo json_encode(array('resultado'=>'Perfil borrado exitosamente'));
    }


    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        //$cierre = $this->input->post('cierre');
        
        $this->load->model('Perfil_model');
        $this->Perfil_model->archivo = $archivo;
        //$this->Canje_model->cierre = $cierre;
        
        $resultado = $this->Perfil_model->grabarExcel();
        echo json_encode($resultado);
        
    }
}