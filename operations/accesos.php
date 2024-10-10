<?php

include "conexionDB.php";
session_start();

$option = $_POST['option'];

switch ($option) {
    case 0:
        registro($conn);
        break;
    case 1:
        login($conn);
        break;
    case 2:
        recuperar($conn);
        break;
    case 3:
        logout();
        break;
    case 4:
        getUserData($conn);

    default:
        # code...
        break;
}

function registro($conn)
{
    $nombre = isset($_POST["FirstName"]) ? $_POST["FirstName"] : "";
    $apellidos = isset($_POST["LastName"]) ? $_POST["LastName"] : "";
    $email = isset($_POST["InputEmail"]) ? $_POST["InputEmail"] : "";
    $pass = isset($_POST["InputPassword"]) ? $_POST["InputPassword"] : "";
    $repetPass = isset($_POST["RepeatPassword"]) ? $_POST["RepeatPassword"] : "";

    if ($nombre == "" || $apellidos == "" || $email == "" || $pass == "" || $repetPass == "") {
        echo json_encode(['success' => false, 'message' => 'Campos vacios']);
        exit();
    }

    $query = $conn->prepare("SELECT email FROM usuario WHERE email = BINARY ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $query->close();


    if ($pass == $repetPass) {
        if ((mysqli_fetch_assoc($result)) == FALSE) {
            $passen = password_hash($pass, PASSWORD_BCRYPT);
            $sql = "INSERT INTO usuario(nombre, apellidos, email, pass) VALUES
            (?, ?, ?, ?)";

            $query = $conn->prepare($sql);
            $query->bind_param('ssss', $nombre, $apellidos, $email, $passen);

            if ($query->execute()) {
                // Cuenta creada correctamente
                echo json_encode(['success' => true, 'message' => 'Cuenta creada correctamente']);
            } else {
                // Error al insertar
                echo json_encode(['success' => false, 'message' => 'Error al crear la cuenta']);
            }
            $query->close();
        } else {
            // Correo ya registrado
            echo json_encode(['success' => false, 'message' => 'Correo ya registrado']);
        }
    } else {
        // Contraseñas no coinciden
        echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
    }
}

function login($conn)
{
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $pass = isset($_POST["password"]) ? $_POST["password"] : "";

    if ($email == "" || $pass == "") {
        echo json_encode(['success' => false, 'message' => 'Campos vacios']);
        exit();
    }

    $query = $conn->prepare("SELECT id, pass FROM usuario WHERE email = BINARY ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $query->close();

    if ($result = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $result['pass'])) {
            $_SESSION['user_id'] = $result['id'];
            echo json_encode(['success' => true, 'message' => 'Acceso Concedido', 'data' => $result["id"]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos Incorrectos']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos Incorrectos']);
    }
}

function recuperar($conn)
{
    $email = isset($_POST["getEmail"]) ? $_POST["getEmail"] : "";

    if ($email == "") {
        echo json_encode(['success' => false, 'message' => 'Campo vacios']);
        exit();
    }

    $query = $conn->prepare("SELECT pass FROM usuario WHERE email = BINARY ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $query->close();

    if ($result = mysqli_fetch_assoc($result)) {
        $pass = "12345678a";
        $passen = password_hash($pass, PASSWORD_BCRYPT);
        $query = $conn->prepare("UPDATE usuario SET pass = ? WHERE email = ?");
        $query->bind_param("ss", $passen, $email);
        $query->execute();
        echo json_encode(['success' => true, 'message' => 'Contraseña Restablecida a:', 'newPass' => $pass]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos Incorrectos']);
    }
}

function logout()
{
    session_destroy();
    echo json_encode(['success' => true, 'message' => '']);
}

function getUserData($conn)
{
    if (isset($_SESSION["user_id"])) {
        $idUser = $_SESSION['user_id'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Error Autenticandose']);
        exit();
    }

    $query = $conn->prepare("SELECT nombre, apellidos FROM usuario WHERE id = ?");
    $query->bind_param("s", $idUser);
    $query->execute();
    $result = $query->get_result();
    $query->close();

    $result = mysqli_fetch_assoc($result);

    echo json_encode(['success' => true, 'message' => $result]);
}