<?php

require_once core('Controller.php');
require_once model('Repair.php');
require_once model('Customer.php');

class RepairsController extends Controller
{
    private $repairModel;
    private $customerModel;

    public function __construct()
    {
        $this->repairModel = new Repair();
        $this->customerModel = new Customer();
    }

    /** @return void */
    public function home(): void
    {
        $this->requireAuth();
        $this->view("home");
    }

    /** @return void */
    public function index(): void
    {
        $this->requireAuth();
        $repairs = $this->repairModel->all();

        // Get customer names and statuses for each repair
        foreach ($repairs as &$repair) {
            $customerData = $this->repairModel->getCustomerName($repair["id"]);
            $repair["customer_name"] = !empty($customerData)
                ? $customerData[0]["f_name"] . " " . $customerData[0]["l_name"]
                : "";
            $repair["status"] = $this->repairModel->getStatus($repair["id"]);
        }

        $this->view("repairs/list", ["repairs" => $repairs]);
    }

    /** @param int $id @return void */
    public function show($id): void
    {
        $this->requireAuth();
        $repair = $this->repairModel->find($id);

        if (!$repair) {
            $this->redirect("/repairs");
            return;
        }

        $customerData = $this->repairModel->getCustomerName($id);
        $usedParts = $this->repairModel->getUsedParts($id);
        $status = $this->repairModel->getStatus($id);

        $this->view("repairs/detail", [
            "repair" => $repair,
            "customer" => !empty($customerData) ? $customerData[0] : null,
            "usedParts" => $usedParts,
            "status" => $status,
        ]);
    }

    /** @return void */
    public function create(): void
    {
        $this->requireAuth();
        $this->view("repairs/add");
    }

    /** @return void */
    public function checkCustomer(): void
    {
        $this->requireAuth();
        $mobile = $this->getPost("mobile");

        if (!$mobile) {
            echo json_encode([
                "success" => false,
                "message" => "กรุณากรอกเบอร์โทรศัพท์",
            ]);
            return;
        }

        $customers = $this->customerModel->findByMobile($mobile);

        if (!empty($customers)) {
            echo json_encode(["success" => true, "customer" => $customers[0]]);
        } else {
            echo json_encode(["success" => false, "message" => "ไม่พบลูกค้า"]);
        }
    }

    /** @return void */
    public function storeNewCustomer(): void
    {
        $this->requireAuth();

        $mobile = $this->getPost("mobile");
        $fname = $this->getPost("fname");
        $lname = $this->getPost("lname");
        $plate = $this->getPost("plate");
        $brand = $this->getPost("brand");
        $detail = $this->getPost("detail");

        try {
            $customerId = $this->customerModel->createCustomer(
                $fname,
                $lname,
                $mobile,
            );
            $this->repairModel->createRepair(
                $customerId,
                $plate,
                $brand,
                $detail,
            );

            $this->redirect("/repairs");
        } catch (Exception $e) {
            $this->view("repairs/add", [
                "error" => "เกิดข้อผิดพลาด: " . $e->getMessage(),
            ]);
        }
    }

    /** @return void */
    public function storeExistingCustomer(): void
    {
        $this->requireAuth();

        $customerId = $this->getPost("customer_id");
        $plate = $this->getPost("plate");
        $brand = $this->getPost("brand");
        $detail = $this->getPost("detail");

        try {
            $this->repairModel->createRepair(
                $customerId,
                $plate,
                $brand,
                $detail,
            );
            $this->redirect("/repairs");
        } catch (Exception $e) {
            $this->view("repairs/add", [
                "error" => "เกิดข้อผิดพลาด: " . $e->getMessage(),
            ]);
        }
    }

    /** @param int $id @return void */
    public function updateStatus($id): void
    {
        $this->requireAuth();

        $statusId = $this->getPost("status_id");

        try {
            $this->repairModel->updateStatus($id, $statusId);
            $this->redirect("/repairs/" . $id);
        } catch (Exception $e) {
            $this->redirect("/repairs/" . $id);
        }
    }
}
