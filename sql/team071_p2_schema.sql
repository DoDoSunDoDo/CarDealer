-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS 'root@localhost' IDENTIFIED BY '09250117';

DROP DATABASE IF EXISTS `cs6400_fa21_team071`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_fa21_team071 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_fa21_team071;

-- GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'root'@'localhost';
-- GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
-- GRANT ALL PRIVILEGES ON `cs6400_fa17_team001`.* TO 'gatechUser'@'localhost';
-- FLUSH PRIVILEGES;

-- Tables
CREATE TABLE User (
  UserName varchar(50) NOT NULL,
  FirstName varchar(50) NOT NULL,
  LastName varchar(50) NOT NULL,
  Password varchar(50) NOT NULL,
  PRIMARY KEY (UserName)
);

CREATE TABLE InventoryClerk (
  UserName varchar(50) NOT NULL,
  PRIMARY KEY (UserName)
);
CREATE TABLE SalesPerson (
  UserName varchar(50) NOT NULL,
  PRIMARY KEY (UserName)
);
CREATE TABLE ServiceWriter (
  UserName varchar(50) NOT NULL,
  PRIMARY KEY (UserName)
);
CREATE TABLE Manager (
  UserName varchar(50) NOT NULL,
  PRIMARY KEY (UserName)
);
CREATE TABLE `Owner` (
  UserName varchar(50) NOT NULL,
  PRIMARY KEY (UserName)
);

CREATE TABLE Customer (
  CustomerID int NOT NULL AUTO_INCREMENT,
  City varchar(50) NOT NULL,
  State varchar(50) NOT NULL,
  PostalCode varchar(50) NOT NULL,
  Street varchar(50) NOT NULL,
  Email varchar(50) DEFAULT NULL,
  PhoneNumber varchar(50) NOT NULL,
  PRIMARY KEY (CustomerID)
);

CREATE TABLE Individual (
  CustomerID int NOT NULL,
  DriverLicenceNum varchar(50) NOT NULL,
  FirstName varchar(50) NOT NULL,
  LastName varchar(50) NOT NULL,
  PRIMARY KEY (DriverLicenceNum), 
  KEY CustomerID (CustomerID)
);
CREATE TABLE Business (
  CustomerID int NOT NULL,
  TaxID int NOT NULL,
  FirstName varchar(50) NOT NULL,
  LastName varchar(50) NOT NULL,
  BusinessName varchar(50) NOT NULL,
  PrimaryContactTitle varchar(50) NOT NULL,
  PRIMARY KEY (TaxID), 
  KEY CustomerID (CustomerID)
);

CREATE TABLE Vehicle (
  VIN varchar(50) NOT NULL,
  UserName varchar(50) NOT NULL,
  AdditionalInfo  varchar(250) DEFAULT NULL,
  InvoicePrice float NOT NULL,
  ModelYear int NOT NULL,
  ModelName varchar(50) NOT NULL,
  AddDate date NOT NULL,
  ManufacturerID int NOT NULL,
  VehicleType varchar(50) NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE Car (
  VIN varchar(50) NOT NULL,
  NumberOfDoors int NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE Truck (
  VIN varchar(50) NOT NULL,
  CargoCapacity Int NOT NULL,
  CargoCoverType varchar(50) DEFAULT NULL,
  NumberOfRearAxis Int NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE Van (
  VIN varchar(50) NOT NULL,
  HasDriverSideBackDoor ENUM('Yes', 'No') NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE SUV (
  VIN varchar(50) NOT NULL,
  DrivetrainType varchar(50) NOT NULL,
  NumberOfCupholders Int NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE Convertible (
  VIN varchar(50) NOT NULL,
  RoofType varchar(50) NOT NULL,
  BackSeatCount int NOT NULL,
  PRIMARY KEY (VIN)
);

CREATE TABLE Color (
  ColorID int NOT NULL AUTO_INCREMENT,
  ColorDescription varchar(50) NOT NULL,
  PRIMARY KEY (ColorID)
);

CREATE TABLE Manufacturer (
  ManufacturerID int NOT NULL AUTO_INCREMENT,
  ManufacturerName varchar(50) NOT NULL,
  PRIMARY KEY (ManufacturerID)
);


CREATE TABLE VehicleColor (
  VIN varchar(50) NOT NULL,
  ColorID int NOT NULL,
  PRIMARY KEY (VIN, ColorID)
);

CREATE TABLE SalesRecord (
  SalesID int NOT NULL AUTO_INCREMENT,
  PurchaseDate date NOT NULL,
  SoldPrice float NOT NULL,
  VIN varchar(50) NOT NULL,
  CustomerID int NOT NULL,
  UserName varchar(50) NOT NULL,
  PRIMARY KEY (SalesID),
  KEY (VIN, CustomerID, UserName, PurchaseDate)
);

CREATE TABLE RepairRecord (
  RepairID int NOT NULL AUTO_INCREMENT,
  VIN varchar(50) NOT NULL,
  CustomerID int NOT NULL,
  UserName varchar(50) NOT NULL,
  StartDate date NOT NULL,
  CompleteDate date DEFAULT NULL,
  LaborCharge float NOT NULL,
  RepairDescription varchar(250) NOT NULL,
  OdometerReading int NOT NULL,
  PRIMARY KEY (RepairID),
  KEY (VIN, CustomerID, UserName, StartDate)
);

CREATE TABLE Parts (
  PartNumber int NOT NULL,
  RepairID int NOT NULL,
  PartPrice float NOT NULL,
  PartQuantity int NOT NULL,
  PartVendor varchar(50) NOT NULL,
  PRIMARY KEY (RepairID, PartNumber)
);

-- Constraints   Foreign Keys: FK_ChildTable_childColumn_ParentTable_parentColumn
-- ON DELETE CASCADE ON UPDATE CASCADE？？
ALTER TABLE InventoryClerk
  ADD CONSTRAINT fk_InventoryClerk_UserName_User_UserName FOREIGN KEY (UserName) REFERENCES `User` (UserName);
ALTER TABLE SalesPerson
  ADD CONSTRAINT fk_SalesPerson_UserName_User_UserName FOREIGN KEY (UserName) REFERENCES `User` (UserName);
ALTER TABLE ServiceWriter
  ADD CONSTRAINT fk_ServiceWriter_UserName_User_UserName FOREIGN KEY (UserName) REFERENCES `User` (UserName);
ALTER TABLE Manager
  ADD CONSTRAINT fk_Manager_UserName_User_UserName FOREIGN KEY (UserName) REFERENCES `User` (UserName);
ALTER TABLE `Owner`
  ADD CONSTRAINT fk_Owner_UserName_User_UserName FOREIGN KEY (UserName) REFERENCES `User` (UserName);

ALTER TABLE Business
  ADD CONSTRAINT fk_Business_CustomerID_Customer_CustomerID FOREIGN KEY (CustomerID) REFERENCES Customer (CustomerID);
ALTER TABLE Individual
  ADD CONSTRAINT fk_Individual_CustomerID_Customer_CustomerID FOREIGN KEY (CustomerID) REFERENCES Customer (CustomerID);

ALTER TABLE Vehicle
  ADD CONSTRAINT fk_Vehicle_ManufacturerID_Manufacturer_ManufacturerID FOREIGN KEY (ManufacturerID) REFERENCES Manufacturer (ManufacturerID),
  ADD CONSTRAINT fk_Vehicle_UserName_User_UserName FOREIGN KEY (UserName) REFERENCES `User` (UserName),
  ADD CHECK (CHAR_LENGTH(CAST(ModelYear AS CHAR)) = 4);

ALTER TABLE Car
  ADD CONSTRAINT fk_Car_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);
ALTER TABLE Truck
  ADD CONSTRAINT fk_Truck_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);
ALTER TABLE Van
  ADD CONSTRAINT fk_Van_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);
ALTER TABLE SUV
  ADD CONSTRAINT fk_SUV_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);
ALTER TABLE Convertible
  ADD CONSTRAINT fk_Convertible_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN);
  
ALTER TABLE VehicleColor
  ADD CONSTRAINT fk_VehicleColor_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN),
  ADD CONSTRAINT fk_VehicleColor_ColorID_Color_ColorID FOREIGN KEY (ColorID) REFERENCES Color (ColorID);
  
ALTER TABLE SalesRecord
  ADD CONSTRAINT fk_SalesRecord_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN),
  ADD CONSTRAINT fk_SalesRecord_CustomerID_Customer_CustomerID FOREIGN KEY (CustomerID) REFERENCES Customer (CustomerID),
  ADD CONSTRAINT fk_SalesRecord_UserName_User_UserName FOREIGN KEY (UserName) REFERENCES `User` (UserName);
  
ALTER TABLE RepairRecord
  ADD CONSTRAINT fk_RepairRecord_VIN_Vehicle_VIN FOREIGN KEY (VIN) REFERENCES Vehicle (VIN),
  ADD CONSTRAINT fk_RepairRecord_CustomerID_Customer_CustomerID FOREIGN KEY (CustomerID) REFERENCES Customer (CustomerID),
  ADD CONSTRAINT fk_RepairRecord_UserName_User_UserName FOREIGN KEY (UserName) REFERENCES `User` (UserName);
  
ALTER TABLE Parts
  ADD CONSTRAINT fk_Parts_RepairID_RepairRecord_RepairID FOREIGN KEY (RepairID) REFERENCES `RepairRecord` (RepairID);