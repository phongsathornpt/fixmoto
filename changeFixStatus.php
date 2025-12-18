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
      text-align: center;
    }
  </style>
    </head>
    <body>

';
include('template/menu.php');
$fix_id = isset($_GET['fix_id']) ? intval($_GET['fix_id']) : 0;
$fix_status = isset($_GET['fix_status']) ? intval($_GET['fix_status']) : 0;
echo '
<main role="main" class="container" style="margin-top: 60px;">
<div class="starter-template">
';

if($fix_id > 0 && $fix_status > 0) {
    echo '<div class="alert alert-success">' . htmlspecialchars($oBj->changeStatusFix($fix_id, $fix_status), ENT_QUOTES, 'UTF-8') . '</div>';
} else {
    echo '<div class="alert alert-danger">ข้อมูลไม่ถูกต้อง</div>';
}

echo "
<a href='fixDetail.php?fix_id=" . htmlspecialchars($fix_id, ENT_QUOTES, 'UTF-8') . "' class='btn btn-primary'>คลิกที่นี่เพื่อกลับไปยังหน้าที่แล้ว</a>
</div>
</main>
</body>
</html>
";

