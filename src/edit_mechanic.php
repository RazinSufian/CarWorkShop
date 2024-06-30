<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mechanic_id = $_POST['mechanic_id'];
    $name = $_POST['name'];
    $daily_limit = $_POST['daily_limit'];

    // Check if the new mechanic name is already in use by another mechanic (excluding the current mechanic being updated)
    $checkQuery = "SELECT mechanic_id FROM Mechanics WHERE name = ? AND mechanic_id <> ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('si', $name, $mechanic_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: ../route/admin.php?error=Mechanic with name '$name' already exists. Please choose a different name.");
        exit();
    } else {
        // Check if the mechanic is free on the current appointment date
        $checkAppointmentQuery = "SELECT COUNT(*) AS count FROM Appointments WHERE mechanic_id = ? AND appointment_date = ?";
        $stmt = $conn->prepare($checkAppointmentQuery);
        $stmt->bind_param('is', $mechanic_id, $_POST['current_appointment_date']);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            // Mechanic is not available on the current appointment date
            header("Location: ../route/admin.php?error=Mechanic '$name' is not available on the current appointment date.");
            exit();
        }

        // Proceed with updating the mechanic details
        $updateMechanicQuery = "UPDATE Mechanics SET name = ?, daily_limit = ? WHERE mechanic_id = ?";
        $stmt = $conn->prepare($updateMechanicQuery);
        $stmt->bind_param('sii', $name, $daily_limit, $mechanic_id);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: ../route/admin.php?message=Mechanic updated successfully.");
            exit();
        } else {
            $stmt->close();
            $conn->close();
            header("Location: ../route/admin.php?error=Error updating mechanic: " . $stmt->error);
            exit();
        }
    }
}
?>
