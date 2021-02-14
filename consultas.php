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

            $sql = "SELECT * FROM `clientes` WHERE `users_id` = ".$rows['id'];
            $statement = $conexion->prepare($sql);
            $statement->execute();
            $cliente = $statement->fetch();
            if ($cliente){
                $rows['datos_cliente'] = true;
            }else{
                $rows['datos_cliente'] = false;
            }

            if (password_verify($password, $rows['password'])) {
                $rows['success'] = true;
                return $rows;
            }else{
                $rows['success'] = false;
                $rows['error'] = "password";
                return $rows;
            }

        } else {
            $rows['success'] = false;
            $rows['error'] = "email";
            return $rows;
        }
    }

    public function registrar($name, $email, $password, $telefono)
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
            VALUES (NULL, :name, :email, NULL, :password, :telefono, NULL, NULL, NULL, NULL, :plataforma, :fecha, :fecha);";
        $statement = $conexion->prepare($sql);
        $statement->bindParam(":name", $name);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":telefono", $telefono);
        $statement->bindParam(":plataforma", $plataforma);
        $statement->bindParam(":fecha", $date);
        if ($statement->execute()) {

            $sql = "SELECT * FROM `users` WHERE `email` = :valor";
            $statement = $conexion->prepare($sql);
            $statement->bindParam(":valor", $email);
            $statement->execute();
            $rows = $statement->fetch();
            $rows['datos_cliente'] = false;
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

    public function recuperarPassword($email)
    {
        $rows = null;
        $resuldato = true;
        $database = new Conexion();
        $conexion = $database->get_conexion();
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $statement = $conexion->prepare($sql);
        $statement->execute();
        $rows = $statement->fetch();
        if ($rows) {
            $id = $rows['id'];
            $two_factor_recovery_codes = $rows['two_factor_recovery_codes'];
             $dia = date("i");
             if (!empty($two_factor_recovery_codes)){
                 if ($dia == $two_factor_recovery_codes){
                     $resuldato = false;
                 }
             }
            if ($resuldato) {
                $nuevo_password = $this->generate_string(8);
                $nueva = password_hash($nuevo_password, PASSWORD_DEFAULT);
                $sql = "UPDATE `users` SET `password` = :valor , `two_factor_recovery_codes` = :dia WHERE `id`= :id";
                $statement = $conexion->prepare($sql);
                $statement->bindParam(":valor", $nueva);
                $statement->bindParam(":dia", $dia);
                $statement->bindParam(":id", $id);
                if ($statement->execute()) {
                    return $nuevo_password;
                } else {
                    return false;
                }
            }else{
                return "true";
            }
        } else {
            return "false";
        }
    }
	
	/*public function recuperarPassword($id, $nuevo_password)
    {
        $rows = null;
        $database = new Conexion();
        $conexion = $database->get_conexion();
        $nueva = password_hash($nuevo_password, PASSWORD_DEFAULT);
        $sql = "UPDATE `users` SET `password` = :valor WHERE `id`= :id";
        $statement = $conexion->prepare($sql);
        $statement->bindParam(":valor", $nueva);
        $statement->bindParam(":id", $id);
        if ($statement->execute()) {
            return true;
        } else {
			return false;
        }
    }
	*/
    function generate_string($strength = 16) {
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
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