<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'agarralapala');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID de la oferta a editar
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Obtener los datos de la oferta
$sql = "SELECT * FROM ofertaslaborales1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$oferta = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Actualizar la oferta en la base de datos
    $titulo = $_POST['titulo'];
    $empresa = $_POST['empresa'];
    $ubicacion = $_POST['ubicacion'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE ofertaslaborales1 SET titulo = ?, empresa = ?, ubicacion = ?, descripcion = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $titulo, $empresa, $ubicacion, $descripcion, $id);
    $stmt->execute();
    header('Location: buscador.php'); // Redirigir a la página de búsqueda después de actualizar
    exit(); // Asegurar que no se ejecute más código después de la redirección
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Oferta de Trabajo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php"><img src="agarralapalalogo.png" alt="Logo de la página" class="logo"></a>
        <h1>EDICIÓN DE OFERTAS</h1>
        <nav>
            <a href="buscar_ofertas.php">Empleos</a>
            <a href="publicar_oferta.php">Publicar Oferta</a>
            <a href="buscador.php">Buscador</a>
            <a href="calculadora.html">Cotizar</a>
        </nav>
    </header>
    <main>
        <h2>Editar Oferta</h2>
        <form method="POST" action="">
            <label for="titulo">Título del Trabajo:</label>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($oferta['titulo']) ?>" required>
            
            <label for="empresa">Empresa:</label>
            <input type="text" id="empresa" name="empresa" value="<?= htmlspecialchars($oferta['empresa']) ?>" required>
            
            <label for="ubicacion">Ubicación:</label>
            <input type="text" id="ubicacion" name="ubicacion" value="<?= htmlspecialchars($oferta['ubicacion']) ?>" required>
            
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($oferta['descripcion']) ?></textarea>
            
            <button type="submit">Actualizar</button>
        </form>
    </main>
</body>
</html>