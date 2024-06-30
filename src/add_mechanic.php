<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $daily_limit = $_POST['daily_limit'];

    // Check if mechanic with same name already exists
    $checkQuery = "SELECT COUNT(*) AS count FROM Mechanics WHERE name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Mechanic with the same name already exists
        header("Location: admin.php?error=Mechanic with name '$name' already exists. Please choose a different name.");
        exit();
    } else {
        // Insert new mechanic
        $insertQuery = "INSERT INTO Mechanics (name, daily_limit) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('si', $name, $daily_limit);
        
        if ($stmt->execute()) {
            // Mechanic added successfully
            header("Location: ../route/admin.php?message=Mechanic '$name' added successfully.");
            exit();
        } else {
            // Error adding mechanic
            header("Location: ../route/admin.php?error=Error adding mechanic: " . $stmt->error);
            exit();
        }

        $stmt->close();
    }
}

$conn->close();
?>
