<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>

    <!-- Display success or error messages if they exist -->
    <?php
    if (isset($_GET['message'])) {
        echo "<p style='color: green;'>{$_GET['message']}</p>";
    }
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>{$_GET['error']}</p>";
    }
    ?>
    
    <h2>Appointments</h2>
    <table border="1"> 
        <tr>
            <th>Client Name</th>
            <th>Phone</th>
            <th>Car License Number</th>
            <th>Appointment Date</th>
            <th>Mechanic</th>
            <th>Action</th>
        </tr>
        <?php
        include '../src/db_config.php'; // Corrected path to db_config.php

        // SQL query to fetch appointments ordered by appointment_date and client_name
        $sql = "SELECT a.appointment_id, c.name AS client_name, c.phone, c.car_license_number, a.appointment_date, m.name AS mechanic_name, a.mechanic_id 
                FROM Appointments a 
                JOIN Clients c ON a.client_id = c.client_id 
                JOIN Mechanics m ON a.mechanic_id = m.mechanic_id
                ORDER BY a.appointment_date, c.name ASC"; // ASC for ascending order

        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <form action='../src/edit_appointment.php' method='POST'> <!-- Adjusted path to edit_appointment.php -->
                        <td>{$row['client_name']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['car_license_number']}</td>
                        <td><input type='date' name='appointment_date' value='{$row['appointment_date']}' required></td>
                        <td>
                            <select name='mechanic_id' required>";
            $mechanics = $conn->query("SELECT * FROM Mechanics");
            while ($mechanic = $mechanics->fetch_assoc()) {
                $selected = ($mechanic['mechanic_id'] == $row['mechanic_id']) ? 'selected' : '';
                echo "<option value='{$mechanic['mechanic_id']}' $selected>{$mechanic['name']}</option>";
            }
            echo "          </select>
                        </td>
                        <td>
                            <input type='hidden' name='appointment_id' value='{$row['appointment_id']}'>
                            <button type='submit'>Update</button>
                            <a href='../src/delete_appointment.php?id={$row['appointment_id']}'>Delete</a> <!-- Adjusted path to delete_appointment.php -->
                        </td>
                    </form>
                </tr>";
        }
        ?>
    </table>
    
    <h2>Mechanics</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Daily Limit</th>
            <th>Action</th>
        </tr>
        <?php
        // Display existing mechanics
        $sql = "SELECT * FROM Mechanics";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <form action='../src/edit_mechanic.php' method='POST'> <!-- Adjusted path to edit_mechanic.php -->
                        <td><input type='text' name='name' value='{$row['name']}' required></td>
                        <td><input type='number' name='daily_limit' value='{$row['daily_limit']}' required></td>
                        <td>
                            <input type='hidden' name='mechanic_id' value='{$row['mechanic_id']}'>
                            <button type='submit'>Update</button>
                            <a href='../src/delete_mechanic.php?id={$row['mechanic_id']}'>Delete</a> <!-- Adjusted path to delete_mechanic.php -->
                        </td>
                    </form>
                </tr>";
        }
        ?>
        <!-- Form to add a new mechanic -->
        <tr>
            <form action="../src/add_mechanic.php" method="POST"> <!-- Adjusted path to add_mechanic.php -->
                <td><input type="text" name="name" placeholder="Mechanic Name" required></td>
                <td><input type="number" name="daily_limit" placeholder="Daily Limit" required></td>
                <td><button type="submit">Add</button></td>
            </form>
        </tr>
    </table>
</body>
</html>
