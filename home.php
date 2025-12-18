<?php   
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

echo  '
<main role="main" class="container" style="margin-top: 60px;">

<div class="starter-template">

<a href="addFix.php" class="btn btn-success btn-lg"> เพิ่มงานซ่อม </a>

</div>
</main>
</body>
</html>

';