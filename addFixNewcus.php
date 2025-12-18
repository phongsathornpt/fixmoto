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

<h1>ลูกค้าไหม่</h1>

';
    if(isset($_GET['monumber'])){
      $result = $oBj->addnewCus($_GET['monumber'], $_GET['f_name'], $_GET['l_name']);
      if($result == "อัพเดทเรียบร้อยแล้ว"){
        $customerID = $oBj->getLastcus();
        echo '<div class="alert alert-success">' . htmlspecialchars($oBj->addFixlist($customerID, $_GET['plate'], $_GET['brand'], $_GET['fix_detail']), ENT_QUOTES, 'UTF-8') . '</div>';
      } else {
        echo '<div class="alert alert-danger">' . htmlspecialchars($result, ENT_QUOTES, 'UTF-8') . '</div>';
      }
    }
echo '
<form class="row g-3">
  <div class="col-12">
    <label for="monumber" class="form-label">เบอร์โทร</label>
    <input type="number" name="monumber" class="form-control" id="monumber" placeholder="xx-xxxx-xxxx" required>
  </div>
  <div class="col-md-6">
    <label for="f_name" class="form-label">ชื่อ</label>
    <input type="text" name="f_name" class="form-control" id="f_name" placeholder="ชื่อ" required>
  </div>
  <div class="col-md-6">
    <label for="l_name" class="form-label">นามสกุล</label>
    <input type="text" name="l_name" class="form-control" id="l_name" placeholder="นามสกุล" required>
  </div>
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
  <div class="col-12">
    <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
  </div>
</form>
    


</div>
</main>
</body>
</html>

';