<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Ofertas por Título</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .salario-section {
            display: none;
        }

        main {
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 97%;
            margin: 0;
            height: auto;
        }

        label {
            margin-bottom: 5px; /* Margen entre el label y el input */
        }

        input[type="text"],
        input[type="range"],
        button {
            width: 100%;
            padding: 8px;
            font-size: 1em;
            margin-bottom: 15px; /* Margen entre inputs */
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Ajustar el campo de salario para mayor ancho */
        #salario-section {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php"><img src="agarralapalalogo.png" alt="Logo de la página" class="logo"></a>
        <h1>BUSCAR OFERTAS LABORALES</h1>
        <nav>
            <a href="buscar_ofertas.php">Empleos</a>
            <a href="publicar_oferta.php">Publicar Oferta</a>
            <a href="buscador.php">Buscador</a>
            <a href="calculadora.php">Cotizar</a>
        </nav>
    </header>
    <main>
        <form method="POST" action="buscador.php">
            <h2>Buscar por Título y Lugar:</h2>

            <label for="titulo_busqueda">Título:</label>
            <input type="text" id="titulo_busqueda" name="titulo_busqueda" placeholder="Ingrese Título" required>

            <label for="lugar_busqueda">Ubicación:</label>
            <input type="text" id="lugar_busqueda" name="lugar_busqueda" placeholder="Ingrese Lugar">

            <!-- Checkbox para activar el filtro de salario -->
            <label>
                <input type="checkbox" id="filtrar_salario" onclick="toggleSalario()"> Filtrar por salario
            </label>
<div id="salario-section" class="salario-section">
    <div style="margin-bottom: 20px;">
        <label for="salario_min">Salario mínimo:</label>
        <output id="salario_min_output" style="display: block; margin-bottom: 5px;">20000</output>
        <input type="range" id="salario_min" name="salario_min" min="20000" max="20000000" step="1000" value="20000" 
               oninput="ajustarSalarioMin()" style="width: 100%;">
    </div>

    <div>
        <label for="salario_max">Salario máximo:</label>
        <output id="salario_max_output" style="display: block; margin-bottom: 5px;">20000000</output>
        <input type="range" id="salario_max" name="salario_max" min="20000" max="20000000" step="1000" value="20000000" 
               oninput="ajustarSalarioMax()" style="width: 100%;">
    </div>
</div>

            <button type="submit">Buscar empleos</button>
        </form>

        <script>
            const salarioMinInput = document.getElementById('salario_min');
            const salarioMaxInput = document.getElementById('salario_max');
            const salarioMinOutput = document.getElementById('salario_min_output');
            const salarioMaxOutput = document.getElementById('salario_max_output');

            // Función para mostrar/ocultar la sección de salario
            function toggleSalario() {
                const salarioSection = document.getElementById("salario-section");
                const checkbox = document.getElementById("filtrar_salario");
                salarioSection.style.display = checkbox.checked ? "block" : "none";
            }

            // Función para sincronizar los valores de los rangos de salario y evitar que se crucen
            function ajustarSalarioMin() {
                if (parseInt(salarioMinInput.value) > parseInt(salarioMaxInput.value)) {
                    salarioMinInput.value = salarioMaxInput.value;
                }
                salarioMinOutput.value = salarioMinInput.value;
            }

            function ajustarSalarioMax() {
                if (parseInt(salarioMaxInput.value) < parseInt(salarioMinInput.value)) {
                    salarioMaxInput.value = salarioMinInput.value;
                }
                salarioMaxOutput.value = salarioMaxInput.value;
            }

            // Inicializar los valores en los outputs
            salarioMinOutput.value = salarioMinInput.value;
            salarioMaxOutput.value = salarioMaxInput.value;
        </script>

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
            $lugarBusqueda = isset($_POST['lugar_busqueda']) ? "%" . $_POST['lugar_busqueda'] . "%" : "%";
            
            // Consulta base
            $sql = "SELECT id, titulo, empresa, ubicacion, descripcion, salario FROM ofertaslaborales1 WHERE titulo LIKE ? AND ubicacion LIKE ?";
            
            // Verificar si se ha seleccionado el filtro de salario
            if (isset($_POST['filtrar_salario'])) {
                $salarioMin = $_POST['salario_min'];
                $salarioMax = $_POST['salario_max'];
                $sql .= " AND salario BETWEEN ? AND ?";
            }

            // Preparar la consulta de búsqueda
            $consulta = $conexion->prepare($sql);
            if (!$consulta) {
                die("Error en la preparación de la consulta: " . $conexion->error);
            }

            // Vincular parámetros de acuerdo a si se filtrará por salario
            if (isset($_POST['filtrar_salario'])) {
                $consulta->bind_param("ssii", $tituloBusqueda, $lugarBusqueda, $salarioMin, $salarioMax);
            } else {
                $consulta->bind_param("ss", $tituloBusqueda, $lugarBusqueda);
            }

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
                    echo "<p>Salario: $" . htmlspecialchars(number_format($fila['salario'], 0, ',', '.')) . "</p>";
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
