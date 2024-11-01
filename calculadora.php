<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizador de publicaciones</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="agarralapalalogo.ico" type="image/x-icon">
    <style>
        .calculator {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            width: 300px;
            margin: -310 auto;
        }
        .calculator input {
            padding: 5px;
            width: 80%;
            margin-bottom: 10px;
            text-align: center;
        }
        .calculator .result {
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 10px;
        
    }
        .calculator .discount-info {
            color: #ff0000;
            font-size: 1em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <a href="index.php"><img src="agarralapalalogo.png" alt="Logo de la página" class="logo"></a>
            <h1>COTIZAR PUBLICACIONES</h1>
            <nav>
                <a href="buscar_ofertas.php">Empleos</a>
                <a href="publicar_oferta.php">Publicar Oferta</a>
                <a href="buscador.php">Buscador</a>
                <a href="calculadora.php">Cotizar</a>
            </nav>
        </header>

        <main>
            <h2>Cotizá las ofertas laborales de tu empresa de forma rápida y confiable</h2>
            <h3>Por cada 5 publicaciones, recibirás un 15% de descuento en el total de tu orden, hasta un máximo de 60%</h3>
            <h3>Valor actual de cada publicación es de: $500</h3>

            <div class="calculator">
                <h2>Cotización de ofertas</h2>
                <label for="cantidad">Cantidad de publicaciones:</label>
                <input type="number" id="cantidad" min="0" oninput="calcularCosto()">
                <div class="result">
                    <p>Total: $<span id="total">0</span></p>
                    <p class="discount-info" id="descuento"></p>
                </div>
            </div>
        </main>
    </div>

    <script>
        const precioPorUnidad = 500;

        function calcularCosto() {
            const cantidad = parseInt(document.getElementById('cantidad').value) || 0;
            let total = cantidad * precioPorUnidad;
            let descuento = 0;

            if (cantidad >= 20) {
                descuento = 0.60;
            } else if (cantidad >= 15) {
                descuento = 0.45;
            } else if (cantidad >= 10) {
                descuento = 0.30;
            } else if (cantidad >= 5) {
                descuento = 0.15;
            }

            const totalDescuento = descuento * 100;  // Porcentaje de descuento
            total -= total * descuento;
            document.getElementById('total').textContent = total.toFixed(2);
            document.getElementById('descuento').textContent = `Descuento aplicado: ${totalDescuento}%`;
        }
        
    </script>
</body>
</html>