<?php /** @noinspection ALL */
require_once "conexion.php";
date_default_timezone_set('America/Caracas');

class Consultas
{


    public function login($email, $password)
    {
        $rows = null;
        $database = new Conexion();
        $conexion = $database->get_conexion();
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $statement = $conexion->prepare($sql);
        $statement->execute();
        $rows = $statement->fetch();
        if ($rows) {
            if (password_verify($password, $rows['password'])) {
                return $rows;
            }
            return false;
        } else {
            return false;
        }
    }

    public function registrar($name, $email, $password)
    {
        $database = new Conexion();
        $conexion = $database->get_conexion();

        $password = password_hash($password, PASSWORD_DEFAULT);
        $date = date("Y-m-d H:i:s");
        $plataforma = 1;

        $usuario = "select * from `users` where email = $email";
        if ($conexion->query($usuario)) {
            return false;
        }

        $sql = "INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, 
        `two_factor_recovery_codes`, `remember_token`, `current_team_id`, `profile_photo_path`, `plataforma`, `created_at`, `updated_at`) 
            VALUES (NULL, :name, :email, NULL, :password, NULL, NULL, NULL, NULL, NULL, :plataforma, :fecha, :fecha);";
        $statement = $conexion->prepare($sql);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":plataforma", $plataforma);
        $statement->bindParam(":fecha", $date);
        if ($statement->execute()) {

            $sql = "SELECT * FROM `users` WHERE `email` = :valor";
            $statement = $conexion->prepare($sql);
            $statement->bindParam(":valor", $email);
            $statement->execute();
            $rows = $statement->fetch();

            return $rows;
        } else {
            return false;
        }
    }

    public function update($campo, $valor, $id)
    {
        $database = new Conexion();
        $conexion = $database->get_conexion();

        $sql = "SELECT * FROM `users` WHERE `$campo` = :valor AND `id`= :id";
        $statement = $conexion->prepare($sql);
        $statement->bindParam(":valor", $valor);
        $statement->bindParam(":id", $id);
        $statement->execute();
        $rows = $statement->fetch();
        if (!$rows){

            $sql = "UPDATE `users` SET $campo = :valor WHERE `id`= :id";
            $statement = $conexion->prepare($sql);
            $statement->bindParam(":valor", $valor);
            $statement->bindParam(":id", $id);
            if ($statement->execute()) {
                return "update";
            } else {
                return "error";
            }

        }else{
            return "sin cambios";
        }

    }

    public function updatePassword($password, $nuevo_password, $id)
    {
        $nueva = password_hash($nuevo_password, PASSWORD_DEFAULT);
        $rows = null;
        $database = new Conexion();
        $conexion = $database->get_conexion();

        $sql = "SELECT * FROM `users` WHERE `id` = '$id'";
        $statement = $conexion->prepare($sql);
        $statement->execute();
        $rows = $statement->fetch();
        if ($rows) {

            if (password_verify($password, $rows['password'])) {

                if ($password != $nuevo_password){
                    $sql = "UPDATE `users` SET `password` = :valor WHERE `id`= :id";
                    $statement = $conexion->prepare($sql);
                    $statement->bindParam(":valor", $nueva);
                    $statement->bindParam(":id", $id);
                    if ($statement->execute()) {
                        return "update";
                    } else {
                        return "error";
                    }
                }
                return false;
            }else{
                return "incorrecto";
            }
        } else {
            return false;
        }

    }


    /*    public function listar_usuarios()
        {
            $rows = null;
            $database = new Conexion();
            $conexion = $database->get_conexion();
            $sql = "select * from `users`";
            $statement = $conexion->prepare($sql);
            $statement->execute();
            while ($result = $statement->fetch()) {
                $rows[] = $result;
            }
            return ($rows);
        }*/

//	public function modificar_course_datails($arg_campo, $arg_valor, $arg_id){
//		$database = new Conexion();
//		$conexion = $database->get_conexion();
//		$sql = "UPDATE `course_details` SET $arg_campo = :valor WHERE `id`= :id";
//		$statement = $conexion->prepare($sql);
//		$statement->bindParam(":valor", $arg_valor);
//		$statement->bindParam(":id", $arg_id);
//		if($statement){
//			$statement->execute();
//			return(true);
//		}else{
//			return(false);
//		}
//	}
//		
//	public function borrar_course_datails($arg_id){
//		$database = new Conexion();
//		$conexion = $database->get_conexion();
//		$sql = "delete from `course_details` where `id`='$arg_id'";
//		$statement = $conexion->prepare($sql);
//		if($statement){
//			$statement->execute();
//			return(true);
//		}else{
//			return(false);
//		}
//	}
////	

}

?>