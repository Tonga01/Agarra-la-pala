<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Ofertas por Título</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php"><img src="agarralapalalogo.png" alt="Logo de la página" class="logo"></a>
        <h1>BUSCAR OFERTAS LABORALES</h1>
        <nav>
            <a href="buscar_ofertas.php">Empleos</a>
            <a href="publicar_oferta.php">Publicar Oferta</a>
            <a href="buscador.php">Buscador</a>
            <a href="calculadora.html">Cotizar</a>
        </nav>
    </header>
    <main>
        <form method="POST" action="buscador.php">
            <h2 for="titulo_busqueda">Buscar por título:</h2>
            <input type="text" id="titulo_busqueda" name="titulo_busqueda" required>
            <button type="submit">Buscar</button>
        </form>

        <?php
        // Conexión a la base de datos
        $conexion = new mysqli("localhost", "root", "", "agarralapala");

        // Verificar conexión
        if ($conexion->connect_error) {
            die("Error en la conexión: " . $conexion->connect_error);
        }

        // Configurar el conjunto de caracteres a utf8mb4
        $conexion->set_charset("utf8mb4");

        // Procesar la búsqueda solo si se envía el formulario y el campo de búsqueda tiene contenido
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['titulo_busqueda'])) {
            $tituloBusqueda = "%" . $_POST['titulo_busqueda'] . "%";

            // Imprimir el valor de $tituloBusqueda para confirmar
            echo "<p>Buscando trabajos que contengan el título: " . htmlspecialchars($_POST['titulo_busqueda']) . "</p>";

            // Preparar la consulta de búsqueda
            $consulta = $conexion->prepare("SELECT id, titulo, empresa, ubicacion, descripcion FROM ofertaslaborales1 WHERE titulo LIKE ?");
            if (!$consulta) {
                die("Error en la preparación de la consulta: " . $conexion->error);
            }

            // Vincular parámetros
            $consulta->bind_param("s", $tituloBusqueda);

            // Ejecutar y obtener resultados
            $consulta->execute();
            $resultado = $consulta->get_result();

            // Mostrar resultados o mensaje de no resultados
            if ($resultado->num_rows > 0) {
                echo "<h2>Resultados:</h2>";
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<h3>" . htmlspecialchars($fila['titulo']) . "</h3>";
                    echo "<p>Empresa: " . htmlspecialchars($fila['empresa']) . "</p>";
                    echo "<p>Ubicación: " . htmlspecialchars($fila['ubicacion']) . "</p>";
                    echo "<p>Descripción: " . htmlspecialchars($fila['descripcion']) . "</p>";
                    echo "<a href='editar_oferta.php?id=" . $fila['id'] . "'>Editar</a> | ";
                    echo "<a href='borrar_oferta.php?id=" . $fila['id'] . "' onclick='return confirm(\"¿Estás seguro de que deseas borrar esta oferta?\")'>Borrar</a>";
                    echo "<hr>";
                }
            } else {
                echo "<p>No se encontraron resultados para tu búsqueda.</p>";
            }

            $consulta->close();
        }

        $conexion->close();
        ?>
    </main>
</body>
</html>
