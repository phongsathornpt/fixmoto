<?php 
    include('class.php');
    include('template/header.php');
    include('template/menu.php');
    $oBj = new Main;
    $data = $oBj->showSupplier();
    if(isset($_GET["supplier"])){
        $supplier = $_GET["supplier"];
        $name = $_GET["name"];
        $cost = $_GET["cost"];
        $price = $_GET["price"];
        echo '<div class="container" style="margin-top: 60px;"><div class="alert alert-info">' . htmlspecialchars($oBj->addProduct($supplier, $name, $cost, $price), ENT_QUOTES, 'UTF-8') . '</div></div>';
    };
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
    echo '
    <main role="main" class="container" style="margin-top: 60px;">
    <div class="starter-template">';
    include('template/menuPart.php');
    echo "
        <h1 class='mt-4'>เพิ่ม Product</h1>
        <br>
        <form method='get' class='row g-3'>
        <div class='col-md-6'>
            <label for='supplier' class='form-label'>เลือก supplier</label>
            <select name='supplier' id='supplier' class='form-select'>           
    ";
                    for($i = 0 ; $i < count($data) ; $i++){
                        echo "<option value='" . htmlspecialchars($data[$i]['supplier_id'], ENT_QUOTES, 'UTF-8') . "'> " . htmlspecialchars($data[$i]['supplier_desc'], ENT_QUOTES, 'UTF-8') . " </option>";
                    }
    echo "          </select>
        </div>
        <div class='col-md-6'>
            <label for='name' class='form-label'>ชื่อสินค้า</label>
            <input type='text' class='form-control' id='name' name='name' placeholder='ชื่อสินค้า' required>
        </div>
        <div class='col-md-6'>
            <label for='cost' class='form-label'>ราคาซื้อ</label>
            <input type='text' class='form-control' id='cost' name='cost' placeholder='ราคาซื้อ' required>
        </div>
        <div class='col-md-6'>
            <label for='price' class='form-label'>ราคาขาย</label>
            <input type='text' class='form-control' id='price' name='price' placeholder='ราคาขาย' required>
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