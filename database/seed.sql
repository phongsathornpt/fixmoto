-- =============================================
-- FIXMOTO Database Seed Data
-- Run: sqlite3 database/fixmoto.db < database/seed.sql
-- =============================================

-- Clear existing data (in reverse order of dependencies)
DELETE FROM repair_items;
DELETE FROM repairs;
DELETE FROM purchase_items;
DELETE FROM purchases;
DELETE FROM inventory_stock;
DELETE FROM inventory_serials;
DELETE FROM inventory;
DELETE FROM customer;
DELETE FROM suppliers;
DELETE FROM buy_statuses;
DELETE FROM repair_statuses;
DELETE FROM users;

-- =============================================
-- 1. USERS (Admin Account)
-- =============================================
INSERT INTO users (username, password) VALUES ('admin', 'admin');

-- =============================================
-- 2. STATUSES
-- =============================================
-- Repair Statuses
INSERT INTO repair_statuses (id, name) VALUES (1, 'รอคิวซ่อม');
INSERT INTO repair_statuses (id, name) VALUES (2, 'กำลังซ่อม');
INSERT INTO repair_statuses (id, name) VALUES (3, 'รออะไหล่');
INSERT INTO repair_statuses (id, name) VALUES (4, 'ซ่อมเสร็จแล้ว');
INSERT INTO repair_statuses (id, name) VALUES (5, 'ส่งงานแล้ว');
INSERT INTO repair_statuses (id, name) VALUES (6, 'ยกเลิก');

-- Buy Statuses
INSERT INTO buy_statuses (id, name) VALUES (1, 'รอดำเนินการ');
INSERT INTO buy_statuses (id, name) VALUES (2, 'สั่งซื้อแล้ว');
INSERT INTO buy_statuses (id, name) VALUES (3, 'ได้รับสินค้าแล้ว');
INSERT INTO buy_statuses (id, name) VALUES (4, 'ยกเลิก');

-- =============================================
-- 3. SUPPLIERS
-- =============================================
INSERT INTO suppliers (id, name) VALUES (1, 'Honda Official Parts');
INSERT INTO suppliers (id, name) VALUES (2, 'Yamaha Thailand');
INSERT INTO suppliers (id, name) VALUES (3, 'Local Parts Shop');
INSERT INTO suppliers (id, name) VALUES (4, 'Thai Motorcycle Parts Co.');
INSERT INTO suppliers (id, name) VALUES (5, 'Bangkok Auto Supply');

-- =============================================
-- 4. CUSTOMERS
-- =============================================
INSERT INTO customer (id, f_name, l_name, mobile_num) VALUES (1, 'สมชาย', 'ใจดี', '0812345678');
INSERT INTO customer (id, f_name, l_name, mobile_num) VALUES (2, 'สมหญิง', 'รักเรียน', '0823456789');
INSERT INTO customer (id, f_name, l_name, mobile_num) VALUES (3, 'วิชัย', 'มั่นคง', '0834567890');
INSERT INTO customer (id, f_name, l_name, mobile_num) VALUES (4, 'มานี', 'สุขใจ', '0845678901');
INSERT INTO customer (id, f_name, l_name, mobile_num) VALUES (5, 'ปิติ', 'ยินดี', '0856789012');

-- =============================================
-- 5. INVENTORY (Parts)
-- =============================================
-- Honda Parts (supplier_id = 1)
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (1, 1, 'Honda Oil Filter', 120, 180);
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (2, 1, 'Honda Brake Pad Set', 450, 650);
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (3, 1, 'Honda Spark Plug', 85, 150);
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (4, 1, 'Honda Chain Kit', 1200, 1800);

-- Yamaha Parts (supplier_id = 2)
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (5, 2, 'Yamaha Air Filter', 180, 280);
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (6, 2, 'Yamaha Clutch Plate', 380, 550);
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (7, 2, 'Yamaha Piston Ring', 220, 350);

-- Generic Parts (supplier_id = 3)
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (8, 3, 'หลอดไฟหน้า LED', 150, 250);
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (9, 3, 'น้ำมันเครื่อง 1L', 180, 280);
INSERT INTO inventory (id, supplier_id, name, cost, price) VALUES (10, 3, 'ยางนอก 70/90-17', 650, 950);

-- =============================================
-- 6. INVENTORY STOCK
-- =============================================
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (1, 25);
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (2, 15);
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (3, 50);
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (4, 8);
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (5, 20);
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (6, 12);
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (7, 18);
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (8, 30);
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (9, 40);
INSERT INTO inventory_stock (inventory_id, quantity) VALUES (10, 10);

-- =============================================
-- 7. PURCHASES (Purchase Orders)
-- =============================================
INSERT INTO purchases (id, supplier_id, buy_date, status_id, due_pay_date, pay_date, recv_date) 
VALUES (1, 1, '2024-12-01', 3, '2024-12-15', '2024-12-10', '2024-12-08');

INSERT INTO purchases (id, supplier_id, buy_date, status_id, due_pay_date, pay_date, recv_date) 
VALUES (2, 2, '2024-12-10', 2, '2024-12-25', NULL, NULL);

INSERT INTO purchases (id, supplier_id, buy_date, status_id, due_pay_date, pay_date, recv_date) 
VALUES (3, 3, '2024-12-15', 1, '2024-12-30', NULL, NULL);

-- =============================================
-- 8. PURCHASE ITEMS
-- =============================================
-- Purchase 1 items
INSERT INTO purchase_items (purchase_id, inventory_id, amount) VALUES (1, 1, 10);
INSERT INTO purchase_items (purchase_id, inventory_id, amount) VALUES (1, 2, 5);
INSERT INTO purchase_items (purchase_id, inventory_id, amount) VALUES (1, 3, 20);

-- Purchase 2 items
INSERT INTO purchase_items (purchase_id, inventory_id, amount) VALUES (2, 5, 15);
INSERT INTO purchase_items (purchase_id, inventory_id, amount) VALUES (2, 6, 8);

-- Purchase 3 items
INSERT INTO purchase_items (purchase_id, inventory_id, amount) VALUES (3, 8, 25);
INSERT INTO purchase_items (purchase_id, inventory_id, amount) VALUES (3, 9, 30);

-- =============================================
-- 9. REPAIRS
-- =============================================
INSERT INTO repairs (id, customer_id, date, brand, detail, status_id, plate) 
VALUES (1, 1, '2024-12-15 10:30:00', 'Honda Wave 110i', 'เปลี่ยนถ่ายน้ำมันเครื่อง + เช็คเบรก', 4, 'กข 1234');

INSERT INTO repairs (id, customer_id, date, brand, detail, status_id, plate) 
VALUES (2, 2, '2024-12-16 14:00:00', 'Yamaha Fino', 'เปลี่ยนยางนอก + ปรับเบรก', 2, 'ขค 5678');

INSERT INTO repairs (id, customer_id, date, brand, detail, status_id, plate) 
VALUES (3, 3, '2024-12-17 09:15:00', 'Honda Click', 'ซ่อมเครื่องยนต์ - เสียงดังผิดปกติ', 3, 'คง 9012');

INSERT INTO repairs (id, customer_id, date, brand, detail, status_id, plate) 
VALUES (4, 4, '2024-12-17 16:45:00', 'Yamaha NMAX', 'เปลี่ยนผ้าเบรกหน้า-หลัง', 1, 'งจ 3456');

INSERT INTO repairs (id, customer_id, date, brand, detail, status_id, plate) 
VALUES (5, 5, '2024-12-18 08:00:00', 'Honda PCX', 'ตรวจเช็คสภาพก่อนขาย', 5, 'จฉ 7890');

-- =============================================
-- Done! Data seeded successfully.
-- =============================================
