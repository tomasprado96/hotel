

<?php
include 'php/db.php';

// Recibir datos del formulario de búsqueda
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$num_habitaciones = $_POST['num_habitaciones'];

// Consulta a la base de datos para obtener las habitaciones disponibles
$query = $pdo->prepare("
    SELECT * FROM habitaciones 
    WHERE id NOT IN (
        SELECT id_habitacion 
        FROM reservas 
        WHERE (fecha_check_in <= :checkout AND fecha_check_out >= :checkin)
    )
");
$query->execute(['checkin' => $checkin, 'checkout' => $checkout]);

$habitaciones = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitaciones Disponibles</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <h1>Habitaciones Disponibles</h1>
    
    <?php if (empty($habitaciones)): ?>
        <div class="caja">
        <p >No hay habitaciones disponibles para las fechas seleccionadas.</p>
        <a class="coso" href="index.php">Volver a la página principal</a>
        </div>
    <?php else: ?>
        <div class="habitaciones">
            <?php foreach ($habitaciones as $habitacion): ?>
                <div class="habitacion">
                    <div class="foto">
                    <img class="fotos2" src="fotos/<?= $habitacion['imagen'] ?>" alt="<?= $habitacion['nombre'] ?>">
                    </div>
                    <h2><?= $habitacion['nombre'] ?></h2>
                    <p><?= $habitacion['descripcion'] ?></p>
                    <p>Precio por noche: <?= $habitacion['precio_por_noche'] ?> USD</p>
                    <form action="php/reserva.php" method="POST">
                        <input type="hidden" name="id_habitacion" value="<?= $habitacion['id'] ?>">
                        <input type="hidden" name="checkin" value="<?= $checkin ?>">
                        <input type="hidden" name="checkout" value="<?= $checkout ?>">
                        <input type="hidden" name="num_habitaciones" value="<?= $num_habitaciones ?>">
                        
                        <label for="nombre_cliente">Nombre:</label>
                        <input type="text" id="nombre_cliente" name="nombre_cliente" required>
                        
                        <label for="email_cliente">Email:</label>
                        <input type="email" id="email_cliente" name="email_cliente" required>
                        
                        <label for="telefono_cliente">Teléfono:</label>
                        <input type="text" id="telefono_cliente" name="telefono_cliente" required>
                        
                        <button type="submit">Reservar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>