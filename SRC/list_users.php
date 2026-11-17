<?php
    ob_start();
    require('../config/database.php');
    ob_end_clean();

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
    $total_users = pg_num_rows($res);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Combox - List users</title>
        <link rel="icon" type="image/png" href="icons/icono.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            body {
                background-color: #f4f6f9;
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            }
            .main-card {
                border: none;
                border-radius: 16px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }
            .card-header-custom {
                background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
                color: #fff;
                padding: 1.5rem 1.75rem;
            }
            .table thead th {
                background-color: #f8fafc;
                color: #475569;
                font-size: 0.78rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-bottom: 2px solid #e2e8f0;
                padding: 0.9rem 1rem;
            }
            .table tbody td {
                padding: 0.85rem 1rem;
                color: #1e293b;
                border-color: #f1f5f9;
            }
            .table tbody tr:hover {
                background-color: #f8fafc;
            }
            .avatar-img {
                width: 44px;
                height: 44px;
                object-fit: cover;
                border-radius: 50%;
                border: 2px solid #e2e8f0;
            }
            .avatar-placeholder {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background-color: #e2e8f0;
                color: #64748b;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1.4rem;
            }
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 0.35rem 0.75rem;
                border-radius: 50rem;
                font-size: 0.8rem;
                font-weight: 600;
            }
            .status-active {
                background-color: #dcfce7;
                color: #166534;
            }
            .status-inactive {
                background-color: #f1f5f9;
                color: #475569;
            }
            .status-dot {
                width: 7px;
                height: 7px;
                border-radius: 50%;
            }
            .btn-action {
                width: 34px;
                height: 34px;
                border-radius: 8px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: none;
                transition: all 0.15s ease-in-out;
                text-decoration: none;
            }
            .btn-edit {
                background-color: #eff6ff;
                color: #2563eb;
            }
            .btn-edit:hover {
                background-color: #2563eb;
                color: #fff;
            }
            .btn-view {
                background-color: #ecfdf5;
                color: #059669;
            }
            .btn-view:hover {
                background-color: #059669;
                color: #fff;
            }
            .btn-delete {
                background-color: #fef2f2;
                color: #dc2626;
            }
            .btn-delete:hover {
                background-color: #dc2626;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <div class="container py-5">
            <div class="card main-card">
                <!-- Encabezado -->
                <div class="card-header-custom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h4 class="mb-1 fw-bold">
                            <i class="bi bi-people-fill me-2"></i>Gestión de Usuarios
                        </h4>
                        <span class="opacity-75 small">
                            Total registrados: <strong><?= $total_users ?></strong>
                        </span>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <!-- Buscador rápido -->
                        <div class="input-group input-group-sm" style="min-width: 240px;">
                            <span class="input-group-text bg-white border-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-0" placeholder="Buscar usuario...">
                        </div>

                        <!-- Botón crear usuario (ajusta el href si tu archivo se llama distinto) -->
                        <a href="create_user.php" class="btn btn-light btn-sm fw-semibold text-primary d-inline-flex align-items-center justify-content-center px-3">
                            <i class="bi bi-person-plus-fill me-1"></i>Nuevo
                        </a>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive bg-white">
                    <table class="table align-middle mb-0" id="usersTable">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">Photo</th>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Mobile Phone</th>
                                <th>Email</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total_users > 0): ?>
                                <?php while($row = pg_fetch_assoc($res)): ?>
                                    <tr>
                                        <!-- Lado izquierdo: Foto de usuario o avatar por defecto -->
                                        <td class="text-center">
                                            <?php if (!empty($row['photo_url'])): ?>
                                                <img 
                                                    src="<?= htmlspecialchars($row['photo_url']) . '?v=' . time(); ?>" 
                                                    alt="Avatar"
                                                    class="avatar-img"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"
                                                >
                                                <span class="avatar-placeholder" style="display: none;">
                                                    <i class="bi bi-person-fill"></i>
                                                </span>
                                            <?php else: ?>
                                                <span class="avatar-placeholder">
                                                    <i class="bi bi-person-fill"></i>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="fw-semibold"><?= htmlspecialchars($row['firstname']) ?></td>
                                        <td><?= htmlspecialchars($row['lastname']) ?></td>
                                        <td>
                                            <span class="text-muted small"><i class="bi bi-telephone me-1"></i></span>
                                            <?= htmlspecialchars($row['mobile_phone']) ?>
                                        </td>
                                        <td>
                                            <span class="text-muted small"><i class="bi bi-envelope me-1"></i></span>
                                            <?= htmlspecialchars($row['email']) ?>
                                        </td>
                                        
                                        <td class="text-center">
                                            <?php if ($row['status'] === 'Active'): ?>
                                                <span class="status-badge status-active">
                                                    <span class="status-dot bg-success"></span>Active
                                                </span>
                                            <?php else: ?>
                                                <span class="status-badge status-inactive">
                                                    <span class="status-dot bg-secondary"></span>Inactive
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Lado derecho: Lápiz, Lupa y Papelera -->
                                        <td class="text-center text-nowrap">
                                            <div class="d-inline-flex gap-2">
                                                <!-- Lápiz (Editar) -->
                                                <a href="edit_user.php?id=<?= $row['id'] ?>" title="Editar usuario" class="btn-action btn-edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>

                                                <!-- Lupa (Ver) -->
                                                <a href="view_user.php?id=<?= $row['id'] ?>" title="Ver detalles" class="btn-action btn-view">
                                                    <i class="bi bi-search"></i>
                                                </a>

                                                <!-- Papelera (Eliminar / Desactivar / Cancelar) -->
                                                <button type="button" 
                                                        class="btn-action btn-delete" 
                                                        title="Opciones de eliminación"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal"
                                                        data-userid="<?= $row['id'] ?>"
                                                        data-username="<?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No hay usuarios registrados todavía.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal de Opciones: Eliminar, Desactivar o Cancelar -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Gestionar usuario
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center py-4 px-4">
                        <p class="mb-2 fs-5">¿Qué deseas hacer con <strong id="modalUserName"></strong>?</p>
                        <p class="text-muted small mb-0">
                            Puedes <strong>desactivarlo</strong> para conservar su historial o <strong>eliminarlo</strong> definitivamente de la base de datos.
                        </p>
                    </div>
                    <div class="modal-footer bg-light justify-content-center gap-2 border-0">
                        <!-- Cancelar -->
                        <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </button>

                        <!-- Desactivar -->
                        <a id="btnInactivate" href="#" class="btn btn-warning text-dark fw-semibold px-3">
                            <i class="bi bi-person-slash me-1"></i>Desactivar
                        </a>

                        <!-- Eliminar -->
                        <a id="btnDelete" href="#" class="btn btn-danger fw-semibold px-3">
                            <i class="bi bi-trash-fill me-1"></i>Eliminar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Configuración del Modal
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-userid');
                const userName = button.getAttribute('data-username');

                document.getElementById('modalUserName').textContent = userName;
                document.getElementById('btnInactivate').href = `delete_user.php?userID=${userId}&action=inactivate`;
                document.getElementById('btnDelete').href = `delete_user.php?userID=${userId}&action=delete`;
            });

            // Filtro de búsqueda instantánea en la tabla
            document.getElementById('searchInput').addEventListener('keyup', function () {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#usersTable tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        </script>
    </body>
</html>