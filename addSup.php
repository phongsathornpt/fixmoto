<?php 
    if(isset($_POST["name"])){
        $name = $_POST["name"];
        include('class.php');
        $oBj = new Main;
        $result = $oBj->addSupplier($name);
    }

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
    if(isset($result)){
        echo '<div class="alert alert-info mt-3">' . htmlspecialchars($result, ENT_QUOTES, 'UTF-8') . '</div>';
    }
    echo "
    <br>
    <h1>เพิ่ม supplier</h1>
    <br>
    <form action='addSup.php' method='post' class='row g-3'>
        <div class='col-md-6'>
            <label for='name' class='form-label'>ชื่อ supplier</label>
            <input type='text' class='form-control' id='name' name='name' placeholder='ชื่อ supplier' required>
        </div>
        <div class='col-12'>
            <button type='submit' class='btn btn-primary'>เพิ่ม</button>
        </div>
    </form>
    </div>
    </main>
    </body>
    </html>
";
?>