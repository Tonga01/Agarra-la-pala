<?php
// Datos de conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor tiene otra configuración
$username = "root";        // Usuario de MySQL
$password = "";            // Contraseña de MySQL
$dbname = "agarralapala"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultar las ofertas de trabajo
$sql = "SELECT * FROM ofertaslaborales1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFERTAS RECIENTES</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="agarralapalalogo.ico" type="image/x-icon">
</head>
<body>
    <header>
        <a href="index.php"><img src="agarralapalalogo.png" alt="Logo de la página" class="logo"></a>
        <h1>OFERTAS RECIENTES</h1>
        <nav>
        <a href="buscar_ofertas.php">Empleos</a>
            <a href="publicar_oferta.php">Publicar Oferta</a>
            <a href="buscador.php">Buscador</a>
            <a href="calculadora.html">Cotizar</a>
        </nav>
    </header>
    <main>
        <h2>Ofertas de Trabajo</h2>
        <div id="job-list">
            <?php
            if ($result->num_rows > 0) {
                // Salida de cada fila
                while($row = $result->fetch_assoc()) {
                    echo "<div class='job-item'>";
                    echo "<h3>" . $row["titulo"] . "</h3>";
                    echo "<p><strong>Empresa:</strong> " . $row["empresa"] . "</p>";
                    echo "<p><strong>Ubicación:</strong> " . $row["ubicacion"] . "</p>";
                    echo "<p><strong>Descripción:</strong> " . $row["descripcion"] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "No hay ofertas disponibles.";
            }
            ?>
        </div>
    </main>
</body>
</html>

<?php
// Cerrar conexión
$conn->close();
?>
