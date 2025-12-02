<?php 
    include('class.php');
    $oBj = new Main;
    $data = $oBj->showProduct();
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
    <br>
    <div class='table-responsive'>
    <table class='table table-striped table-hover' style='max-width: 800px; margin: 0 auto;'>
    <thead class='table-dark'>
    <tr>
        <th>
            รหัส
        </th>
        <th>
            ชื่อสินค้า
        </th>
        <th>
            ราคาซื้อ
        </th>
        <th>
            ราคาขาย
        </th>
        <th>
            จำนวนคงเหลือ
        </th>
    </tr>
    </thead>
    <tbody>         
    " ;
    for($i = 0 ; $i < count($data) ; $i++){
        echo "
        <tr>
            <td>
                " . htmlspecialchars($data[$i]['part_id'], ENT_QUOTES, 'UTF-8') . "
            </td>
            <td>
                " . htmlspecialchars($data[$i]['part_desc'], ENT_QUOTES, 'UTF-8') . "
            </td>
            <td>
                " . htmlspecialchars($data[$i]['part_cost'], ENT_QUOTES, 'UTF-8') . "
            </td>
            <td>
                " . htmlspecialchars($data[$i]['part_price'], ENT_QUOTES, 'UTF-8') . "
            </td>
            <td>
                " . htmlspecialchars($data[$i]['part_total'], ENT_QUOTES, 'UTF-8') . "
            </td>
        </tr>         
        " ;
    }
    echo "</tbody></table></div></div></main></body></html>";
?>