<?php

class DetalleOrdenController extends Controller{
    
    private $detalleOrdenModelo;

    public function __construct(){
        $this->detalleOrdenModelo=$this->model('DetalleOrdenDao');  
    }

    public function mensajeWsp($idCliente){
        $ordenModel = $this->model('OrdenDao');
        $idOrden = $ordenModel->idOrdenFinalCliente($idCliente);

        $datos = $this->detalleOrdenModelo->detalleFusion($idCliente, $idOrden);
        if(is_null($datos)){
            echo $this->detalleOrdenModelo->getMensaje();
        }else{
            echo json_encode($datos);
        }

    }

    public function detalleOrdenInsert(){
        $ordenModel = $this->model('OrdenDao');
        $idCliente = $_POST["idCliente"];
        $idOrden = $ordenModel->idOrdenFinalCliente($idCliente);

        if(is_null($idOrden)){
            echo $ordenModel->getMensaje();
        }else{
            $idProducto = $_POST["idProducto"];
            $precioUnit = $_POST["precioUnit"];
            $totalDetalle = $_POST["totalDetalle"];
            $cantidad = $_POST["cantidad"];

            $detalleOrden = new DetalleOrden;
            $detalleOrden->setIdOrden($idOrden);
            $detalleOrden->setIdProducto($idProducto);
            $detalleOrden->setPrecioUnit($precioUnit);
            $detalleOrden->setTotalDetalle($totalDetalle);
            $detalleOrden->setCantidad($cantidad);

            $mensaje = $this->detalleOrdenModelo->detalleOrdenInsert($detalleOrden);

            echo $mensaje;
        }
    }

}