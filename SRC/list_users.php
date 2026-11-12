<?php
    require('../config/database.php');
    $sql = "SELECT 
                id,
                firstname, 
                lastname, 
                mobile_phone, 
                email, 
                photo_url, 
                case
                    when u.status = true then 'Active' else 'Inactive'
                end as status
            FROM users u
            ORDER BY firstname ASC";
    $res = pg_query($supa_conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Combox - List users</title>
        <link rel="icon" type="image/png" href="icons/icono.png">
    </head>
    <body>
        <center><h3>List all users</h3></center>
        <table border="1" align="center" width="80%" cellpadding="6">
            <tr>
                <th>Photo</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Mobile Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>...</th>
            </tr>
            <?php
                while($row = pg_fetch_assoc($res)){
                    echo "<tr>";
                    
                    // Mostrar foto o texto "Sin foto"
                    echo "<td align='center'>";
                    if (!empty($row['photo_url'])) {
                        echo "<img src='" . htmlspecialchars($row['photo_url']) . "?v=" . time() . "' width='50' height='50' style='object-fit: cover; border-radius: 50%;'>";
                    } else {
                        echo "Sin foto";
                    }
                    echo "</td>";

                    echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['mobile_phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td align='center'>
                            <img src='photos/search.png' width='15'>&nbsp;&nbsp;
                            <a href='edit_user.php?id=" . $row['id'] . "' title='Editar usuario'>
                                <img src='photos/edit.png' width='15'>
                            </a>&nbsp;&nbsp;
                            <img src='photos/delete.png' width='15'>
                        </td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </body>
</html>