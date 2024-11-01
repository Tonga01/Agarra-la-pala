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
    $salario = $_POST['salario'];

    $sql = "UPDATE ofertaslaborales1 SET titulo = ?, empresa = ?, ubicacion = ?, descripcion = ?, salario = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssii', $titulo, $empresa, $ubicacion, $descripcion, $salario, $id);
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
    <style>
        main {
            padding: 20px;
            font-size: 1em;
        }

        form {
            display: flex;
            flex-direction: column;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; 
            margin: auto;
            font-size: 1em;
        }

        form label,
        form input,
        form textarea,
        form button {
            width: 100%;
            margin-bottom: 15px;
            font-size: 1em;
        }

        form input,
        form textarea {
            padding: 8px;
            font-size: 1em;
        }

        form textarea {
            height: 120px;
            font-size: 125%;
        }

        form button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1em;
        }

        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php"><img src="agarralapalalogo.png" alt="Logo de la página" class="logo"></a>
        <h1>EDICIÓN DE OFERTAS</h1>
        <nav>
            <a href="buscar_ofertas.php">Empleos</a>
            <a href="publicar_oferta.php">Publicar Oferta</a>
            <a href="buscador.php">Buscador</a>
            <a href="calculadora.php">Cotizar</a>
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
            
            <label for="salario">Salario:</label>
            <input type="number" id="salario" name="salario" value="<?= htmlspecialchars($oferta['salario']) ?>" required min="20000" max="20000000" step="1000">
            
            <button type="submit">Actualizar</button>
        </form>
    </main>
</body>
</html>