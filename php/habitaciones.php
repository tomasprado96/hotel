<?php
include 'db.php';

$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$num_habitaciones = $_POST['num_habitaciones'];

$query = $pdo->prepare("SELECT * FROM habitaciones WHERE id NOT IN (
    SELECT id_habitacion FROM reservas WHERE (fecha_check_in <= :checkout AND fecha_check_out >= :checkin)
)");
$query->execute(['checkin' => $checkin, 'checkout' => $checkout]);

$habitaciones = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($habitaciones as $habitacion) {
    echo "<div class='habitacion'>";
    echo "<img src='images/{$habitacion['imagen']}' alt='{$habitacion['nombre']}'>";
    echo "<h2>{$habitacion['nombre']}</h2>";
    echo "<p>{$habitacion['descripcion']}</p>";
    echo "<p>Precio por noche: {$habitacion['precio_por_noche']} USD</p>";
    echo "<form action='reserva.php' method='POST'>";
    echo "<input type='hidden' name='id_habitacion' value='{$habitacion['id']}'>";
    echo "<input type='hidden' name='checkin' value='$checkin'>";
    echo "<input type='hidden' name='checkout' value='$checkout'>";
    echo "<input type='hidden' name='num_habitaciones' value='$num_habitaciones'>";
    echo "<button type='submit'>Reservar</button>";
    echo "</form>";
    echo "</div>";
}
?>