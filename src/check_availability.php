<?php
include 'db_config.php';

// Get appointment date from GET parameters
if (isset($_GET['appointment_date'])) {
    $appointment_date = $_GET['appointment_date'];

    // Prepare SQL query to check availability for the selected date
    $sql = "SELECT m.mechanic_id, m.name, m.daily_limit - COUNT(a.appointment_id) AS available_slots 
            FROM Mechanics m 
            LEFT JOIN Appointments a ON m.mechanic_id = a.mechanic_id AND a.appointment_date = '$appointment_date'
            GROUP BY m.mechanic_id";

    $result = $conn->query($sql);

    if ($result) {
        $options = "";
        while ($row = $result->fetch_assoc()) {
            if ($row['available_slots'] > 0) {
                $options .= "<option value='{$row['mechanic_id']}'>{$row['name']} ({$row['available_slots']} slots available)</option>";
            }
        }
        echo $options;
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
