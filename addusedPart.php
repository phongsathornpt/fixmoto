<?php 
    include('class.php');
    $oBj = new Main;
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
    $fix_id = isset($_GET['fix_id']) ? intval($_GET['fix_id']) : 0;
    echo '<main role="main" class="container" style="margin-top: 60px;">
    <div class="starter-template">
    <h1>เพิ่มอะไหล่ที่ใช้</h1>
    <form method="get" class="row g-3">
        <div class="col-md-6">
            <label for="partnumber" class="form-label">รหัสอะไหล่</label>
            <input type="text" class="form-control" id="partnumber" name="partnumber" required>
        </div>
        <input type="hidden" name="fix_id" value="' . htmlspecialchars($fix_id, ENT_QUOTES, 'UTF-8') . '">
        <div class="col-12">
            <button type="submit" class="btn btn-success">เพิ่ม</button>
        </div>
    </form>
    ';
    if(isset($_GET['partnumber'])){
        $partnumber = $_GET['partnumber'];
        $fix_id = intval($_GET['fix_id']);
        $partResult = $oBj->checkPartuse($partnumber);
        if(count($partResult) == 0 ){
            echo "<div class='alert alert-warning mt-3'>อะไหล่ถูกใช้แล้ว หรือไม่พบอะไหล่</div>";
        }else if(count($partResult) >= 1 ){
            echo "<div class='alert alert-success mt-3'>" . htmlspecialchars($oBj->addFixuse($partnumber, $fix_id), ENT_QUOTES, 'UTF-8') . "</div>";
            echo "<div class='alert alert-info mt-2'>" . htmlspecialchars($oBj->setStatuspart($partnumber), ENT_QUOTES, 'UTF-8') . "</div>";
            echo "<div class='alert alert-info mt-2'>" . htmlspecialchars($oBj->updateStockfixuser($partnumber), ENT_QUOTES, 'UTF-8') . "</div>";
            echo "<div class='alert alert-info mt-2'>" . htmlspecialchars($oBj->changeStatusFix($fix_id, 2), ENT_QUOTES, 'UTF-8') . "</div>";
        }else {
            echo "<div class='alert alert-danger mt-3'>มีปัญหา</div>";
        }
    }
    echo "</div></main></body></html>";
?>