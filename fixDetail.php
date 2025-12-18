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
$buyid = 0;
if(isset($_GET['fix_id'])){
    $fix_id = intval($_GET['fix_id']);
    echo '
<main role="main" class="container" style="margin-top: 60px;">

<div class="starter-template">



<div class="starter-template">

<h1>รายการซ่อม</h1>
<div class="table-responsive">
<table class="table table-striped table-hover" style="max-width: 800px; margin: 0 auto;">
    <thead class="table-dark">
    <tr>
        <th>หมายเลขงานซ่อม</th>
        <th>หมายเลขลูกค้า</th>
        <th>ยี่ห้อรถ</th>
        <th>ป้ายทะเบียน</th>
        <th>รายระเอียด</th>
        <th>สถานะ</th>
    </tr>
    </thead>
    <tbody>
';

$datafix = $oBj->showFixlistbyid($fix_id);
$fixDetail = $oBj->usePart($fix_id);
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
        
    </tr>
    </tbody>
    </table>
    </div>
    
    ";

    
};
}
}else{
    echo '<main role="main" class="container" style="margin-top: 60px;"><div class="starter-template"><div class="alert alert-danger">เกิดข้อผิดพลาดกรุณาลองไหม่</div></div>';
}
echo "
<br>
    <hr>
    <h1>อะไหล่ที่ใช้</h1>
    <br>
    <div class='table-responsive'>
    <table class='table table-striped table-hover' style='max-width: 800px; margin: 0 auto;'>
      <thead class='table-dark'>
      <tr>
        <th>
          หมายเลขอะไหล่
        </th>
        <th>
          อะไหล่
        </th>
      </tr>
      </thead>
      <tbody>

";


if(count($fixDetail) == 0){
  echo "<tr><td colspan='2' class='text-center'>ยังไม่ได้อัพเดทรายการใช้อะไหล่</td></tr>";
}else{
  $fixDetailCount = count($fixDetail);
  for($i = 0 ; $i < $fixDetailCount ; $i++){
    echo "
    <tr>
      <td>
        " . htmlspecialchars($fixDetail[$i]['part_number'], ENT_QUOTES, 'UTF-8') . "
      </td>
      <td>
        " . htmlspecialchars($fixDetail[$i]['part_desc'], ENT_QUOTES, 'UTF-8') . "
      </td>
    </tr>
    ";
  }
}
echo "    
</tbody>
</table>
</div>
<br>";
if($oBj->checkStatusFix($fix_id) != 'ซ่อมเรียบร้อย'){
  echo "<a href='addusedPart.php?fix_id=" . htmlspecialchars($fix_id, ENT_QUOTES, 'UTF-8') . "' class='btn btn-primary me-2'>เพิ่มอะไหล่ที่ใช้</a>";
}
if($oBj->checkStatusFix($fix_id) == 'กำลังซ่อม'){
  echo "
  <a href='changeFixStatus.php?fix_id=" . htmlspecialchars($fix_id, ENT_QUOTES, 'UTF-8') . "&fix_status=3' class='btn btn-success'>ซ่อมเรียบร้อยแล้วคลิกที่นี่</a>
  ";
}else if($oBj->checkStatusFix($fix_id) == 'รอชำระ'){
  echo "
  <a href='changeFixStatus.php?fix_id=" . htmlspecialchars($fix_id, ENT_QUOTES, 'UTF-8') . "&fix_status=4' class='btn btn-success'>ชำระเงินและรับรถเรียบร้อยคลิกที่นี่</a>
  ";
}
echo'
</div>
</main>
</body>
</html>

';


