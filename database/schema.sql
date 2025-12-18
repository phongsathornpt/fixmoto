-- Users / Auth
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);

-- Customers (kept as is)
CREATE TABLE IF NOT EXISTS customer (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    f_name TEXT NOT NULL,
    l_name TEXT NOT NULL,
    mobile_num TEXT NOT NULL
);

-- Status Tables
CREATE TABLE IF NOT EXISTS repair_statuses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS buy_statuses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

-- Suppliers
CREATE TABLE IF NOT EXISTS suppliers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

-- Inventory (Part) System
CREATE TABLE IF NOT EXISTS inventory (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    supplier_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    cost REAL NOT NULL DEFAULT 0,
    price REAL NOT NULL DEFAULT 0,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

CREATE TABLE IF NOT EXISTS inventory_stock (
    inventory_id INTEGER PRIMARY KEY,
    quantity INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);

CREATE TABLE IF NOT EXISTS inventory_serials (
    serial_number TEXT PRIMARY KEY,
    inventory_id INTEGER NOT NULL,
    status TEXT DEFAULT 'available',
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);

-- Repairs (Fix) System
CREATE TABLE IF NOT EXISTS repairs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_id INTEGER NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    brand TEXT,
    detail TEXT,
    status_id INTEGER DEFAULT 1,
    plate TEXT,
    FOREIGN KEY (customer_id) REFERENCES customer(id),
    FOREIGN KEY (status_id) REFERENCES repair_statuses(id)
);

CREATE TABLE IF NOT EXISTS repair_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    repair_id INTEGER NOT NULL,
    serial_number TEXT NOT NULL,
    FOREIGN KEY (repair_id) REFERENCES repairs(id),
    FOREIGN KEY (serial_number) REFERENCES inventory_serials(serial_number)
);

-- Purchase Orders
CREATE TABLE IF NOT EXISTS purchases (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    supplier_id INTEGER NOT NULL,
    buy_date DATE NOT NULL,
    status_id INTEGER DEFAULT 1,
    due_pay_date DATE,
    pay_date DATE,
    recv_date DATE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (status_id) REFERENCES buy_statuses(id)
);

CREATE TABLE IF NOT EXISTS purchase_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    purchase_id INTEGER NOT NULL,
    inventory_id INTEGER NOT NULL,
    amount INTEGER NOT NULL DEFAULT 1,
    FOREIGN KEY (purchase_id) REFERENCES purchases(id),
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);
