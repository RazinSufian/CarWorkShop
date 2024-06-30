<?php
include 'db_config.php';

$appointment_id = $_GET['id'];

$deleteAppointmentQuery = "DELETE FROM Appointments WHERE appointment_id = ?";
$stmt = $conn->prepare($deleteAppointmentQuery);
$stmt->bind_param('i', $appointment_id);

if ($stmt->execute()) {
    echo "Appointment deleted successfully.";
} else {
    echo "Error deleting appointment: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: ../route/admin.php"); // Redirect back to admin panel
exit();
?>
