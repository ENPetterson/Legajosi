<?php

class Cliente_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $apellido;
    public $direccion;
    public $email;    
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveCliente(){
        $cliente = R::load('cliente', $this->id);
        $cliente->nombre = $this->nombre;
        $cliente->apellido = $this->apellido;
        $cliente->direccion = $this->direccion;
        $cliente->email = $this->email;
        $this->id = R::store($cliente);
        return $this->id;
    }
    
    public function getCliente(){
        $cliente = R::load('cliente', $this->id);
        return $cliente->export();
    }
    
    public function getClientesUsuario(){
        $clientes = R::getCol('select cliente_id from cliente_usuario where usuario_id = ?', array($this->usuario_id));
        return $clientes;
    }
    
    public function getClientes(){
        $clientes = R::getAll('select * from cliente order by nombre');
        return $clientes;
    }
    
    public function delCliente(){
        $cliente = R::load('cliente', $this->id);
        R::trash($cliente);
    }
    
    public function assocUsuario(){
        $cliente = R::load('cliente', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($cliente, $usuario);
    }
    
    public function clearRelMenu(){
        $cliente = R::load('cliente', $this->id);
        R::clearRelations($cliente, 'menu');
    }
    
    public function assocMenu(){
        $cliente = R::load('cliente', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($cliente, $menu);
    }
    
    public function clearRelControlador(){
        $cliente = R::load('cliente', $this->id);
        R::clearRelations($cliente, 'controlador');
    }
    
    public function assocControlador(){
        $cliente = R::load('cliente', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($cliente, $controlador);
    }
    
    
}