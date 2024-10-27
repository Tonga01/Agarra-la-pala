<?php
// Datos de conexión a la base de datos
$host = "localhost";
$user = "root";       
$password = "";       
$dbname = "agarralapala";  

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y sanitizar los datos del formulario
    $titulo = $conn->real_escape_string($_POST['title']);
    $empresa = $conn->real_escape_string($_POST['company']);
    $ubicacion = $conn->real_escape_string($_POST['location']);
    $descripcion = $conn->real_escape_string($_POST['description']);

    // Insertar datos en la base de datos
    $sql = "INSERT INTO ofertaslaborales (titulo, empresa, ubicacion, descripcion) 
            VALUES ('$titulo', '$empresa', '$ubicacion', '$descripcion')";

    if ($conn->query($sql) === TRUE) {
        echo "La oferta fue publicada exitosamente.";
    } else {
        echo "Error al publicar la oferta: " . $conn->error; // Muestra el error SQL
    }
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Oferta de Trabajo</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="agarralapalalogo.ico" type="image/x-icon">
</head>
<body>
    <header>
        <a href="index.html"><img src="agarralapalalogo.png" alt="Logo de la página" class="logo"></a>
        <h1>PUBLICAR OFERTA LABORAL</h1>
        <nav>
        <a href="buscar_ofertas.php">Empleos</a>
            <a href="publicar_oferta.php">Publicar Oferta</a>
            <a href="buscador.php">Buscardor</a>
            <a href="calculadora.html">Cotizar</a>
        </nav>
    </header>
    <main>
        <h2>Formulario de Publicación</h2>
        <form id="job-form" method="POST" action="publicar_oferta.php">
            <label for="title">Título del Trabajo:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="company">Empresa:</label>
            <input type="text" id="company" name="company" required>
            
            <label for="location">Ubicación:</label>
            <input type="text" id="location" name="location" required>
            
            <label for="description">Descripción:</label>
            <textarea id="description" name="description" required></textarea>
            
            <button type="submit">Publicar</button>
        </form>
    </main>
</body>
</html>
