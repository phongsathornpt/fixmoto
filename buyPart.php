<?php 
    include('class.php');
    $oBj = new Main;
    $data = $oBj->showSupplier();
    $sup_id = '';
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
    <h1>สั่งซื้อ Part</h1>
        <br>
        <form method='post' class='row g-3'>
            <div class='col-md-6'>
                <label for='supplier' class='form-label'>เลือก supplier</label>
                <select name='supplier' id='supplier' class='form-select'>           
    ";
                    for($i = 0 ; $i < count($data) ; $i++){
                        echo "<option value='" . htmlspecialchars($data[$i]['supplier_id'], ENT_QUOTES, 'UTF-8') . "'> " . htmlspecialchars($data[$i]['supplier_desc'], ENT_QUOTES, 'UTF-8') . " </option>";
                    }
    echo " </select></div><div class='col-md-6 d-flex align-items-end'><button type='submit' class='btn btn-primary'>เลือก</button></div></form>";
    if(isset($_POST["supplier"])){
        $sup_id = $_POST["supplier"];
        $dataProductbyid = $oBj->showBuyproduct($sup_id);
        echo "
        <form method='get' class='row g-3 mt-3'>
            <div class='col-md-6'>
                <label for='dateofbill' class='form-label'>วันที่ออก</label>
                <input type='hidden' name='supplier' value='" . htmlspecialchars($sup_id, ENT_QUOTES, 'UTF-8') . "'>
                <input type='date' class='form-control' id='dateofbill' name='dateofbill' required>
            </div>
            <div class='col-md-6'>
                <label for='dateofpay' class='form-label'>วันที่จะชำระ</label>
                <input type='date' class='form-control' id='dateofpay' name='dateofpay' required>
            </div>
            <div class='col-12'>
                <h5>รายการสินค้า</h5>
            </div>
        ";
        for($i = 0 ; $i < count($dataProductbyid) ; $i++){
            echo "
            <div class='col-md-6'>
                <label class='form-label'>" . htmlspecialchars($dataProductbyid[$i]['part_desc'], ENT_QUOTES, 'UTF-8') . "</label>
                <input type='number' class='form-control' name='amount" . $i . "' min='0' placeholder='จำนวน' required>
            </div>
            ";
        }
        echo "
        <div class='col-12'>
            <button type='submit' class='btn btn-success'>สั่งซื้อ</button>
        </div>
        </form>
        ";
    }
    if(isset($_GET['dateofbill'])){
        $sup_id = $_GET["supplier"];
        $dataProductbyid = $oBj->showBuyproduct($sup_id);
        $dateofbill = $_GET['dateofbill'];
        $dateofpay = $_GET['dateofpay'];
        echo '<div class="alert alert-info mt-3">' . htmlspecialchars($oBj->addBuy($sup_id, $dateofbill, $dateofpay), ENT_QUOTES, 'UTF-8') . '</div>';
        $buyid = $oBj->showBuyid();
        for($ii = 0 ; $ii < count($dataProductbyid) ; $ii++){
            $prod_id = $dataProductbyid[$ii]['part_id'];
            $order_amout = $_GET['amount'.$ii];
            echo '<div class="alert alert-info">' . htmlspecialchars($oBj->addBuydesc($buyid, $prod_id, $order_amout), ENT_QUOTES, 'UTF-8') . '</div>';
        }
    }
    echo "</div></main></body></html>";
?>
