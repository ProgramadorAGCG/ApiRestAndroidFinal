<?php

class ClienteController extends Controller{

    private $clienteModelo;

    public function __construct(){
        $this->clienteModelo=$this->model('ClienteDao');  
    }

    public function clientePerfil($id){
        $cliente = $this->clienteModelo->clientePerfil($id);
        if(is_null($cliente))
            echo $this->clienteModelo->getMensaje();
        else
            echo json_encode(["cliente"=>$cliente]);
    }

    public function clienteLogin(){
        $correo = $_POST["correo"];
        $password = $_POST["password"];
        $cliente = $this->clienteModelo->clienteLogin($correo, $password);
        if(is_null($cliente))
            echo $this->clienteModelo->getMensaje();
        else
            echo json_encode(["cliente"=>$cliente]);
    }

    public function clienteInsert(){
        $cliente = new Cliente;
        $nombreCliente = $_POST["nombreCliente"];
        $direccion = $_POST["direccion"];
        $numTelef = $_POST["numTelef"];
        $correo = $_POST["correo"];
        $password = $_POST["password"];
        $cliente->setNombreCliente($nombreCliente);
        $cliente->setDireccion($direccion);
        $cliente->setNumTelef($numTelef);
        $cliente->setCorreo($correo);
        $cliente->setPassword($password);
        $mensaje = $this->clienteModelo->clienteIns($cliente);
        echo $mensaje;
    }

    public function index(){
        echo "Hola mundo";
    }
}
