-- Create the database
CREATE DATABASE IF NOT EXISTS pfund_db;

-- Use the database
USE pfund_db;

-- Create the Users table
CREATE TABLE IF NOT EXISTS Users (
  UserID INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  password VARCHAR(255) NOT NULL
);

-- Create the Income table
CREATE TABLE IF NOT EXISTS Income (
  Id INT AUTO_INCREMENT PRIMARY KEY,
  UserID INT NOT NULL,
  source VARCHAR(100) NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  date DATE NOT NULL,
  details TEXT,
  FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create the Expenditure table
CREATE TABLE IF NOT EXISTS Expenditure (
  id INT AUTO_INCREMENT PRIMARY KEY,
  UserID INT NOT NULL,
  date DATE NOT NULL,
  particulars VARCHAR(100) NOT NULL,
  amount_spent DECIMAL(10, 2) NOT NULL,
  category VARCHAR(50) NOT NULL,
  FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create the Ex_Category table
CREATE TABLE IF NOT EXISTS Ex_Category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  description VARCHAR(100) NOT NULL,
  amount DECIMAL(10, 2) NOT NULL
);
