<?php

require_once('../app/model/dto/DetalleOrden.php');

class DetalleOrdenDao{

    private $connection;
    private $mensaje;

    public function __construct(){
        $this->connection = new Connection;        
    }

    public function detalleFusion($idCliente, $idOrden){
        $sql = "SELECT c.nombreCliente, c.numTelef, o.direccionOrden, o.total, o.tipoServicio, do.cantidad, do.totalDetalle, p.nombreProducto FROM Cliente as c INNER JOIN orden as o ON c.idCliente = o.idCliente INNER JOIN detalleorden as do ON o.idOrden = do.idOrden INNER JOIN producto as p ON do.idProducto = p.idProducto WHERE c.idCliente = :idCliente AND o.idOrden = :idOrden";
        $datos = NULL;
        try {
            $puente = $this->connection->getConnection();
            $consultaPreparada = $puente->prepare($sql);
            $consultaPreparada->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $consultaPreparada->bindParam(':idOrden', $idOrden, PDO::PARAM_INT);
            if ($consultaPreparada->execute()) {
                $datos = array();
                while ($fila = $consultaPreparada->fetch(PDO::FETCH_ASSOC)) {
                    $elemento = [];
                    $elemento[] = $fila["nombreCliente"];
                    $elemento[] = $fila["numTelef"];
                    $elemento[] = $fila["direccionOrden"];
                    $elemento[] = $fila["total"];
                    $elemento[] = $fila["tipoServicio"];
                    $elemento[] = $fila["cantidad"];
                    $elemento[] = $fila["totalDetalle"];
                    $elemento[] = $fila["nombreProducto"];
                    $datos[] = $elemento;
                }
                if (count($datos) === 0) {
                    $this->mensaje = "No existen registros en esta consulta";
                }
            } else {
                $this->mensaje = "Error en la ejecucion de la consulta";
            }
            $consultaPreparada->closeCursor();
        } catch (Exception $e) {
            $this->mensaje = $e->getMessage();
        } finally {
            $puente = NULL;
        }
        return $datos;
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