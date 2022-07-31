<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrdenTest extends CI_Controller {

    function index() {


        $this->load->model('Orden_model');
        
        $this->Orden_model->id = 1;
        $this->Orden_model->nombre = 'Mamu';
        $this->Orden_model->sucursal = 'Quilmes';
        $this->Orden_model->fecha = 'Anteayer';
        $orden = $this->Orden_model->saveOrden();
        
        echo "Dada de alta la Orden con el id $orden <br>";
        

        $this->Orden_model->id = 2;
        $this->Orden_model->nombre = 'Panthom';
        $this->Orden_model->sucursal = 'Wilde';
        $this->Orden_model->fecha = 'Ayer';
        $orden = $this->Orden_model->saveOrden();
        
        echo "Dada de alta la Orden con el id $orden <br>";

        $this->Orden_model->id = 3;
        $this->Orden_model->nombre = 'Cirilo';
        $this->Orden_model->sucursal = 'Constitucion';
        $this->Orden_model->fecha = 'Ayer';
        $orden = $this->Orden_model->saveOrden();
        
        echo "Dada de alta la Orden con el id {$orden} <br>";


        $this->Orden_model->id = 2;
        $this->Orden_model->nombre = 'Este hizo Update';
        $this->Orden_model->sucursal = 'Este hizo update';
        $this->Orden_model->fecha = 'Este hizo update';
        $orden = $this->Orden_model->saveOrden();
        
        echo "Dada de alta la Orden con el id $orden <br>";



        $orden = R::dispense('orden');
        
        $orden->nombre = 'Charan';
        $orden->sucursal = 'Lanus';
        $orden->fecha = 'Anteyaer';
        
        R::store($orden);


        $orden = R::dispense('orden');
        
        $orden->nombre = 'Gru';
        $orden->sucursal = 'Avellaneda';
        $orden->fecha = 'Hoy';

        R::store($orden);




       
        
        $sql = "INSERT INTO orden (nombre, sucursal, fecha) VALUES ('Africana', 'Constitucion', 'Hoy');";

        R::exec($sql);
        
        echo "Creado el orden <br />";

        
    }
    
}