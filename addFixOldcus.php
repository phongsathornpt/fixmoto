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

<h1>ลูกค้าเดิม</h1>

';
if(isset($_GET['monumber'])){
    $monumber = $_GET['monumber'];
    $dataarr = $oBj->getDatacus($monumber);
    if(count($dataarr) == 0){
      echo '<div class="alert alert-warning">ไม่พบข้อมูล</div>';
    }else{
      if(isset($_GET['customerID'])){
        echo '<div class="alert alert-success">' . htmlspecialchars($oBj->addFixlist($_GET['customerID'], $_GET['plate'], $_GET['brand'], $_GET['fix_detail']), ENT_QUOTES, 'UTF-8') . '</div>';
    }
    echo "
        <div class='card mb-3'>
        <div class='card-body'>
        <p class='card-text'>คุณ " . htmlspecialchars($dataarr[0]['f_name'] . $dataarr[0]['l_name'], ENT_QUOTES, 'UTF-8') . "</p>
        <p class='card-text'>เบอร์โทรติดต่อ " . htmlspecialchars($dataarr[0]['mobile_num'], ENT_QUOTES, 'UTF-8') . "</p>
        </div>
        </div>
        <h2>กรุณากรอก</h2>
    ";

    echo '
    <form method="GET" class="row g-3">
    <div class="col-md-6">
    <label for="brand" class="form-label">ยี่ห้อรถ</label>
    <input type="text" name="brand" class="form-control" id="brand" placeholder="ยี่ห้อ" required>
    </div>
    <div class="col-md-6">
    <label for="plate" class="form-label">ป้ายทะเบียน</label>
    <input type="text" name="plate" class="form-control" id="plate" placeholder="ป้ายทะเบียน" required>
    </div>
    <div class="col-12">
        <label for="fix_detail" class="form-label">รายระเอียดการซ่อม</label>
        <textarea name="fix_detail" class="form-control" id="fix_detail" rows="3" maxlength="50" required></textarea>
    </div>
    <input type="hidden" name="monumber" value="'.$monumber.'">
    <input type="hidden" name="customerID" value="'.$dataarr[0]['customer_id'].'">
    <div class="col-12">
    <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
    </div>
    </form>
    ';
    }
}else {
    echo '
    <form method="GET" class="row g-3">
      <div class="col-12">
        <label for="monumber" class="form-label">เบอร์โทร</label>
        <input type="number" name="monumber" class="form-control" id="monumber" placeholder="xx-xxxx-xxxx" required>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-success">ดึงข้อมูล</button>
      </div>
    </form>    
</div>
</main>
</body>
</html>
        ';
};
