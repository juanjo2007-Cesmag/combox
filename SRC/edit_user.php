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
    <link rel="icon" type="image/png" href="icons/icono.png">
    <!-- Bootstrap 5 y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Usuario</h4>
                        <small class="opacity-75">Actualiza los datos y la fotografía de perfil</small>
                    </div>

                    <div class="card-body p-4">
                        <form action="update_user.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

                            <!-- Foto de perfil actual / Vista previa -->
                            <div class="text-center mb-4">
                                <div class="mb-2">
                                    <?php if (!empty($user['photo_url'])): ?>
                                        <img
                                            id="previewImg"
                                            src="<?php echo htmlspecialchars($user['photo_url']) . '?v=' . time(); ?>"
                                            alt="Foto actual"
                                            width="110"
                                            height="110"
                                            class="rounded-circle border shadow-sm"
                                            style="object-fit: cover;"
                                            onerror="this.style.display='none'; document.getElementById('defaultAvatar').style.display='inline-block';"
                                        >
                                        <i id="defaultAvatar" class="bi bi-person-circle text-secondary" style="display: none; font-size: 95px; line-height: 1;"></i>
                                    <?php else: ?>
                                        <img
                                            id="previewImg"
                                            src=""
                                            alt="Vista previa"
                                            width="110"
                                            height="110"
                                            class="rounded-circle border shadow-sm"
                                            style="display: none; object-fit: cover;"
                                        >
                                        <i id="defaultAvatar" class="bi bi-person-circle text-secondary" style="font-size: 95px; line-height: 1;"></i>
                                    <?php endif; ?>
                                </div>

                                <label for="photo" class="form-label fw-semibold mb-1">Cambiar fotografía</label>
                                <input
                                    type="file"
                                    class="form-control form-control-sm"
                                    id="photo"
                                    name="photo"
                                    accept=".jpg, .jpeg, .png"
                                >
                                <div class="form-text">Formatos permitidos: JPG o PNG (máximo 1 MB).</div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="firstname" class="form-label fw-semibold">Nombre</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="firstname"
                                        name="firstname"
                                        value="<?php echo htmlspecialchars($user['firstname']); ?>"
                                        required
                                    >
                                </div>

                                <div class="col-sm-6">
                                    <label for="lastname" class="form-label fw-semibold">Apellido</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="lastname"
                                        name="lastname"
                                        value="<?php echo htmlspecialchars($user['lastname']); ?>"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        value="<?php echo htmlspecialchars($user['email']); ?>"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="mobile_phone" class="form-label fw-semibold">Celular</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="mobile_phone"
                                        name="mobile_phone"
                                        value="<?php echo htmlspecialchars($user['mobile_phone']); ?>"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <a href="list_users.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-lg me-1"></i>Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para vista previa de foto y validación de 1 MB -->
    <script>
        document.getElementById('photo').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 1048576) {
                alert('La imagen supera el tamaño máximo permitido de 1 MB.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                const preview = document.getElementById('previewImg');
                const avatar = document.getElementById('defaultAvatar');
                preview.src = event.target.result;
                preview.style.display = 'inline-block';
                if (avatar) avatar.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    </script>

</body>
</html>