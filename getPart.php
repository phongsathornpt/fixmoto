<?php 
    include('class.php');
    $oBj = new Main;
    $listPoActivate = $oBj->listPoActivate();
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
    <h1>รับสินค้าเข้าคลัง</h1>
    <hr>
    <p>รายการใบสั่งซื้อที่อนุมัติแล้ว</p>
    <div class='table-responsive'>
    <table class='table table-striped table-hover'>
        <thead class='table-dark'>                
    <tr>
        <th>รหัส</th>
        <th>ชื่อลูกค้า</th>
        <th>วันที่ออก</th>
        <th>มูลค่า</th>
        <th>จัดการ</th>
    </tr>
    </thead>
    <tbody>
";
    for($i = 0 ; $i < count($listPoActivate) ; $i++){
    $buy_ids = $listPoActivate[$i]['buy_id'];
    echo "
    <tr>
        <td><a href='listPoDetail.php?buyid=" . htmlspecialchars($buy_ids, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($buy_ids, ENT_QUOTES, 'UTF-8') . "</a></td>
        <td>" . htmlspecialchars($listPoActivate[$i]['supplier_desc'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($listPoActivate[$i]['buy_date'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($oBj->costPo($buy_ids), ENT_QUOTES, 'UTF-8') . "</td>
        <td>             
    ";
    if($oBj->checkRecv($buy_ids) == 1){
        echo "<span class='badge bg-success'>รับสินค้าแล้ว</span>";
    }else{
        echo "<a href='getPartDetail.php?buyid=" . htmlspecialchars($buy_ids, ENT_QUOTES, 'UTF-8') . "' class='btn btn-sm btn-primary'>รับสินค้า</a>";
    };
    echo "
        </td>
    </tr>
    ";
    };
    echo "
    </tbody>
    </table>
    </div>
    </div>
    </main>
    </body>
    </html>
    ";

?>