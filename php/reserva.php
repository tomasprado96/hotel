<?php
include 'db.php';

// Recibimos los datos enviados desde el formulario de selección de habitación
$id_habitacion = $_POST['id_habitacion'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$num_habitaciones = $_POST['num_habitaciones'];
$nombre_cliente = $_POST['nombre_cliente'];
$email_cliente = $_POST['email_cliente'];
$telefono_cliente = $_POST['telefono_cliente'];

// Verificar la disponibilidad de la habitación antes de proceder
$query = $pdo->prepare("
    SELECT * FROM reservas 
    WHERE id_habitacion = :id_habitacion 
    AND (fecha_check_in <= :checkout AND fecha_check_out >= :checkin)
");
$query->execute([
    'id_habitacion' => $id_habitacion,
    'checkin' => $checkin,
    'checkout' => $checkout
]);

$disponibilidad = $query->fetch();

if ($disponibilidad) {
    // Si hay conflicto de fechas, mostrar un mensaje de error
    echo "Lo sentimos, la habitación no está disponible para las fechas seleccionadas.";
    echo "<a href='../index.php'>Volver a la página principal</a>";
} else {
    // Si la habitación está disponible, proceder con la reserva
    $query = $pdo->prepare("
        INSERT INTO reservas (id_habitacion, nombre_cliente, email_cliente, telefono_cliente, fecha_check_in, fecha_check_out, num_habitaciones, fecha_reserva) 
        VALUES (:id_habitacion, :nombre_cliente, :email_cliente, :telefono_cliente, :checkin, :checkout, :num_habitaciones, NOW())
    ");
    
    $query->execute([
        'id_habitacion' => $id_habitacion,
        'nombre_cliente' => $nombre_cliente,
        'email_cliente' => $email_cliente,
        'telefono_cliente' => $telefono_cliente,
        'checkin' => $checkin,
        'checkout' => $checkout,
        'num_habitaciones' => $num_habitaciones
    ]);
    
    // Enviar correo de confirmación (esto es solo un ejemplo simple)
    $mensaje = "Estimado/a $nombre_cliente,\n\nSu reserva ha sido confirmada.\n\nDetalles de la reserva:\nHabitación: $id_habitacion\nCheck-in: $checkin\nCheck-out: $checkout\n\nGracias por elegirnos.";
    mail($email_cliente, "Confirmación de Reserva", $mensaje);
    
    // Redirigir a la página de confirmación
    header("Location: ../confirmar.php");
exit();

}
?>