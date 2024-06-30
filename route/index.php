<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Workshop Appointment</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Book an Appointment</h1>
    <div id="appointmentContainer">
        <form id="appointmentForm" action="../src/process_appointment.php" method="post"> <!-- Adjusted path to process_appointment.php -->
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br>
            
            <label for="car_color">Car Color:</label>
            <input type="text" id="car_color" name="car_color"><br>
            
            <label for="car_license_number">Car License Number:</label>
            <input type="text" id="car_license_number" name="car_license_number" required><br>
            
            <label for="car_engine_number">Car Engine Number:</label>
            <input type="text" id="car_engine_number" name="car_engine_number" required><br>
            
            <label for="appointment_date">Appointment Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" required><br>
            
            <label for="mechanic">Select Mechanic:</label>
            <select id="mechanic" name="mechanic" required>
                <!-- Mechanics will be loaded here by JavaScript -->
            </select><br>
            
            <button type="submit">Book Appointment</button>
        </form>

        <div id="successMessage" style="display: none;">
            <!-- Success message or dynamically generated HTML -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Function to load mechanics availability when date is selected
            $('#appointment_date').on('change', function() {
                var selectedDate = $(this).val();
                if (selectedDate) {
                    $.ajax({
                        url: '../src/check_availability.php', // Adjusted path to check_availability.php
                        method: 'GET',
                        data: { appointment_date: selectedDate }, // Pass selected date to PHP
                        success: function(data) {
                            console.log("Response from check_availability.php:", data); // Log response to console
                            $('#mechanic').html(data);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error: ", error);
                            console.error("Status: ", status);
                            console.error("Response: ", xhr.responseText);
                        }
                    });
                }
            });

            $('#appointmentForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../src/process_appointment.php', // Adjusted path to process_appointment.php
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Hide form and show success message or generated HTML
                        $('#appointmentContainer').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: ", error);
                        console.error("Status: ", status);
                        console.error("Response: ", xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
