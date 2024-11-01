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
    
    // Validar y sanitizar el campo de salario
    $salario = (int)$_POST['salary'];
    if ($salario < 20000 || $salario > 20000000) {
        die("El salario debe estar entre 20000 y 20000000.");
    }

    // Insertar datos en la base de datos
    $sql = "INSERT INTO ofertaslaborales1 (titulo, empresa, ubicacion, descripcion, salario) 
            VALUES ('$titulo', '$empresa', '$ubicacion', '$descripcion', $salario)";

    if ($conn->query($sql) === TRUE) {
        echo "La oferta fue publicada exitosamente.";
    } else {
        echo "Error al publicar la oferta: " . $conn->error; 
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
    <style>
    
        main {
            padding: 20px;
        }

        .form-container {
            width: auto; 
            height: 100vh;
            padding:auto;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form#job-form {
            display: flex;
            flex-direction: column;
            border: none; 
        }

        form#job-form label,
        form#job-form input,
        form#job-form textarea,
        form#job-form button {
            width: 100%;
            margin-bottom: 15px;
        }

        form#job-form input,
        form#job-form textarea {
            padding: 8px;
            font-size: 1em;
        }

        form#job-form textarea {
            height: 120px;
        }

        form#job-form button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        form#job-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php"><img src="agarralapalalogo.png" alt="Logo de la página" class="logo"></a>
        <h1>PUBLICAR OFERTA LABORAL</h1>
        <nav>
            <a href="buscar_ofertas.php">Empleos</a>
            <a href="publicar_oferta.php">Publicar Oferta</a>
            <a href="buscador.php">Buscador</a>
            <a href="calculadora.php">Cotizar</a>
        </nav>
    </header>
    <main>
        <div class="form-container">
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
                
                <label for="salary">Salario:</label>
                <input type="number" id="salary" name="salary" min="20000" max="20000000" required>
                
                <button type="submit">Publicar</button>
            </form>
        </div>
    </main>
</body>
</html>
