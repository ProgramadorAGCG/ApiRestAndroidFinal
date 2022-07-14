<?php

require_once('../app/model/dto/Cliente.php');

class ClienteDao{

    private $connection;
    private $mensaje;

    public function __construct() {
        $this->connection=new Connection;
    }

    public function clienteLogin($correo, $password){
        $sql = "SELECT idCliente, nombreCliente, direccion, numTelef, correo, password FROM Cliente WHERE correo = :correo AND password = AES_ENCRYPT(:password, :password)";
        $Cliente = NULL;
        try {
            $puente = $this->connection->getConnection();
            $consultaPreparada = $puente->prepare($sql);
            $consultaPreparada->bindParam(':correo', $correo, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':password',$password, PDO::PARAM_STR);
            if ($consultaPreparada->execute()) {
                if ($fila = $consultaPreparada->fetch(PDO::FETCH_ASSOC)) {
                    $Cliente=new Cliente;
                    $Cliente->setIdCliente($fila["idCliente"]);
                    $Cliente->setNombreCliente($fila["nombreCliente"]);
                    $Cliente->setDireccion($fila["direccion"]);
                    $Cliente->setNumTelef($fila["numTelef"]);
                    $Cliente->setCorreo($fila["correo"]);
                } else {
                    $this->mensaje = "Usuario o contraseña incorrecta";
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
        return $Cliente;
    }

    public function clientePerfil($idCliente){
        $sql = "SELECT idCliente, nombreCliente, direccion, numTelef, correo, password FROM Cliente WHERE idCliente = :idCliente";
        $Cliente = NULL;
        try {
            $puente = $this->connection->getConnection();
            $consultaPreparada = $puente->prepare($sql);
            $consultaPreparada->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            if ($consultaPreparada->execute()) {
                if ($fila = $consultaPreparada->fetch(PDO::FETCH_ASSOC)) {
                    $Cliente=new Cliente;
                    $Cliente->setIdCliente($fila["idCliente"]);
                    $Cliente->setNombreCliente($fila["nombreCliente"]);
                    $Cliente->setDireccion($fila["direccion"]);
                    $Cliente->setNumTelef($fila["numTelef"]);
                    $Cliente->setCorreo($fila["correo"]);
                } else {
                    $this->mensaje = "No se ha encontrado ningún cliente con el id ".$idCliente;
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
        return $Cliente;
    }

    public function clienteIns($Cliente){
        $sql = "INSERT INTO cliente(nombreCliente, direccion, numTelef, correo, password) VALUES(:nombreCliente,:direccion, :numTelef, :correo, AES_ENCRYPT(:password, :password));";
        try {
            $puente = $this->connection->getConnection();
            $consultaPreparada = $puente->prepare($sql);
            $nombreCliente = $Cliente->getNombreCliente();
            $direccion = $Cliente->getDireccion();
            $numTelef = $Cliente->getNumTelef();
            $correo = $Cliente->getCorreo();
            $contraseña = $Cliente->getPassword();
            $consultaPreparada->bindParam(':nombreCliente', $nombreCliente, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':numTelef', $numTelef, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':correo', $correo, PDO::PARAM_STR);
            $consultaPreparada->bindParam(':password', $contraseña, PDO::PARAM_STR);
            if ($consultaPreparada->execute()) {
                if ($consultaPreparada->rowCount() === 0) {
                    $this->mensaje = "No se ha insertado ningun Cliente";
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