<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'agarralapala');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID de la oferta a borrar
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Borrar la oferta
$sql = "DELETE FROM ofertaslaborales WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirigir de vuelta a la página de búsqueda
header('Location: buscador.php');
?>