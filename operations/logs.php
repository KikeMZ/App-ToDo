<?php
session_start();

function createLog($idUser, $info, $conn)
{
    $infoArray = json_decode($info, true);

    $query = $conn->prepare("INSERT INTO historial (id_user, accion, fecha) VALUES (?, ?, ?)");
    $query->bind_param("sss", $idUser, $infoArray['nombre'], $infoArray['fecha']);
    $query->execute();
    $query->close();
}

function destroyLog($info, $conn) {}
