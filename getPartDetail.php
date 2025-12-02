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
    <h1>รับสินค้า</h1>
    <hr>
";
if($buyid != 0){
    echo "
    <form method='get'>
    <input type='hidden' value='" . htmlspecialchars($buyid, ENT_QUOTES, 'UTF-8') . "' name='buyid'>
    
    <div class='card'>
    <div class='card-header bg-info text-white'>
        ใบสั่งซื้อเลขที่ " . htmlspecialchars($buyid, ENT_QUOTES, 'UTF-8') . " - " . htmlspecialchars($oBj->getStatusPo($buyid), ENT_QUOTES, 'UTF-8') . "
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
                <th>จำนวนที่สัง</th>
                <th>จำนวนที่ได้</th>
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
        <td> 
        ";
        if($oBj->checkRecv($buyid) == 1){
            echo "<span class='badge bg-success'>รับสินค้าแล้ว</span>";
        }else{
            echo "<input type='number' class='form-control' name='amount" . $i . "' min='0' required>";
        };
        echo "
        </td>
        </tr>
    ";
    }
    echo "
            </tbody>
        </table>
        </div>
        <div class='text-end'>
            <h5>ยอดรวมสุทธิ: " . htmlspecialchars($oBj->costPo($buyid), ENT_QUOTES, 'UTF-8') . " บาท</h5>
            <input type='hidden' value='" . htmlspecialchars($buyid, ENT_QUOTES, 'UTF-8') . "' name='formpost'>
            ";
            if($oBj->checkRecv($buyid) == 1){
                echo "<span class='badge bg-success'>รับสินค้าเรียบร้อยแล้ว</span>";
            }else{
                echo "<button type='submit' class='btn btn-primary'>รับสินค้า</button>";
            }
            echo "
        </div>
    </div>
    </div>
    </form>
";
}else{
    echo "<div class='alert alert-danger'>เกิดข้อผิดพลาด</div>";
}
if(isset($_GET['formpost'])){
    for($is = 0 ; count($data) > $is ; $is++){
        $part_id = $data[$is]['part_id'];
        $order_amout = intval($_GET['amount'.$is]);
        echo '<div class="alert alert-info mt-2">' . htmlspecialchars($oBj->getProduct($part_id, $order_amout), ENT_QUOTES, 'UTF-8') . '</div>';
    }
    echo '<div class="alert alert-success mt-2">' . htmlspecialchars($oBj->updateDateRecv($buyid), ENT_QUOTES, 'UTF-8') . '</div>';
};
echo "</div></main></body></html>";
?>