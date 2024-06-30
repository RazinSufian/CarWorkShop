<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $appointment_date = $_POST['appointment_date'];
    $mechanic_id = $_POST['mechanic_id'];

    // Check if updating appointment date and mechanic
    $checkAvailabilityQuery = "SELECT m.name AS mechanic_name, m.daily_limit - COUNT(a.appointment_id) AS available_slots 
                               FROM Mechanics m 
                               LEFT JOIN Appointments a ON m.mechanic_id = a.mechanic_id AND a.appointment_date = ? 
                               WHERE m.mechanic_id = ?
                               GROUP BY m.mechanic_id";
    $stmt = $conn->prepare($checkAvailabilityQuery);
    $stmt->bind_param('si', $appointment_date, $mechanic_id);
    $stmt->execute();
    $stmt->bind_result($mechanic_name, $available_slots);
    $stmt->fetch();
    $stmt->close();

    if ($available_slots <= 0) {
        // No available slots for the selected mechanic on the chosen date
        header("Location: ../route/admin.php?error=No available slots for Mechanic '$mechanic_name' on $appointment_date.");
        exit();
    }

    // Proceed with updating the appointment
    $updateAppointmentQuery = "UPDATE Appointments SET appointment_date = ?, mechanic_id = ? WHERE appointment_id = ?";
    $stmt = $conn->prepare($updateAppointmentQuery);
    $stmt->bind_param('sii', $appointment_date, $mechanic_id, $appointment_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../route/admin.php?message=Appointment updated successfully.");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: ../route/admin.php?error=Error updating appointment: " . $stmt->error);
        exit();
    }
}
?>
