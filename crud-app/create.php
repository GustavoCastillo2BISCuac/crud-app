<?php
include 'db.php';

$name = $email = '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['name'])) {
        $errors[] = "El nombre es obligatorio.";
    } else {
        $name = trim($_POST['name']);
    }

    if (empty($_POST['email'])) {
        $errors[] = "El email es obligatorio.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email no es válido.";
    } else {
        $email = trim($_POST['email']);
    }

    if (empty($errors)) {
        try {
            $query = "INSERT INTO users (name, email) VALUES (:name, :email)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            header("Location: index.php");
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errors[] = "El email ya está registrado.";
            } else {
                $errors[] = "Error al guardar el usuario: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="create_style.css">
    
</head>
<body>
    <h1>Agregar Usuario</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="create.php" method="POST">
        <label for="name">Nombre:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>
        <input type="submit" value="Guardar">
    </form>
</body>
</html>