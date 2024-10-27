<?php
// Datos de conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor tiene otra configuración
$username = "root";        // Usuario de MySQL
$password = "";            // Contraseña de MySQL
$dbname = "agarrá la pala"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y sanitizar los datos del formulario
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $empresa = $conn->real_escape_string($_POST['empresa']);
    $ubicacion = $conn->real_escape_string($_POST['ubicacion']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    // Insertar datos en la base de datos
    $sql = "INSERT INTO ofertas laborales(titulo, empresa, ubicacion, descripcion) 
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