<?php 
    include('class.php');
    $oBj = new Main;
    $buyid = 0;
    if(isset($_GET['buyid'])){
        $buyid = intval($_GET['buyid']);
    };
    $data = $oBj->detailBill($buyid);
    include('template/header.php');
    include('template/menu.php');
    echo '
    <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
    .starter-template {
      padding: 3rem 1.5rem;
    }
  </style>
    </head>
    <body>

';
    echo '<main role="main" class="container" style="margin-top: 60px;">
    <div class="starter-template">';
    include('template/menuPart.php');
    echo "
    <br>
    <h1>รายการใบสั่งซื้อ</h1>
    <hr>
";
if($buyid != 0){
    echo "
    <div class='card'>
    <div class='card-header bg-info text-white'>
        ใบสั่งซื้อเลขที่ " . htmlspecialchars($buyid, ENT_QUOTES, 'UTF-8') . " - "  . htmlspecialchars($oBj->getStatusPo($buyid), ENT_QUOTES, 'UTF-8') . "
    </div>
    <div class='card-body'>
        <div class='row mb-3'>
            <div class='col-md-4'>
                <strong>ผู้ขาย:</strong> " . htmlspecialchars($oBj->getNameSupplier($buyid), ENT_QUOTES, 'UTF-8') . "
            </div>
            <div class='col-md-4'>
                <strong>วันที่ออก:</strong> " . htmlspecialchars($oBj->getDatebuy($buyid), ENT_QUOTES, 'UTF-8') . "
            </div>
            <div class='col-md-4'>
                <strong>ยอดรวมสุทธิ:</strong> " . htmlspecialchars($oBj->costPo($buyid), ENT_QUOTES, 'UTF-8') . " บาท
            </div>
        </div>
        <div class='table-responsive'>
        <table class='table table-striped'>
            <thead class='table-dark'>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>จำนวน</th>
                <th class='text-end'>ราคา</th>
            </tr>
            </thead>
            <tbody>
    ";
    for($i = 0 ; count($data) > $i ; $i++){
    echo "
        <tr>
        <td>" . htmlspecialchars($data[$i]['part_id'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($data[$i]['part_desc'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($data[$i]['order_amount'], ENT_QUOTES, 'UTF-8') . "</td>
        <td class='text-end'>" . htmlspecialchars($data[$i]['total_cost'], ENT_QUOTES, 'UTF-8') . "</td>
        </tr>
    ";
    }
    echo "
            </tbody>
        </table>
        </div>
        <div class='text-end'>
            <h5>ยอดรวมสุทธิ: " . htmlspecialchars($oBj->costPo($buyid), ENT_QUOTES, 'UTF-8') . " บาท</h5>
    ";
    if($oBj->checkPay($buyid) == 0){
        echo "
            <form action='' method='POST' class='d-inline'>
                <input type='hidden' name='idtoupdate' value='" . htmlspecialchars($buyid, ENT_QUOTES, 'UTF-8') . "'>
                <button type='submit' class='btn btn-primary'>ชำระค่าบริการ</button>
            </form>            
        ";
    }else if($oBj->getStatusPo($buyid) == "รออนุมัติ"){
        echo "
        <span class='badge bg-success me-2'>ชำระค่าสินค้าแล้ว</span>
        <form action='' method='POST' class='d-inline'>
        <input type='hidden' name='activateBill' value='" . htmlspecialchars($buyid, ENT_QUOTES, 'UTF-8') . "'>
        <button type='submit' class='btn btn-success'>อนุมัติรายการ</button>
        </form> 
        
        ";
    }else {
        echo "
        <span class='badge bg-success'>ชำระค่าสินค้าแล้ว</span>
        <span class='badge bg-info'>อนุมัติรายการแล้ว</span>
        ";
    }
    if(isset($_POST['idtoupdate'])){
        echo '<div class="alert alert-success mt-3">' . htmlspecialchars($oBj->payBill($buyid), ENT_QUOTES, 'UTF-8') . '</div>';
    }
    if(isset($_POST['activateBill'])){
        echo '<div class="alert alert-success mt-3">' . htmlspecialchars($oBj->activateBill($buyid), ENT_QUOTES, 'UTF-8') . '</div>';
    }
    echo "
        </div>
    </div>
    </div>
";
}else{
    echo "<div class='alert alert-danger'>เกิดข้อผิดพลาด</div>";
}
echo "</div></main></body></html>";
?>