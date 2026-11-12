
<?php
    require('../config/database.php');

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: list_users.php");
        exit;
    }

    $id = $_GET['id'];

    $sql = 'SELECT id, firstname, lastname, email, mobile_phone, photo_url FROM users WHERE id = $1';
    $result = pg_query_params($supa_conn, $sql, array($id));
    $user = pg_fetch_assoc($result);

    if (!$user) {
        header("Location: list_users.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combox - Editar Usuario</title>
</head>

<body>

    <h2>Editar Usuario</h2>
    <p>Actualiza los datos y la fotografía de perfil</p>

    <form action="update_user.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

        <div>
            <label for="firstname">Nombre:</label>
            <input
                type="text"
                id="firstname"
                name="firstname"
                value="<?php echo htmlspecialchars($user['firstname']); ?>"
                required
            >
        </div>

        <br>

        <div>
            <label for="lastname">Apellido:</label>
            <input
                type="text"
                id="lastname"
                name="lastname"
                value="<?php echo htmlspecialchars($user['lastname']); ?>"
                required
            >
        </div>

        <br>

        <div>
            <label for="email">Correo electrónico:</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?php echo htmlspecialchars($user['email']); ?>"
                required
            >
        </div>

        <br>

        <div>
            <label for="mobile_phone">Celular:</label>
            <input
                type="tel"
                id="mobile_phone"
                name="mobile_phone"
                value="<?php echo htmlspecialchars($user['mobile_phone']); ?>"
                required
            >
        </div>

        <br>

        <?php if (!empty($user['photo_url'])) { ?>

            <div>
                <label>Foto actual:</label>
                <br>
                <img
                    src="<?php echo htmlspecialchars($user['photo_url']) . '?v=' . time(); ?>"
                    alt="Foto actual"
                    width="100"
                >
            </div>

            <br>

        <?php } ?>

        <div>
            <label for="photo">Nueva foto (JPG o PNG, máximo 1 MB):</label>
            <input
                type="file"
                id="photo"
                name="photo"
                accept=".jpg, .jpeg, .png"
            >
        </div>

        <br>

        <button type="submit">Guardar cambios</button>

    </form>

    <br>

    <a href="list_users.php">Cancelar y volver a la lista</a>

</body>
</html>
