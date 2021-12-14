USE cs6400_fa21_team071 ;

/*************** mimic database *****************/
-- mimic customer related tables
INSERT INTO Customer (City, State, PostalCode, Street, Email, PhoneNumber)
VALUES 
	('Ottawa', 'ON', 'K1V 0N9', '123 Wisteria Crescent', 'a@yahoo,com', '123-456-789'), 
	('Toronto', 'ON', 'L1V 0N9', '370 Groove Crescent', 'b@yahoo,com', '519-899-8888'),
    ('Victoria', 'BC', 'C1V 0N9', 'Unit 2 Apt', 'c@yahoo,com', '777-899-8888'),
    ('Waterloo', 'ON', 'D1V 0N9', '100 Abs Crescent', '', '123-987-6543');
    
INSERT INTO Individual (CustomerID, DriverLicenceNum, FirstName, LastName)
VALUES 
	(1, '123456789', 'Ying', 'Yao'),
    (3, '987654321', 'Haha', 'Lala');
INSERT INTO Business (CustomerID, TaxID, BusinessName, PrimaryContactTitle, FirstName, LastName)
VALUES 
	(2, 123, 'Abc Auto Shop', 'Manager', 'Wendy', 'Zhang'),
    (4, 456, 'Good Insurance', 'Owner', 'Sunny', 'Liu');
    
-- mimic user related tables
INSERT INTO User
VALUES 
	('User123', 'AA', 'BB', 'aabbcc'), 
    ('User234', 'CC', 'DD', 'aabbcc'), 
    ('User345', 'EE', 'FF', 'aabbcc'), 
    ('User456', 'GG', 'HH', 'bbccdd'),
    ('User567', 'II', 'JJ', 'aab');

INSERT INTO InventoryClerk
VALUES ('User123'), ('User567');
INSERT INTO SalesPerson
VALUES ('User234'), ('User567');
INSERT INTO ServiceWriter
VALUES ('User345'), ('User567');
INSERT INTO Manager
VALUES ('User456'), ('User567');
INSERT INTO Owner
VALUES ('User567');

-- mimic veihcle related tables
-- 2 tables below will be a fixed as required
INSERT INTO Manufacturer
VALUES (1, 'Acura'), (2, 'Alfa Romeo'), (3, 'Aston Martin'), (4, 'Audi'), (5, 'Bentley');
INSERT INTO Color
VALUES (1, 'Aluminum'), (2, 'Beige'), (3, 'Black'), (4, 'Blue');

INSERT INTO Vehicle (VIN, UserName, AdditionalInfo, InvoicePrice, ModelYear, ModelName,
AddDate, VehicleType, ManufacturerID)
VALUES 
	('ABC1234', 'User123', 'Car has two accidents', 1000.00, 2021, 'Model1', DATE('2021-09-30'), 'Car', 1),
    ('BCD2345', 'User567', '', 3000.34, 2020, 'Model2', DATE('2021-10-02'), 'Truck', 2), 
    ('CDE1234', 'User567', '', 1000.34, 2020, 'Model3', DATE('2021-10-03'), 'Convertible', 2), 
    ('DEF1234', 'User567', '', 2000.34, 2020, 'Model4', DATE('2021-10-03'), 'SUV', 3), 
    ('EFG1', 'User123', '', 2000.00, 2021, 'Model1', DATE('2021-10-04'), 'Car', 1),
    ('FG12', 'User123', '', 1500.00, 2000, 'Model5', DATE('2021-10-04'), 'Van', 4);
    
INSERT INTO Car (VIN, NumberofDoors)
VALUES ('ABC1234', 2), ('EFG1', 2);
INSERT INTO Truck (VIN, CargoCapacity, CargoCoverType, NumberOfRearAxis)
VALUES ('BCD2345', '20.0', 'type1', 3);
INSERT INTO Van (VIN, HasDriverSideBackDoor)
VALUES ('FG12', 'yes');
INSERT INTO SUV (VIN, DrivetrainType, NumberOfCupholders)
VALUES ('DEF1234', 'type11', 2);
INSERT INTO Convertible (VIN, RoofType, BackseatCount)
VALUES ('CDE1234', 'type1', 0);

INSERT INTO VehicleColor
VALUES ('ABC1234', 1), ('ABC1234', 2), ('BCD2345', 1), ('CDE1234', 3), ('DEF1234', 4), ('EFG1', 4), ('EFG1', 1), ('FG12', 2);

-- mimic sales related tables
INSERT INTO SalesRecord (PurchaseDate, SoldPrice, VIN, CustomerID, UserName)
VALUES 
	(DATE('2021-10-01'), 1200.00, 'ABC1234', 2, 'User234'),
	(DATE('2021-10-05'), 1300.00, 'DEF1234', 2, 'User567'),
	(DATE('2021-10-05'), 1000.00, 'EFG1', 1, 'User567'),
	(DATE('2021-10-05'), 2000.12, 'FG12', 3, 'User567');

-- mimic repair related tables
INSERT INTO RepairRecord (VIN, CustomerID, UserName, StartDate, CompleteDate, LaborCharge, RepairDescription, OdometerReading)
VALUES 
	('ABC1234', 2, 'User234', DATE('2021-10-02'), NULL, 200.12, 'Changed font breaker and screws', 80000),
	('EFG1', 1, 'User567', DATE('2021-10-07'), DATE('2021-10-08'), 100.00, 'Changed back breaker and screws', 9),
	('FG12', 3, 'User567', DATE('2021-10-10'), DATE('2021-10-13'), 1000.12, 'Changed wind shield', 70000);
    
INSERT INTO Parts (PartNumber, RepairID, PartPrice, PartQuantity, PartVendor)
VALUES
	(1, 1, 10.0, 3, 'Vendor1'),
    (2, 1, 20.0, 1, 'Vendor2'),
    (3, 2, 12.0, 1, 'Vendor2'),
    (1, 2, 20.0, 2, 'Vendor1'),
    (5, 2, 100.0, 1, 'Vendor3'),
    (6, 3, 520.0, 1, 'Vendor1');

/*************** test tasks and report *****************/
-- test vehcile constraint
-- INSERT INTO Vehicle (VIN, UserName, AdditionalInfo, InvoicePrice, ModelYear, ModelName,
-- AddDate, VehicleType, ManufacturerID)
-- VALUES ('CCCC3', 'User123', '', 1000.00, 20, 'Model1', DATE('2021-09-30'), 'Car', 1);

/*-- search/view customer
SELECT i.DriverLicenceNum, i.FirstName, i.LastName,
c.PhoneNumber, c.Street, c.City, c.State, c.PostalCode, c.Email 
FROM Individual as i INNER JOIN Customer as c
ON i.CustomerID = c.CustomerID
WHERE i.DriverLicenceNum = '$DriverLicenceNum'; # '123456789'

SELECT b.TaxID, b.FirstName, b.LastName, b.Businessname, b.PrimaryContactTitle,
c.PhoneNumber, c.Street, c.City, c.State, c.Postalcode, c.Email 
FROM Business as b INNER JOIN Customer as c
ON b.CustomerID = c.CustomerID
WHERE b.Taxid = '$TaxID'; # '123'

-- add/view customer has been tested
INSERT INTO customer (city, state, PostalCode, Street, Email, PhoneNumber)
VALUES ('$City', '$State', '$PostalCode', '$Street', '$Email', '$PhoneNumber');
    
INSERT INTO Individual (CustomerID, DriverLicenceNum, FirstName, LastName)
SELECT MAX(CustomerID), '$DriverLicenceNum', '$FirstName','$LastName'
FROM Customer; # '4','130987748','Hahah','Lala'

INSERT INTO Business (CustomerID, TaxID, BusinessName, PrimaryContactTitle, FirstName, LastName)
SELECT MAX(CustomerID), '$TaxID', '$BusinessName', '$PrimaryContactTitle', '$FirstName', '$LastName'
FROM Customer;
*/
