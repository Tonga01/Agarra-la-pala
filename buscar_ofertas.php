<?php
// Datos de conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor tiene otra configuración
$username = "root";        // Usuario de MySQL
$password = "";            // Contraseña de MySQL
$dbname = "ofertas laborales"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultar las ofertas de trabajo
$sql = "SELECT * FROM ofertas";
$result = $conn->query($sql);

// Inicializar un array para almacenar las ofertas
$ofertas = [];

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Almacenar cada oferta en el array
    while ($row = $result->fetch_assoc()) {
        $ofertas[] = $row;
    }
}

// Cerrar la conexión
$conn->close();
?>
