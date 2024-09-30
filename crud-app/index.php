<?php
include 'db.php';

$limit = 5; // Número de usuarios por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Contar el total de registros
$total_query = "SELECT COUNT(*) FROM users WHERE name LIKE :search OR email LIKE :search";
$stmt = $pdo->prepare($total_query);
$stmt->execute(['search' => "%$search%"]);
$total_users = $stmt->fetchColumn();

// Obtener usuarios paginados
$query = "SELECT * FROM users WHERE name LIKE :search OR email LIKE :search LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
$stmt->bindValue('limit', $limit, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->bindValue('search', "%$search%", PDO::PARAM_STR);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular el total de páginas
$total_pages = ceil($total_users / $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Avanzado en PHP y MySQL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Lista de Usuarios</h1>
    <form action="index.php" method="GET">
        <input type="text" name="search" placeholder="Buscar por nombre o email" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Buscar">
    </form>
    <a href="create.php">Agregar Usuario</a>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td>
                            <a href="update.php?id=<?php echo $user['id']; ?>">Editar</a>
                            <a href="delete.php?id=<?php echo $user['id']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No se encontraron usuarios.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="index.php?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>