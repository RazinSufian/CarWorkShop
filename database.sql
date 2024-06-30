-- Create the database
CREATE DATABASE CarWorkshop;

-- Use the newly created database
USE CarWorkshop;

-- Create Clients table
CREATE TABLE Clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL UNIQUE,
    car_color VARCHAR(50),
    car_license_number VARCHAR(20) UNIQUE,
    car_engine_number VARCHAR(50) UNIQUE
);

-- Create Mechanics table
CREATE TABLE Mechanics (
    mechanic_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    daily_limit INT NOT NULL
);

-- Create Appointments table
CREATE TABLE Appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    mechanic_id INT,
    appointment_date DATE,
    CONSTRAINT fk_client FOREIGN KEY (client_id) REFERENCES Clients(client_id),
    CONSTRAINT fk_mechanic FOREIGN KEY (mechanic_id) REFERENCES Mechanics(mechanic_id),
    UNIQUE(client_id, appointment_date)
);
