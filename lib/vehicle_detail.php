<?php include('common.php');
?>
<!-- create VIEW for vehicle details -->
<?php
    $query1 = "DROP TABLE if exists vehicle_detail";
    $result1 = mysqli_query($db, $query1); 
    $query2 = "CREATE TABLE vehicle_detail AS SELECT carinfo.*, salesinfo.*, repairinfo.*
                FROM (SELECT v.VIN as VIN_1, 
                        CASE WHEN v.VIN IN (SELECT VIN FROM car) THEN 'Car'
                        WHEN v.VIN IN (SELECT VIN FROM truck) THEN 'Truck'
                        WHEN v.VIN IN (SELECT VIN FROM convertible) THEN 'Convertible'
                        WHEN v.VIN IN (SELECT VIN FROM SUV) THEN 'SUV'
                        WHEN v.VIN IN (SELECT VIN FROM van) THEN 'Van' END AS 'VehicleType',
                        v.ModelYear, m.ManufacturerName, v.ModelName, GROUP_CONCAT(c.ColorDescription SEPARATOR ', ') as Colors, 
                        ROUND(v.InvoicePrice*1.25, 0) as 'ListPrice', v.AdditionalInfo, v.InvoicePrice, v.AddDate, CONCAT(u.FirstName, ' ', u.LastName) as ClerkName
                        FROM Vehicle as v
                        INNER JOIN VehicleColor AS vc
                        ON vc.VIN = v.VIN
                        INNER JOIN Color AS c
                        ON vc.ColorID = c.ColorID
                        INNER JOIN Manufacturer AS m
                        ON v.ManufacturerID = m.ManufacturerID
                        INNER JOIN user as u
                        ON v.UserName = u.UserName
                        GROUP BY v.VIN) as carinfo
                LEFT JOIN (SELECT s.VIN as VIN_2, s.SoldPrice, s.PurchaseDate, c.*, CONCAT(i.FirstName, ' ', i.LastName) as PersonalName,
                            CONCAT(b.FirstName, ' ', b.LastName) as  PrimaryContactName, b.BusinessName, b.PrimaryContactTitle, 
                            CONCAT(u.FirstName, ' ', u.LastName) as SalesName
                            FROM salesrecord as s
                            INNER JOIN customer as c
                            ON s.CustomerID = c.CustomerID
                            INNER JOIN user as u
                            ON s.UserName = u.UserName
                            LEFT JOIN individual as i
                            ON s.CustomerID = i.CustomerID
                            LEFT JOIN business as b
                            ON s.CustomerID = b.CustomerID) as salesinfo
                ON carinfo.VIN_1 = salesinfo.VIN_2
                LEFT JOIN (SELECT r.VIN as VIN_3, r.StartDate, r.CompleteDate, r.RepairDescription, r.OdometerReading, ROUND(r.LaborCharge, 2) as LaborCharge, 
                            ROUND(p.PartsCost, 2) as PartsCost, ROUND(coalesce(r.LaborCharge, 0) + coalesce(p.PartsCost, 0), 2) as TotalCost, customer_info.*, CONCAT(u.FirstName, u.LastName) as ServiceWriterName
                            FROM repairrecord as r
                            LEFT JOIN (SELECT c.CustomerID as CustomerID_2, IF (i.CustomerID is not NULL, CONCAT(i.FirstName, ' ', i.LastName), b.BusinessName) as CustomerName
                                        FROM customer as c 
                                        LEFT JOIN individual as i ON c.CustomerID = i.CustomerID
                                        LEFT JOIN business as b ON c.CustomerID = b.CustomerID) as customer_info
                            ON r.CustomerID = customer_info.CustomerID_2
                            LEFT JOIN user as u
                            ON r.UserName = u.UserName
                            LEFT JOIN (SELECT RepairID, SUM(PartQuantity*PartPrice) AS PartsCost FROM Parts GROUP BY RepairID) as p
                            ON r.RepairID = p.RepairID) as repairinfo
                ON carinfo.VIN_1 = repairinfo.VIN_3
                ORDER BY AddDate, PurchaseDate, StartDate";
    $result2 = mysqli_query($db, $query2);

?>
<?php
$query = "ALTER TABLE vehicle_detail DROP VIN_2, VIN_3 FROM vehicle_detail";
$result = mysqli_query($db, $query);
?>     
