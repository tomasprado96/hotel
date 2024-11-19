<?php
include 'php/db.php';

// Validar y sanitizar el parámetro email_cliente
$email_cliente = filter_input(INPUT_GET, 'email_cliente', FILTER_SANITIZE_EMAIL);

// Consulta a la base de datos para obtener las reservas del usuario
try {
    $query = $pdo->prepare("SELECT * FROM reservas WHERE email_cliente = :email_cliente ORDER BY fecha_reserva DESC");
    $query->execute(['email_cliente' => $email_cliente]);
    $reservas = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    $reservas = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Reservas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Historial de Reservas</h1>
    
    <?php if (empty($reservas)): ?>
        <div class="caja">
        <p>No tiene reservas realizadas.</p>
        <a href="index.php">Volver a la página principal</a>
        </div>
    <?php else: ?>
        <table>
            <tr>
                <th>Habitación</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Número de Habitaciones</th>
                <th>Fecha de Reserva</th>
            </tr>
            <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reserva['habitacion']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['check_in']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['check_out']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['numero_habitaciones']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['fecha_reserva']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
