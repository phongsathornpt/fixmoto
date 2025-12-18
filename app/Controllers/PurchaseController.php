<?php

require_once core('Controller.php');
require_once model('Purchase.php');
require_once model('Supplier.php');
require_once model('Inventory.php');
require_once dto('PurchaseDTO.php');
require_once dto('PurchaseDetailDTO.php');
require_once dto('PurchaseItemDTO.php');
require_once dto('SupplierDTO.php');

class PurchaseController extends Controller
{
    private $purchaseModel;
    private $supplierModel;
    private $inventoryModel;

    public function __construct()
    {
        $this->purchaseModel = new Purchase();
        $this->supplierModel = new Supplier();
        $this->inventoryModel = new Inventory();
    }

    public function index()
    {
        $this->requireAuth();
        $rows = $this->purchaseModel->all();
        $purchases = PurchaseDTO::collection($rows);
        $this->view('purchase/list', ['purchases' => $purchases]);
    }

    public function show($id)
    {
        $this->requireAuth();
        $purchase = $this->purchaseModel->find($id);

        if (!$purchase) {
            $this->redirect('/purchase');
            return;
        }

        $items = PurchaseItemDTO::collection($this->purchaseModel->getDetails($id));

        $dto = new PurchaseDetailDTO(
            id: (int) $id,
            supplierName: $this->purchaseModel->getSupplierName($id),
            buyDate: $this->purchaseModel->getBuyDate($id),
            status: $this->purchaseModel->getStatus($id),
            totalCost: $this->purchaseModel->getTotalCost($id),
            isPaid: $this->purchaseModel->isPaid($id),
            isReceived: $this->purchaseModel->isReceived($id),
            items: $items,
        );

        $this->view('purchase/detail', ['data' => $dto]);
    }

    public function create()
    {
        $this->requireAuth();
        $rows = $this->supplierModel->all();
        $suppliers = SupplierDTO::collection($rows);
        $this->view('purchase/add', ['suppliers' => $suppliers]);
    }

    public function store()
    {
        $this->requireAuth();

        $supplierId = $this->getPost('supplier_id');
        $buyDate = $this->getPost('purchase_date');
        $duePayDate = $this->getPost('due_date');

        try {
            $purchaseId = $this->purchaseModel->createPurchase($supplierId, $buyDate, $duePayDate);
            $this->redirect('/purchase/' . $purchaseId);
        } catch (Exception $e) {
            $rows = $this->supplierModel->all();
            $suppliers = SupplierDTO::collection($rows);
            $this->view('purchase/add', [
                'suppliers' => $suppliers,
                'error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function activate($id)
    {
        $this->requireAuth();

        try {
            $this->purchaseModel->activate($id);
            $this->redirect('/purchase/' . $id);
        } catch (Exception $e) {
            $this->redirect('/purchase/' . $id);
        }
    }

    public function markPaid($id)
    {
        $this->requireAuth();

        try {
            $this->purchaseModel->markPaid($id);
            $this->redirect('/purchase/' . $id);
        } catch (Exception $e) {
            $this->redirect('/purchase/' . $id);
        }
    }

    public function receive($id)
    {
        $this->requireAuth();

        try {
            $this->purchaseModel->markReceived($id);
            $this->redirect('/purchase/' . $id);
        } catch (Exception $e) {
            $this->redirect('/purchase/' . $id);
        }
    }

    public function getPartsBySupplier()
    {
        $this->requireAuth();
        $supplierId = $this->getPost('supplier_id');

        $inventory = $this->inventoryModel->findBySupplier($supplierId);
        echo json_encode($inventory);
    }
}
