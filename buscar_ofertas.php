<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agarralapala";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Variables para la búsqueda y paginación
$tituloBusqueda = isset($_POST['titulo_busqueda']) ? "%" . $_POST['titulo_busqueda'] . "%" : "%";
$lugarBusqueda = isset($_POST['lugar_busqueda']) ? "%" . $_POST['lugar_busqueda'] . "%" : "%";
$salarioMin = isset($_POST['salario_min']) ? $_POST['salario_min'] : 20000;
$salarioMax = isset($_POST['salario_max']) ? $_POST['salario_max'] : 20000000;

// Configuración de paginación
$ofertasPorPagina = 10;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $ofertasPorPagina;

// Contar el total de ofertas para determinar el número de páginas
$sqlCount = "SELECT COUNT(*) AS total FROM ofertaslaborales1 WHERE titulo LIKE ? AND ubicacion LIKE ? AND salario BETWEEN ? AND ?";
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->bind_param("ssii", $tituloBusqueda, $lugarBusqueda, $salarioMin, $salarioMax);
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalOfertas = $resultCount->fetch_assoc()['total'];
$totalPaginas = ceil($totalOfertas / $ofertasPorPagina);

// Obtener las ofertas para la página actual
$sql = "SELECT * FROM ofertaslaborales1 WHERE titulo LIKE ? AND ubicacion LIKE ? AND salario BETWEEN ? AND ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiiii", $tituloBusqueda, $lugarBusqueda, $salarioMin, $salarioMax, $ofertasPorPagina, $offset);
$stmt->execute();
$result = $stmt->get_result();
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
            <a href="calculadora.php">Cotizar</a>
        </nav>
    </header>
    <main>
        <h2>Ofertas de Trabajo</h2>
        <div id="job-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='job-item'>";
                    echo "<h3>" . htmlspecialchars($row["titulo"]) . "</h3>";
                    echo "<p><strong>Empresa:</strong> " . htmlspecialchars($row["empresa"]) . "</p>";
                    echo "<p><strong>Ubicación:</strong> " . htmlspecialchars($row["ubicacion"]) . "</p>";
                    echo "<p><strong>Descripción:</strong> " . htmlspecialchars($row["descripcion"]) . "</p>";
                    echo "<p><strong>Salario:</strong> $" . number_format($row["salario"], 2, ',', '.') . "</p>";
                    echo "</div>";
                }
            } else {
                echo "No hay ofertas disponibles.";
            }
            ?>
        </div>

        <!-- Paginación -->
        <div class="pagination">
            <?php
            // Definir el rango de páginas para mostrar
            $rangoPaginas = 10;
            $inicioPagina = max(1, $paginaActual - floor($rangoPaginas / 2));
            $finPagina = min($totalPaginas, $inicioPagina + $rangoPaginas - 1);

            // Asegurarse de que el rango se ajuste al total de páginas
            if ($finPagina - $inicioPagina < $rangoPaginas - 1) {
                $inicioPagina = max(1, $finPagina - $rangoPaginas + 1);
            }

            // Botón "Página anterior"
            if ($paginaActual > 1) {
                echo "<a href='?pagina=" . ($paginaActual - 1) . "'>&laquo; Página Anterior  </a>";
            }

            // Números de página
            for ($i = $inicioPagina; $i <= $finPagina; $i++) {
                if ($i == $paginaActual) {
                    echo "<span class='current-page'>$i</span>";
                } else {
                    echo "<a href='?pagina=$i '>$i </a>";
                }
            }

            // Botón "Página siguiente"
            if ($paginaActual < $totalPaginas) {
                echo "<a href='?pagina=" . ($paginaActual + 1 ) . "'>  Página Siguiente &raquo;</a>";
            }
            ?>
        </div>
    </main>
</body>
</html>

<?php
// Cerrar conexión
$stmt->close();
$stmtCount->close();
$conn->close();
?>
