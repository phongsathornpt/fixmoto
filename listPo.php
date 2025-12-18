<?php 
    include('class.php');
    $oBj = new Main;
    $data = $oBj->showSupplier();
    $sup_id = '';
    $listPo = $oBj->listPo();
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
    <div class='table-responsive'>
    <table class='table table-striped table-hover'>
        <thead class='table-dark'>
        <tr>
            <th>รหัส</th>
            <th>ชื่อลูกค้า</th>
            <th>วันที่ออก</th>
            <th>มูลค่า</th>
            <th>สถานะ</th>
        </tr>
        </thead>
        <tbody>
    ";
    for($i = 0 ; $i < count($listPo) ; $i++){
        $buy_ids = $listPo[$i]['buy_id'];
        echo "
        <tr>
            <td><a href='listPoDetail.php?buyid=" . htmlspecialchars($buy_ids, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($buy_ids, ENT_QUOTES, 'UTF-8') . "</a></td>
            <td>" . htmlspecialchars($listPo[$i]['supplier_desc'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($listPo[$i]['buy_date'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($oBj->costPo($buy_ids), ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($oBj->getStatusPo($buy_ids), ENT_QUOTES, 'UTF-8') . "</td>
        </tr>
        ";
    }
    echo "</tbody></table></div></div></main></body></html>";
?>