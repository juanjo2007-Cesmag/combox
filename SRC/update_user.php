<?php
require('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: list_users.php");
    exit;
}

$id           = $_POST['id'];
$firstname    = trim($_POST['firstname']);
$lastname     = trim($_POST['lastname']);
$email        = trim($_POST['email']);
$mobile_phone = trim($_POST['mobile_phone']);

// Consultar la foto actual antes de actualizar (para el reto opcional de unlink)
$sql_prev = 'SELECT photo_url FROM users WHERE id = $1';
$res_prev = pg_query_params($supa_conn, $sql_prev, array($id));
$prev_user = pg_fetch_assoc($res_prev);
$old_photo = $prev_user ? $prev_user['photo_url'] : '';

// PARTE A: Actualizar los datos básicos y updated_at
$sql = 'UPDATE users 
        SET firstname = $1, lastname = $2, email = $3, mobile_phone = $4, updated_at = now() 
        WHERE id = $5';
$result = pg_query_params($supa_conn, $sql, array($firstname, $lastname, $email, $mobile_phone, $id));

if (!$result) {
    echo "Error al actualizar los datos. <a href='edit_user.php?id=$id'>Volver</a>";
    exit;
}

// PARTE B: Procesar la foto solo si el usuario seleccionó un archivo
if (isset($_FILES['photo']) && $_FILES['photo']['name'] != "") {
    
    // Verificar errores de subida de PHP
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        echo "Error al subir el archivo (código: " . $_FILES['photo']['error'] . "). <a href='edit_user.php?id=$id'>Volver</a>";
        exit;
    }

    // Reto opcional: rechazar fotos de más de 1 MB (1048576 bytes)
    if ($_FILES['photo']['size'] > 1048576) {
        echo "La foto supera el tamaño máximo permitido de 1 MB. <a href='edit_user.php?id=$id'>Volver</a>";
        exit;
    }

    // Extraer extensión en minúsculas
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    if ($ext == "jpg" || $ext == "jpeg" || $ext == "png") {
        $photo_url = "photos/" . $mobile_phone . "." . $ext;

        // Mover de carpeta temporal a src/photos/
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_url)) {
            
            // Reto opcional: si tenía una foto anterior con distinta ruta/extensión, eliminarla
            if (!empty($old_photo) && $old_photo !== $photo_url && file_exists($old_photo)) {
                unlink($old_photo);
            }

            // Guardar la ruta en la base de datos
            $sql_photo = 'UPDATE users SET photo_url = $1, updated_at = now() WHERE id = $2';
            $res_photo = pg_query_params($supa_conn, $sql_photo, array($photo_url, $id));

            if (!$res_photo) {
                echo "La foto se guardó en la carpeta, pero falló al actualizar photo_url en la BD.";
                exit;
            }
        } else {
            echo "No se pudo guardar la foto. Revisa que exista la carpeta src/photos y tenga permisos.";
            exit;
        }
    } else {
        echo "Solo se permiten fotos JPG o PNG. <a href='edit_user.php?id=$id'>Volver</a>";
        exit;
    }
}

// PARTE C: Redirigir a la lista
header("Location: list_users.php");
exit;