<?php

require_once('../app/model/dto/Orden.php');

class OrdenDao{

    private $connection;
    private $mensaje;

    public function __construct(){
        $this->connection = new Connection;
    }

    public function idOrdenFinalCliente($idCliente){
        $sql = "SELECT idOrden FROM orden WHERE idCliente = :idCliente ORDER BY idOrden desc LIMIT 1";
        $idOrden = NULL;
        try {
            $puente = $this->connection->getConnection();
            $consultaPreparada = $puente->prepare($sql);
            $consultaPreparada->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            if ($consultaPreparada->execute()) {
                if ($fila = $consultaPreparada->fetch(PDO::FETCH_ASSOC)) {
                    $idOrden = $fila["idOrden"];
                } else {
                    $this->mensaje = "No se ha encontrado ninguna Orden con el cliente ".$idCliente;
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
        return $idOrden;
    }

    public function detalleFusion(){
        $sql = "SELECT idOrden FROM orden WHERE idCliente = :idCliente ORDER BY idOrden desc LIMIT 1";
        $idOrden = NULL;
        try {
            $puente = $this->connection->getConnection();
            $consultaPreparada = $puente->prepare($sql);
            $consultaPreparada->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            if ($consultaPreparada->execute()) {
                if ($fila = $consultaPreparada->fetch(PDO::FETCH_ASSOC)) {
                    $idOrden = $fila["idOrden"];
                } else {
                    $this->mensaje = "No se ha encontrado ninguna Orden con el cliente ".$idCliente;
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
        return $idOrden;
    }

    public function ordenInsert($Orden){
        $sql = "INSERT INTO Orden(direccionOrden, fechaOrden, total, idCliente, tipoServicio) VALUES (:direccionOrden, :fechaOrden, :total, :idCliente, :tipoServicio)";
        try {
            $puente = $this->connection->getConnection();
            $consultaPreparada = $puente->prepare($sql);
            $direccionOrden = $Orden->getDireccionOrden();
            $fechaOrden = $Orden->getFechaOrden();
            $total = $Orden->getTotal();
            $idCliente = $Orden->getIdCliente();
            $tipoServicio = $Orden->getTipoServicio();
            $consultaPreparada->bindParam(':direccionOrden', $direccionOrden, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':fechaOrden', $fechaOrden, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':total', $total, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $consultaPreparada->bindParam(':tipoServicio', $tipoServicio, PDO::PARAM_STR);
            if ($consultaPreparada->execute()) {
                if ($consultaPreparada->rowCount() === 0) {
                    $this->mensaje = "No se ha insertado ningun Pedido";
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