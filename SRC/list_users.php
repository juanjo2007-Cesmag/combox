<?php
// Silenciar cualquier echo generado por database.php
ob_start();
require('../config/database.php');
ob_end_clean();

// Consulta SQL (incluyendo el id para las futuras acciones de ver, editar y borrar)
$sql = "
    SELECT
        u.id,
        u.firstname,
        u.lastname,
        u.mobile_phone,
        u.email,
        CASE
            WHEN u.status = true THEN 'Active'
            ELSE 'Inactive'
        END AS user_status,
        u.photo_url
    FROM users u
    ORDER BY u.lastname ASC
";

// Ejecutar consulta
$res = pg_query($supa_conn, $sql);

// Verificar consulta
if (!$res) {
    die("Error en la consulta: " . pg_last_error($supa_conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Users - Combox</title>
</head>

<body>

<center>
    <h3>List All Users</h3>

    <table border="1" align="center" cellpadding="8" cellspacing="0">
        <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Mobile_phone</th>
            <th>Email</th>
            <th>Status</th>
            <th>Photo</th>
            <th>Actions</th>
        </tr>

        <?php
        $default_img = "photos_users/user_default.png";

        while ($row = pg_fetch_assoc($res)) {
            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['firstname'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['lastname'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['mobile_phone'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['user_status']) . "</td>";

            // Columna de Foto
            echo "<td align='center'>";
            if (!empty($row['photo_url'])) {
                echo "<img src='" . htmlspecialchars($row['photo_url']) . "' width='40' height='40' style='object-fit: cover; border-radius: 4px;' onerror=\"this.onerror=null; this.src='$default_img';\">";
            } else {
                echo "<img src='" . $default_img . "' width='40' height='40' style='object-fit: cover; border-radius: 4px;'>";
            }
            echo "</td>";

            // Columna de Acciones (Search, Edit, Trash)
            echo "<td align='center'>";
            echo "<a href='#' title='Search / View' style='margin-right: 8px;'><img src='icons/search.png' width='20' height='20' alt='Search' style='vertical-align: middle; cursor: pointer;'></a>";
            echo "<a href='#' title='Edit' style='margin-right: 8px;'><img src='icons/edit.png' width='20' height='20' alt='Edit' style='vertical-align: middle; cursor: pointer;'></a>";
            echo "<a href='#' title='Delete' onclick=\"return confirm('¿Desea eliminar este usuario?');\"><img src='icons/trash.png' width='20' height='20' alt='Delete' style='vertical-align: middle; cursor: pointer;'></a>";
            echo "</td>";

            echo "</tr>";
        }
        ?>
    </table>
</center>

</body>
</html>