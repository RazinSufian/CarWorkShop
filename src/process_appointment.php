<?php
include 'db_config.php';

$name = $_POST['name'];
$phone = $_POST['phone'];
$car_color = $_POST['car_color'];
$car_license_number = $_POST['car_license_number'];
$car_engine_number = $_POST['car_engine_number'];
$appointment_date = $_POST['appointment_date'];
$mechanic_id = $_POST['mechanic'];

// Check if client exists or insert new client
$sql = "SELECT client_id FROM Clients WHERE phone = '$phone'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $sql = "INSERT INTO Clients (name, phone, car_color, car_license_number, car_engine_number) 
            VALUES ('$name', '$phone', '$car_color', '$car_license_number', '$car_engine_number')";
    $conn->query($sql);
    $client_id = $conn->insert_id;
} else {
    $row = $result->fetch_assoc();
    $client_id = $row['client_id'];
}

// Check if the client already has an appointment on the selected date
$sql = "SELECT COUNT(*) as count FROM Appointments 
        WHERE client_id = '$client_id' AND appointment_date = '$appointment_date'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['count'] > 0) {
    echo "You already have an appointment on this date.";
} else {
    // Proceed to book the appointment
    $sql = "INSERT INTO Appointments (client_id, mechanic_id, appointment_date) 
            VALUES ('$client_id', '$mechanic_id', '$appointment_date')";
    if ($conn->query($sql) === TRUE) {
        echo "Appointment booked successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
