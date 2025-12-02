<?php 
    echo '
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
    <a class="navbar-brand" href="home.php">ระบบร้านซ่อมมอเตอร์ไซ</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="home.php">หน้าแรก</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="addFix.php">บันทึกรายการซ่อม</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="fixList.php">รายการซ่อม</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="partList.php">รายการอะไหร่</a>
        </li>
      </ul>
      <div class="d-flex">
      <a href="logout.php" class="btn btn-danger"> ออกจากระบบ </a>
      </div>
    </div>
    </div>
  </nav>
    ';
?>