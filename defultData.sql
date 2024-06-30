-- Insert sample clients
INSERT INTO Clients (name, phone, car_color, car_license_number, car_engine_number)
VALUES 
('John Doe', '1234567890', 'Red', 'AB123CD', 'EN123456'),
('Jane Smith', '0987654321', 'Blue', 'XY987ZT', 'EN654321');

-- Insert sample mechanics
INSERT INTO Mechanics (name, daily_limit)
VALUES 
('Mike Johnson', 3),
('Sara Williams', 2);

-- Insert sample appointments
INSERT INTO Appointments (client_id, mechanic_id, appointment_date)
VALUES 
(1, 1, '2024-07-01'),
(2, 2, '2024-07-01');
