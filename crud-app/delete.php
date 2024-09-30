<?php
// Incluir la conexión a la base de datos
require 'db.php';

// Verificar si se ha pasado un ID en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la consulta para eliminar el usuario
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$id]);

    // Redirigir a la lista después de eliminar
    header('Location: index.php');
    exit();
} else {
    // Si no se pasa un ID válido, redirigir a la lista
    header('Location: index.php');
    exit();
}
?>
