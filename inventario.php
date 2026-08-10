<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: rgb(12, 7, 41);
        }
        h1 {
            color: rgb(11, 140, 150);
        }
        form, table {
            background-color:rgb(255, 255, 255);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(24, 180, 185, 0.87);
        }
        label {
            display: block;
            margin-top: 10px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        input, select {
            width: 50%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        button {
            background-color: #6a5acd;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        button:hover {
            background-color: #5a4ab6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            border: 1px solid rgb(64, 156, 152);
            padding: 12px;
        }
        th {
            background-color: #e0e0ff;
        }
    </style>
</head>
<body>

    <h1>Inventario de Ropa</h1>

    <form>
        <div class="form-row">
            <div style="flex: 1;">
                <label>Nombre del Producto:</label>
                <input type="text" name="accesorios" class="form-control" required>
            </div>
            <div style="flex: 1;">
                <label>Precio ($):</label>
                <input type="text" name="precio" class="form-control" required>
            </div>
        </div>

        <label>Categoría:</label>
        <select name="categoria" class="form-select" required>
            <option>Ropa Deportiva</option>
            <option>Ropa de Invierno</option>
            <option>Ropa de verano</option>
        </select>

        <label>Cantidad:</label>
        <input type="number" name="cantidad" class="form-control" required>

        <label>Descuentos ($):</label>
        <select name="Descuentos" class="form-select" required>
            <option>descuento por Aniversario</option>
            <option>descuento de verano</option>
            <option>descuento de blackfriday</option>
            <option>descuento de fechas festivas</option>
        </select>

        <button disabled>Agregar Producto</button>
        <br>
        <button onclick="history.back()">Volver</button>
    </form>

    <table>
        <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Cantidad</th>
            <th>Precio</th>
        </tr>
        <tr>
            <td>Camiseta Deportiva</td>
            <td>Ropa Deportiva</td>
            <td>15</td>
            <td>$29.99</td>
        </tr>
        <tr>
            <td>Jeans de Moda</td>
            <td>Ropa de Moda</td>
            <td>8</td>
            <td>$49.99</td>
        </tr>
    </table>

</body>
</html>
