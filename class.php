<?php
    class Database{
        protected $host = 'localhost';
        protected $db = 'fixmoto';
        protected $user = 'root';
        protected $pass = '';
        protected $pdo = null;
        
        public function db_con(){
            if ($this->pdo === null) {
                try {
                    $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8";
                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ];
                    $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
                } catch (PDOException $e) {
                    throw new Exception("Connection failed: " . $e->getMessage());
                }
            }
            return $this->pdo;
        }
    }
    
    class Main extends Database{
        public function getDatacus($monumber){
            $data = [];
            $sql = "SELECT customer_id, f_name, l_name, mobile_num FROM customer WHERE mobile_num = :monumber";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['monumber' => $monumber]);
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function addFixlist($customerID, $plate, $brand, $fix_detail){
            $d = strtotime("tomorrow");
            $datenow = date("Y-m-d h:i:sa", $d);
            $sql = "INSERT INTO `fix_list` (`fix_id`, `customer_id`, `date`, `brand`, `fix_detail`, `fix_status_id`, `plate`) VALUES (NULL, :customerID, :datenow, :brand, :fix_detail, '1', :plate)";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute([
                    'customerID' => $customerID,
                    'datenow' => $datenow,
                    'brand' => $brand,
                    'fix_detail' => $fix_detail,
                    'plate' => $plate
                ]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function addnewCus($monumber, $f_name, $l_name){
            $sql = "INSERT INTO customer (customer_id, f_name, l_name, mobile_num) VALUES (NULL, :f_name, :l_name, :monumber)";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute([
                    'f_name' => $f_name,
                    'l_name' => $l_name,
                    'monumber' => $monumber
                ]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function getLastcus(){
            $sql = "SELECT max(customer_id) as max_id FROM customer";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row['max_id'] ?? null;
        }
        
        public function showFixlist(){
            $data = [];
            $sql = "SELECT fix_id, customer_id, date, brand, fix_detail, fix_status_id, plate FROM fix_list";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function getNamebyfixid($fix_id){
            $data = [];
            $sql = "SELECT customer.f_name, customer.l_name FROM fix_list INNER JOIN customer on fix_list.customer_id = customer.customer_id WHERE fix_list.fix_id = :fix_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['fix_id' => $fix_id]);
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function getStatusbyfixid($fix_id){
            $data = [];
            $sql = "SELECT fix_status.fix_detail FROM `fix_list` INNER JOIN fix_status on fix_list.fix_status_id = fix_status.fix_status_id WHERE fix_list.fix_id = :fix_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['fix_id' => $fix_id]);
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function showFixlistbyid($fix_id){
            $data = [];
            $sql = "SELECT fix_id, customer_id, date, brand, fix_detail, fix_status_id, plate FROM fix_list WHERE fix_id = :fix_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['fix_id' => $fix_id]);
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function showProduct(){
            $data = [];
            $sql = "SELECT part_list.part_id, part_list.part_desc, part_list.part_cost, part_list.part_price, part_stock.part_total FROM part_list INNER JOIN part_stock ON part_list.part_id = part_stock.part_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function showBuyproduct($sup_id){
            $data = [];
            $sql = "SELECT part_id, part_desc FROM part_list WHERE supplier_id = :sup_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['sup_id' => $sup_id]);
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function showBuyid(){
            $sql = "SELECT max(buy_id) as max_id FROM buy";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row['max_id'] ?? null;
        }
        
        public function addProduct($supplier, $prod_desc, $prod_cost, $prod_price){
            try {
                $pdo = $this->db_con();
                $pdo->beginTransaction();
                
                $sql = "INSERT INTO `part_list` (`part_id`, `supplier_id`, `part_desc`, `part_cost`, `part_price`) VALUES (NULL, :supplier, :prod_desc, :prod_cost, :prod_price)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'supplier' => $supplier,
                    'prod_desc' => $prod_desc,
                    'prod_cost' => $prod_cost,
                    'prod_price' => $prod_price
                ]);
                
                $lastId = $pdo->lastInsertId();
                
                $sql2 = "INSERT INTO `part_stock` (`part_id`, `part_total`) VALUES (:part_id, '0')";
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute(['part_id' => $lastId]);
                
                $pdo->commit();
                return 'เพิ่มเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                $this->db_con()->rollBack();
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function showSupplier(){
            $data = [];
            $sql = "SELECT supplier_id, supplier_desc FROM supplier";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function addSupplier($name){
            $sql = "INSERT INTO supplier (supplier_desc) VALUES (:name)";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute(['name' => $name]);
                return 'เพิ่มเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function addBuy($sup_id, $dateofbill, $dateofpay){
            $sql = "INSERT INTO `buy` (`buy_id`, `supplier_id`, `buy_date`, `buy_status_id`, `recv_date`, `due_pay_date`, `pay_date`) VALUES (NULL, :sup_id, :dateofbill, '1', NULL, :dateofpay, NULL)";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute([
                    'sup_id' => $sup_id,
                    'dateofbill' => $dateofbill,
                    'dateofpay' => $dateofpay
                ]);
                return 'เพิ่มเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function addBuydesc($buy_id, $part_id, $order_amount){
            $sql = "INSERT INTO `buy_desc` (`buydesc_id`, `part_id`, `buy_id`, `order_amount`, `recv_amount`) VALUES (NULL, :part_id, :buy_id, :order_amount, NULL)";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute([
                    'part_id' => $part_id,
                    'buy_id' => $buy_id,
                    'order_amount' => $order_amount
                ]);
                return 'เพิ่มเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function listPo(){
            $data = [];
            $sql = "SELECT buy_id, supplier_desc, buy_date FROM buy INNER JOIN supplier on buy.supplier_id = supplier.supplier_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function listPoActivate(){
            $data = [];
            $sql = "SELECT buy_id, supplier_desc, buy_date FROM buy INNER JOIN supplier on buy.supplier_id = supplier.supplier_id WHERE buy_status_id = 2";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function costPo($buy_id){
            $data = [];
            $sql = "SELECT part_list.part_id, order_amount, part_list.part_cost FROM buy_desc INNER JOIN part_list on buy_desc.part_id = part_list.part_id WHERE buy_id = :buy_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['buy_id' => $buy_id]);
            while($row = $stmt->fetch()){
                $data[] = $row;
            }

            $costPo = 0;
            for($i = 0; $i < count($data); $i++){
                $costPo += ($data[$i]['order_amount'] * $data[$i]['part_cost']);
            }
            return $costPo;
        }
        
        public function getStatusPo($buy_id){
            $data = '';
            $sql = "SELECT buy_status_desc FROM `buy_status` INNER JOIN buy on buy.buy_status_id = buy_status.buy_status_id WHERE buy.buy_id = :buy_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['buy_id' => $buy_id]);
            $row = $stmt->fetch();
            return $row['buy_status_desc'] ?? '';
        }
        
        public function getNameSupplier($buyid){
            $sql = "SELECT supplier_desc FROM buy INNER JOIN supplier on buy.supplier_id = supplier.supplier_id WHERE buy_id = :buyid";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['buyid' => $buyid]);
            $row = $stmt->fetch();
            return $row['supplier_desc'] ?? '';
        }
        
        public function getDatebuy($buyid){
            $sql = "SELECT buy_date FROM buy WHERE buy_id = :buyid";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['buyid' => $buyid]);
            $row = $stmt->fetch();
            return $row['buy_date'] ?? '';
        }
        
        public function detailBill($buyid){
            $data = [];
            $sql = "SELECT buy_desc.part_id, part_list.part_desc, order_amount, (order_amount * part_list.part_cost) as total_cost FROM buy_desc INNER JOIN part_list on part_list.part_id = buy_desc.part_id WHERE buy_id = :buyid";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['buyid' => $buyid]);
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function activateBill($buyid){
            $sql = "UPDATE buy SET buy_status_id = '2' WHERE buy_id = :buyid";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute(['buyid' => $buyid]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function payBill($buyid){
            $d = strtotime("tomorrow");
            $datenow = date("Y-m-d", $d);
            $sql = "UPDATE buy SET pay_date = :datenow WHERE buy_id = :buyid";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute(['datenow' => $datenow, 'buyid' => $buyid]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function checkPay($buyid){
            $sql = "SELECT pay_date FROM buy WHERE buy_id = :buyid";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['buyid' => $buyid]);
            $row = $stmt->fetch();
            $data = $row['pay_date'] ?? null;
            return ($data === null) ? 0 : 1;
        }
        
        public function getPoStock($part_id){
            $sql = "SELECT part_total FROM part_stock WHERE part_id = :part_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['part_id' => $part_id]);
            $row = $stmt->fetch();
            return $row['part_total'] ?? 0;
        }
        
        public function getProduct($part_id, $amount){
            $amount = $this->getPoStock($part_id) + $amount;
            $sql = "UPDATE part_stock SET part_total = :amount WHERE part_id = :part_id";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute(['amount' => $amount, 'part_id' => $part_id]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function updateDateRecv($buy_id){
            $d = strtotime("tomorrow");
            $datenow = date("Y-m-d", $d);
            $sql = "UPDATE buy SET recv_date = :datenow WHERE buy_id = :buy_id";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute(['datenow' => $datenow, 'buy_id' => $buy_id]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function checkRecv($buyid){
            $sql = "SELECT recv_date FROM buy WHERE buy_id = :buyid";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['buyid' => $buyid]);
            $row = $stmt->fetch();
            $data = $row['recv_date'] ?? null;
            return ($data === null) ? 0 : 1;
        }
        
        public function stockProduct(){
            $data = [];
            $sql = "SELECT product_stock.prod_id, product.prod_desc, branch.branch_name, branch.branch_location, total FROM `product_stock` INNER JOIN product on product_stock.prod_id = product.prod_id INNER JOIN branch on product_stock.branch_id = branch.branch_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function usePart($fix_id){
            $data = [];
            $sql = "SELECT part_desc, fix_use.part_number FROM `fix_use` INNER JOIN part_number on fix_use.part_number = part_number.part_number INNER JOIN part_list on part_number.part_id = part_list.part_id WHERE fix_id = :fix_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['fix_id' => $fix_id]);
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function addFixuse($partnumber, $fix_id){
            $sql = "INSERT INTO `fix_use` (`fix_id`, `part_number`, `fixuse_id`) VALUES (:fix_id, :partnumber, NULL)";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute(['fix_id' => $fix_id, 'partnumber' => $partnumber]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function checkPartuse($partnumber){
            $data = [];
            $sql = "SELECT part_number FROM `part_number` WHERE part_number = :partnumber and part_status != 'used'";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['partnumber' => $partnumber]);
            while($row = $stmt->fetch()){
                $data[] = $row;
            }
            return $data;
        }
        
        public function setStatuspart($partnumber){
            $sql = "UPDATE `part_number` SET `part_status` = 'used' WHERE `part_number`.`part_number` = :partnumber";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute(['partnumber' => $partnumber]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function getPartID($partnumber){
            $sql = "SELECT part_id FROM part_number WHERE part_number = :partnumber";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['partnumber' => $partnumber]);
            $row = $stmt->fetch();
            return $row['part_id'] ?? null;
        }
        
        public function getPartTotal($partnumber){
            $part_id = $this->getPartID($partnumber);
            $sql = "SELECT part_total FROM part_stock WHERE part_id = :part_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['part_id' => $part_id]);
            $row = $stmt->fetch();
            return $row['part_total'] ?? 0;
        }
        
        public function updateStockfixuser($partnumber){
            $part_id = $this->getPartID($partnumber);
            $part_total = $this->getPartTotal($partnumber) - 1;
            $sql = "UPDATE `part_stock` SET `part_total` = :part_total WHERE `part_stock`.`part_id` = :part_id";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute(['part_total' => $part_total, 'part_id' => $part_id]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
        
        public function checkStatusFix($fix_id){
            $sql = "SELECT fix_status.fix_detail FROM `fix_status` INNER JOIN fix_list on fix_list.fix_status_id = fix_status.fix_status_id WHERE fix_list.fix_id = :fix_id";
            $stmt = $this->db_con()->prepare($sql);
            $stmt->execute(['fix_id' => $fix_id]);
            $row = $stmt->fetch();
            return $row['fix_detail'] ?? '';
        }
        
        public function changeStatusFix($fix_id, $fix_status){
            $sql = "UPDATE `fix_list` SET `fix_status_id` = :fix_status WHERE `fix_list`.`fix_id` = :fix_id";
            try {
                $stmt = $this->db_con()->prepare($sql);
                $stmt->execute(['fix_status' => $fix_status, 'fix_id' => $fix_id]);
                return 'อัพเดทเรียบร้อยแล้ว';
            } catch (PDOException $e) {
                return 'มีปัญหา: ' . $e->getMessage();
            }
        }
    }
?>