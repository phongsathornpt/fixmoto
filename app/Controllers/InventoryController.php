<?php

require_once core('Controller.php');
require_once model('Inventory.php');
require_once model('Supplier.php');

class InventoryController extends Controller
{
    private $inventoryModel;
    private $supplierModel;

    public function __construct()
    {
        $this->inventoryModel = new Inventory();
        $this->supplierModel = new Supplier();
    }

    /** @return void */
    public function index(): void
    {
        $this->requireAuth();
        $inventory = $this->inventoryModel->allWithStock();
        $this->view("inventory/list", ["inventory" => $inventory]);
    }

    /** @param int $id @return void */
    public function show($id): void
    {
        $this->requireAuth();
        $item = $this->inventoryModel->find($id);

        if (!$item) {
            $this->redirect("/inventory");
            return;
        }

        $stock = $this->inventoryModel->getStock($id);
        $this->view("inventory/detail", ["item" => $item, "stock" => $stock]);
    }

    /** @return void */
    public function create(): void
    {
        $this->requireAuth();
        $suppliers = $this->supplierModel->all();
        $this->view("inventory/add", ["suppliers" => $suppliers]);
    }

    /** @return void */
    public function store(): void
    {
        $this->requireAuth();

        $supplierId = $this->getPost("supplier");
        $name = $this->getPost("name");
        $cost = $this->getPost("cost");
        $price = $this->getPost("price");

        try {
            $this->inventoryModel->createInventory(
                $supplierId,
                $name,
                $cost,
                $price,
            );
            $this->redirect("/inventory");
        } catch (Exception $e) {
            $suppliers = $this->supplierModel->all();
            $this->view("inventory/add", [
                "suppliers" => $suppliers,
                "error" => "เกิดข้อผิดพลาด: " . $e->getMessage(),
            ]);
        }
    }

    /** @return void */
    public function addSupplier(): void
    {
        $this->requireAuth();
        $this->view("inventory/add-supplier");
    }

    /** @return void */
    public function storeSupplier(): void
    {
        $this->requireAuth();

        $name = $this->getPost("name");

        try {
            $this->supplierModel->createSupplier($name);
            $this->redirect("/inventory/create");
        } catch (Exception $e) {
            $this->view("inventory/add-supplier", [
                "error" => "เกิดข้อผิดพลาด: " . $e->getMessage(),
            ]);
        }
    }
}
