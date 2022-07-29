<?php

require_once('../app/model/dto/DetalleOrden.php');

class DetalleOrdenDao{

    private $connection;
    private $mensaje;

    public function __construct(){
        $this->connection = new Connection;        
    }

    public function detalleOrdenInsert($detalleOrden){
        $sql = "INSERT INTO detalleorden VALUES (:idOrden, :idProducto, :precioUnit, :totalDetalle, :cantidad)";
        try {
    $puente = $this->connection->getConnection();
            $consultaPreparada = $puente->prepare($sql);
            $idOrden = $detalleOrden->getIdOrden();
            $idProducto = $detalleOrden->getIdProducto();
            $precioUnit = $detalleOrden->getPrecioUnit();
            $totalDetalle = $detalleOrden->getTotalDetalle();
            $cantidad = $detalleOrden->getCantidad();
            $consultaPreparada->bindParam(':idOrden', $idOrden, PDO::PARAM_INT);
            $consultaPreparada->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
            $consultaPreparada->bindParam(':precioUnit', $precioUnit, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':totalDetalle', $totalDetalle, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':cantidad', $cantidad, PDO::PARAM_STR);
            if ($consultaPreparada->execute()) {
                if ($consultaPreparada->rowCount() === 0) {
                    $this->mensaje = "No se ha insertado ningun Detalle";
                }else{
                    $this->mensaje = "Se ha registrado correctamente";
                }
            }
            $consultaPreparada->closeCursor();
        } catch (Exception $e) {
            $this->mensaje = $e->getMessage();
        } finally {
            $puente = NULL;
        }
        return $this->mensaje;
    }

    public function getMensaje(){
        return $this->mensaje;
    }
}