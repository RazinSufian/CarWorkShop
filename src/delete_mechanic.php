<?php
include 'db_config.php';

$mechanic_id = $_GET['id'];

// Fetch mechanic's name for error message
$fetchMechanicNameQuery = "SELECT name FROM Mechanics WHERE mechanic_id = ?";
$stmt = $conn->prepare($fetchMechanicNameQuery);
$stmt->bind_param('i', $mechanic_id);
$stmt->execute();
$stmt->bind_result($mechanic_name);
$stmt->fetch();
$stmt->close();

// Check if the mechanic has any pending appointments
$checkAppointmentsQuery = "SELECT COUNT(*) AS count FROM Appointments WHERE mechanic_id = ?";
$stmt = $conn->prepare($checkAppointmentsQuery);
$stmt->bind_param('i', $mechanic_id);
$stmt->execute();
$stmt->bind_result($appointment_count);
$stmt->fetch();
$stmt->close();

if ($appointment_count > 0) {
    // Mechanic has pending appointments, cannot be deleted
    header("Location: ../route/admin.php?error=Mechanic '$mechanic_name' has appointments pending, cannot be deleted.");
    exit();
}

// Delete mechanic if no pending appointments
$deleteMechanicQuery = "DELETE FROM Mechanics WHERE mechanic_id = ?";
$stmt = $conn->prepare($deleteMechanicQuery);
$stmt->bind_param('i', $mechanic_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: ../route/admin.php?message=Mechanic deleted successfully.");
    exit();
} else {
    $stmt->close();
    $conn->close();
    header("Location: ../route/admin.php?error=Error deleting mechanic '$mechanic_name': " . $stmt->error);
    exit();
}
?>
