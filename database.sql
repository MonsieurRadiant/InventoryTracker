DROP TABLE Ontime_Packages;
DROP TABLE Fragile_Packages;
DROP TABLE Contains_Packages;
DROP TABLE Contract;
DROP TABLE Carrier;
DROP TABLE TransportBy;
DROP TABLE Shipment;
DROP TABLE PostalCodeCity;
DROP TABLE Request;
DROP TABLE CustOrder;
DROP TABLE Supply;
DROP TABLE Supplier;
DROP TABLE HumanManager;
DROP TABLE DriverID;
DROP TABLE Robot;
DROP TABLE Manage;
DROP TABLE Manager;
DROP TABLE Store;
DROP TABLE Items;
DROP TABLE ItemBarcode;
DROP TABLE Fill;
DROP TABLE Inventory;
DROP TABLE Storage;



CREATE TABLE Storage
(wareHouseAddress VARCHAR(100),
position VARCHAR(100),
storageID VARCHAR (20),
PRIMARY KEY (storageID),
UNIQUE (wareHouseAddress, position));

CREATE TABLE Inventory
(inventoryNum VARCHAR (20),
inventorySize VARCHAR(100),
PRIMARY KEY (inventoryNum));

CREATE TABLE Fill
(storageID VARCHAR (20),
inventoryNum VARCHAR (20),
PRIMARY KEY (storageID, inventoryNum),
FOREIGN KEY (storageID) REFERENCES Storage,
FOREIGN KEY (inventoryNum) REFERENCES Inventory);

CREATE TABLE ItemBarcode
(barcode VARCHAR (20),
itemName VARCHAR(100),
price INT,
PRIMARY KEY (barcode));

CREATE TABLE Items
(itemID VARCHAR (20),
amount INT,
barcode VARCHAR (20),
description VARCHAR(100),
PRIMARY KEY (itemID),
FOREIGN KEY (barcode) REFERENCES ItemBarcode);

create table Store 
(itemID varchar (20),
inventoryNum varchar (20),
primary key (itemID, inventoryNum),
foreign key (itemID) references Items,
foreign key (inventoryNum) references Inventory);

CREATE TABLE Manager
(managerID VARCHAR (20),
PRIMARY KEY (managerID));

CREATE TABLE Manage
(inventoryNum VARCHAR (20),
managerID VARCHAR (20),
PRIMARY KEY (inventoryNum, managerID),
FOREIGN KEY (inventoryNum) REFERENCES Inventory,
FOREIGN KEY (managerID) REFERENCES Manager);

CREATE TABLE Robot
(managerID VARCHAR (20),
lastMaintenanceDate VARCHAR (20),
maintenancePeriod INT,
PRIMARY KEY (managerID),
FOREIGN KEY (managerID) REFERENCES Manager);

CREATE TABLE DriverID
(driverID VARCHAR (20),
name VARCHAR (100),
age INT,
PRIMARY KEY (driverID));

CREATE TABLE HumanManager
(managerID VARCHAR (20),
driverID VARCHAR (20),
phoneNumber VARCHAR (20),
PRIMARY KEY (managerID),
FOREIGN KEY (managerID) REFERENCES Manager,
FOREIGN KEY (driverId) REFERENCES DriverID);

CREATE TABLE Supplier
(supplierID VARCHAR (20),
supplierName VARCHAR(100),
address VARCHAR(100),
PRIMARY KEY (supplierID));

CREATE TABLE Supply
(itemID VARCHAR (20),
Sdate VARCHAR (20),
supplierID VARCHAR (20),
PRIMARY KEY (itemID),
FOREIGN KEY (itemID) REFERENCES Items,
FOREIGN KEY (supplierID) REFERENCES Supplier);

CREATE TABLE CustOrder
(orderNum VARCHAR (20),
orderDate VARCHAR (20),
totalPrice INT,
PRIMARY KEY (orderNum));

CREATE TABLE Request
(itemID VARCHAR (20),
orderNum VARCHAR (20),
PRIMARY KEY (itemID),
FOREIGN KEY (itemID) REFERENCES Items,
FOREIGN KEY (orderNum) REFERENCES CustOrder);

CREATE TABLE PostalCodeCity
(postalCode VARCHAR (20),
city VARCHAR(100),
PRIMARY KEY (postalCode));

CREATE TABLE Shipment
(shipmentNum VARCHAR (20),
shipDate VARCHAR (20),
customName VARCHAR(100),
arrivalDate VARCHAR (20),
address VARCHAR(100),
postalCode VARCHAR (20),
PRIMARY KEY (shipmentNum),
FOREIGN KEY (postalCode) REFERENCES PostalCodeCity);

CREATE TABLE TransportBy
(shipmentNum VARCHAR (20),
orderNum VARCHAR (20),
PRIMARY KEY (shipmentNum),
FOREIGN KEY (shipmentNum) REFERENCES Shipment,
FOREIGN KEY (orderNum) REFERENCES CustOrder);

CREATE TABLE Carrier
(carrierID VARCHAR (20),
carrierName VARCHAR(100),
price INT,
PRIMARY KEY (carrierID));

CREATE TABLE Contract
(carrierID VARCHAR (20),
shipmentNum VARCHAR (20),
PRIMARY KEY (shipmentNum),
FOREIGN KEY (shipmentNum) REFERENCES Shipment,
FOREIGN KEY (carrierID) REFERENCES Carrier);

CREATE TABLE Contains_Packages
(packageNum VARCHAR (20),
shipmentNum VARCHAR (20),
weight INT,
PRIMARY KEY (packageNum, shipmentNum),
FOREIGN KEY (shipmentNum) REFERENCES Shipment ON DELETE CASCADE);

CREATE TABLE Fragile_Packages
(packageNum VARCHAR (20),
shipmentNum VARCHAR (20),
shippingInstruction VARCHAR(100),
PRIMARY KEY (packageNum, shipmentNum),
FOREIGN KEY (packageNum, shipmentNum) REFERENCES Contains_Packages,
FOREIGN KEY (shipmentNum) REFERENCES Shipment ON DELETE CASCADE);

CREATE TABLE Ontime_Packages
(packageNum VARCHAR (20),
shipmentNum VARCHAR (20),
estimatedArrivalTime VARCHAR (20),
PRIMARY KEY (packageNum, shipmentNum),
FOREIGN KEY (packageNum, shipmentNum) REFERENCES Contains_Packages,
FOREIGN KEY (shipmentNum) REFERENCES Shipment ON DELETE CASCADE);


insert into Storage
values('1234 west 1st avenue', 'L2', '001-12345');
insert into Storage
values('2314 east 2st avenue', 'L1', '002-12346');
insert into Storage
values('2322 west 3st avenue', 'R2', '003-12347');
insert into Storage
values('1250 west 4st avenue', 'M1', '004-12348');
insert into Storage
values('1234 west 1st avenue', 'R3', '005-12349');

insert into Inventory
values('100', '500');
insert into Inventory
values('101', '1000');
insert into Inventory
values('102', '500');
insert into Inventory
values('103', '1000');
insert into Inventory
values('104', '2000');

insert into Fill
values('001-12345', '100');
insert into Fill
values('002-12346', '100');
insert into Fill
values('003-12347', '102');
insert into Fill
values('004-12348', '103');
insert into Fill
values('003-12347', '104');

insert into ItemBarcode
values('1234567890123', 'sunRype Orange Juice', '3.99');
insert into ItemBarcode
values('1234567890222', 'MinuteMaid Orange Juice ', '4.89');
insert into ItemBarcode
values('1234567890333', 'MinuteMaid Apple Juice', '4.89');
insert into ItemBarcode
values('1234567890444', 'MinuteMaid Grape Juice ', '4.89');
insert into ItemBarcode
values('1234567890555', 'sunRype Grape Juice', '2.79');
insert into ItemBarcode
values('000001', 'Vancouver Milk', '4.79');
insert into ItemBarcode
values('000002', 'Vancouver Apple', '1.79');

insert into Items
values('MK2020AP0908', '20', '1234567890123', 'none');
insert into Items
values('AP2019XCV089', '15', '1234567890222', 'none');
insert into Items
values('SX2022QEO011', '30', '1234567890333', 'none');
insert into Items
values('MK2021AP291B', '30', '1234567890444', 'none');
insert into Items
values('CS2022AP0910', '10', '1234567890555', 'none');

insert into Store
values('MK2020AP0908', '100');
insert into Store
values('AP2019XCV089', '100');
insert into Store
values('SX2022QEO011', '102');
insert into Store
values('MK2021AP291B', '104');
insert into Store
values('CS2022AP0910', '103');
insert into Store
values('MK2020AP0908', '102');
insert into Store
values('AP2019XCV089', '102');
insert into Store
values('MK2021AP291B', '102');
insert into Store
values('CS2022AP0910', '102');
insert into Store
values('SX2022QEO011', '100');
insert into Store
values('MK2021AP291B', '100');
insert into Store
values('CS2022AP0910', '100');

insert into Manager
values('H20011234');
insert into Manager
values('H20021233');
insert into Manager
values('H20051235');
insert into Manager
values('H20081236');
insert into Manager
values('H20091239');
insert into Manager
values('R1020111234');
insert into Manager
values('R1020221233');
insert into Manager
values('R1020051235');
insert into Manager
values('R1020071236');
insert into Manager
values('R1020001239');

insert into Manage
values('100', 'H20011234');
insert into Manage
values('101', 'H20021233');
insert into Manage
values('102', 'R1020111234');
insert into Manage
values('103', 'R1020221233');
insert into Manage
values('104', 'R1020051235');

insert into Robot
values('R1020111234', '2022-01-01', '90');
insert into Robot
values('R1020221233', '2022-01-01', '90');
insert into Robot
values('R1020051235', '2022-02-01', '180');
insert into Robot
values('R1020071236', '2022-02-01', '180');
insert into Robot
values('R1020001239', '2022-02-01', '180');

insert into DriverID
values('1234567', 'Andy', '33');
insert into DriverID
values('1234561', 'Barry', '32');
insert into DriverID
values('1234562', 'Carol', '32');
insert into DriverID
values('1234563', 'David', '31');
insert into DriverID
values('1234568', 'Eden', '30');

insert into HumanManager
values('H20011234', '1234567', '778-1230012');
insert into HumanManager
values('H20021233', '1234561', '123-2238809');
insert into HumanManager
values('H20051235', '1234562', '231-2221010');
insert into HumanManager
values('H20081236', '1234563', '123-4567890');
insert into HumanManager
values('H20091239', '1234568', '409-1238009');

insert into Supplier
values('9701234', '2322 west 2nd avenue', 'Amy');
insert into Supplier
values('9991234', '1022 west 16th avenue', 'Ninten');
insert into Supplier
values('8501234', '2312 east 5th avenue', 'Soy');
insert into Supplier
values('7701234', '1011 west 4th avenue', 'Amy');
insert into Supplier
values('0221234', '3012 west 9th avenue', 'Golg');

insert into Supply
values('MK2020AP0908', '2022-01-05', '9701234');
insert into Supply
values('AP2019XCV089', '2022-01-05', '9701234');
insert into Supply
values('SX2022QEO011', '2022-01-05', '9991234');
insert into Supply
values('MK2021AP291B', '2022-01-05', '8501234');
insert into Supply
values('CS2022AP0910', '2022-01-05', '7701234');

insert into CustOrder
values('20220202123', '2022-02-02', '55');
insert into CustOrder
values('20211222123', '2021-12-22', '60');
insert into CustOrder
values('20220202124', '2022-02-02', '200');
insert into CustOrder
values('20220203125', '2022-02-03', '500');
insert into CustOrder
values('20220204223', '2022-02-04', '335');

insert into Request
values('MK2020AP0908', '20220202123');
insert into Request
values('AP2019XCV089', '20220202123');
insert into Request
values('SX2022QEO011', '20220202124');
insert into Request
values('MK2021AP291B', '20220203125');
insert into Request
values('CS2022AP0910', '20220204223');

insert into PostalCodeCity
values('V6K 1A1', 'Vancouver');
insert into PostalCodeCity
values('V6K 1Z1', 'Vancouver');
insert into PostalCodeCity
values('V6E 1A1', 'Vancouver');
insert into PostalCodeCity
values('V5G 1A1', 'Burnaby');
insert into PostalCodeCity
values('V5G 1Z4', 'Burnaby');

insert into Shipment
values('51022898', '2022-02-03', 'Queen', '2022-02-04', '2000 west 4th avenue, Vancouver', 'V6K 1A1');
insert into Shipment
values('33076988', '2021-12-23', 'Wendy', '2021-12-24', '1298 east 10th avenue, Vancouver', 'V6K 1Z1');
insert into Shipment
values('11895672', '2022-02-03', 'Ed', '2022-02-04', '3012 west 4th avenue, Burnaby', 'V5G 1Z4');
insert into Shipment
values('39248491', '2022-02-04', 'Ron', '2022-02-05', '2011 east 4th avenue, Burnaby', 'V5G 1A1');
insert into Shipment
values('74429418', '2022-02-05', 'Tony', '2022-02-06', '2111 west 5th avenue, Vancouver', 'V6E 1A1');

insert into TransportBy
values('51022898', '20220202123');
insert into TransportBy
values('33076988', '20211222123');
insert into TransportBy
values('11895672', '20220202124');
insert into TransportBy
values('39248491', '20220203125');
insert into TransportBy
values('74429418', '20220204223');

insert into Carrier
values('BC1022', 'CanadaPotato', '5');
insert into Carrier
values('BC2012', 'Express', '6');
insert into Carrier
values('BC1234', 'Downs', '5');
insert into Carrier
values('BC2345', 'Pulato', '4');
insert into Carrier
values('BC5678', 'FeedEx', '4');

insert into Contract
values('BC1022', '51022898');
insert into Contract
values('BC1022', '33076988');
insert into Contract
values('BC1234', '11895672');
insert into Contract
values('BC2345', '39248491');
insert into Contract
values('BC5678', '74429418');

insert into Contains_Packages
values('2100123', '51022898', '500');
insert into Contains_Packages
values('2200123', '33076988', '2000');
insert into Contains_Packages
values('5859659', '33076988', '2000');
insert into Contains_Packages
values('2980145', '11895672', '2500');
insert into Contains_Packages
values('8459912', '39248491', '900');
insert into Contains_Packages
values('2135732', '74429418', '3000');
insert into Contains_Packages
values('6257931', '11895672', '900');
insert into Contains_Packages
values('4325566', '33076988', '1200');
insert into Contains_Packages
values('7592719', '33076988', '2200');
