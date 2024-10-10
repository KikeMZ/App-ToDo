<?php
include "conexionDB.php";
include "logs.php";

$option = $_POST['op'];

switch ($option) {
    case 0:
        loadData($conn);
        break;
    case 1:
        newTarea($conn);
        break;
    case 2:
        recuperar($conn);
        break;

    default:
        # code...
        break;
}

function loadData($conn)
{
    if (isset($_SESSION["user_id"])) {
        $idUser = $_SESSION['user_id'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Error Autenticandose']);
        exit();
    }

    $query = $conn->prepare("SELECT id, nombre, descrip, fecha, materia, estado FROM tareas WHERE id_user = ? ORDER BY fecha DESC");
    $query->bind_param("s", $idUser);
    $query->execute();
    $result = $query->get_result();

    $tareas = [];  // Array para almacenar todas las tareas

    // Recorrer todas las filas de resultados
    while ($row = $result->fetch_assoc()) {
        $tareas[] = $row;
    }

    $query->close();

    if (count($tareas) > 0) {
        echo json_encode(['success' => true, 'message' => $tareas]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Sin tareas']);
    }
}

function newTarea($conn)
{
    $nombre = isset($_POST["nombreT"]) ? $_POST["nombreT"] : "";
    $descripcion = isset($_POST["descripcionT"]) ? $_POST["descripcionT"] : "";
    $materia = isset($_POST["materiaT"]) ? $_POST["materiaT"] : "";

    if ($nombre == "" || $descripcion == "" || $materia == "") {
        echo json_encode(['success' => false, 'message' => 'Campos vacios']);
        exit();
    }

    if (isset($_SESSION["user_id"])) {
        $idUser = $_SESSION['user_id'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Error Autenticandose']);
        exit();
    }

    date_default_timezone_set('America/Mexico_City');

    $datetime = new DateTime();
    $getDatetime = $datetime->format('Y-m-d H:i:s');

    $query = $conn->prepare("INSERT INTO tareas (id_user, nombre, descrip, fecha, materia, estado) VALUES (?, ?, ?, ?, ?, 0)");
    $query->bind_param("sssss", $idUser, $nombre, $descripcion, $getDatetime, $materia);
    $query->execute();

    if ($query->affected_rows > 0) {
        $info = json_encode(['nombre' => $nombre, 'fecha' => $getDatetime]);
        createLog($idUser, $info, $conn);

        echo json_encode(['success' => true, 'message' => 'Registro insertado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo crear la tarea']);
    }
    $query->close();
}
