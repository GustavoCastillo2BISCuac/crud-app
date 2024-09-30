<?php
// Incluir la conexión a la base de datos (Asegúrate de que el archivo db.php contenga la conexión con la variable $pdo)
require 'db.php'; 

// Inicializar variables
$errors = [];
$name = '';
$email = '';

// Verificar si se ha pasado un ID en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del usuario actual para precargar el formulario
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $name = $user['name'];
        $email = $user['email'];
    } else {
        // Si no existe el usuario, redirigir a la lista
        header('Location: index.php');
        exit();
    }
} else {
    // Si no se pasa un ID válido, redirigir a la lista
    header('Location: index.php');
    exit();
}

// Procesar el formulario al enviar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Validar los datos ingresados
    if (empty($name)) {
        $errors[] = 'El nombre es obligatorio.';
    }

    if (empty($email)) {
        $errors[] = 'El email es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'El formato de email no es válido.';
    }

    // Si no hay errores, actualizar los datos en la base de datos
    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
        $stmt->execute([$name, $email, $id]);

        // Redirigir a la lista después de actualizar
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
    <link rel="stylesheet" href="create_style.css"> <!-- Usar el CSS del formulario de creación -->
</head>
<body>
    <div class="container">
        <h1>Actualizar Usuario</h1>

        <!-- Mostrar errores si existen -->
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulario de actualización -->
        <form action="update.php?id=<?php echo $id; ?>" method="POST">
            <label for="name">Nombre:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <input type="submit" value="Actualizar">
        </form>

        <a class="back-link" href="index.php">Volver a la lista</a>
    </div>
</body>
</html>