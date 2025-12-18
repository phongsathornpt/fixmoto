<?php
    include('class.php');
    $oBj = new Main;   
    include('template/header.php');
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
include('template/menu.php');

echo '
<main role="main" class="container" style="margin-top: 60px;">

<div class="starter-template">



<div class="starter-template">

<h1>รายการซ่อม</h1>
<div class="table-responsive">
<table class="table table-striped table-hover">
    <thead class="table-dark">
    <tr>
        <th>หมายเลขงานซ่อม</th>
        <th>หมายเลขลูกค้า</th>
        <th>ยี่ห้อรถ</th>
        <th>ป้ายทะเบียน</th>
        <th>รายระเอียด</th>
        <th>สถานะ</th>
        <th>จัดการ</th>
    </tr>
    </thead>
    <tbody>
';
$datafix = $oBj->showFixlist();
if(!empty($datafix)){
$datafixCount = count($datafix);
for($i = 0; $i < $datafixCount ; $i++){
    $statusbyFixid = $oBj->getStatusbyfixid($datafix[$i]['fix_id']);
    echo "
    <tr>
        <td> " . htmlspecialchars($datafix[$i]['fix_id'], ENT_QUOTES, 'UTF-8') . " </td>
        <td> " . htmlspecialchars($datafix[$i]['customer_id'], ENT_QUOTES, 'UTF-8') . " </td>
        <td> " . htmlspecialchars($datafix[$i]['brand'], ENT_QUOTES, 'UTF-8') . " </td>
        <td> " . htmlspecialchars($datafix[$i]['plate'], ENT_QUOTES, 'UTF-8') . " </td>
        <td> " . htmlspecialchars($datafix[$i]['fix_detail'], ENT_QUOTES, 'UTF-8') . " </td>
        <td> " . htmlspecialchars($statusbyFixid[0]['fix_detail'] ?? '', ENT_QUOTES, 'UTF-8') . " </td>
        <td>  <a href='fixDetail.php?fix_id=" . htmlspecialchars($datafix[$i]['fix_id'], ENT_QUOTES, 'UTF-8') . "'>
    ";
    if($oBj->checkStatusFix($datafix[$i]['fix_id']) != 'ซ่อมเรียบร้อย'){
      echo "
      <button class='btn btn-sm btn-danger'> อัพเดทงานซ่อม </button> </a></td>
        
      </tr>
      ";
    } else {
      echo "</a></td></tr>";
    }
};
}
echo'
</tbody>
</table>
</div>
</div>
</div>
</main>
</body>
</html>

';


