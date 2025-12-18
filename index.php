<!doctype html>
    <html lang='th'>
      <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN' crossorigin='anonymous'>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js' integrity='sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL' crossorigin='anonymous'></script>
        <script src='https://unpkg.com/htmx.org@1.9.10' integrity='sha384-D1Kt99CQMDuVetoL1lrYwg5t+9QdHe7NLX/SoJYkXDFfX37iInKRy5xLSi8nO7UC' crossorigin='anonymous'></script>
        <title> เข้าสู่ระบบ </title>
    </head>
    <body class="bg-light">
        <div class="container">
        <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4">
        <div class="card shadow">
        <div class="card-body">
        <h1 class="card-title text-center mb-4">เข้าสู่ระบบ</h1>
        <?php 
        session_start();
        if(isset($_POST['username']) && isset($_POST['password'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $svname = 'localhost';
            $dbuser = 'root';
            $dbpass = '';
            $dbname = 'fixmoto';
            
            try {
                $dsn = "mysql:host={$svname};dbname={$dbname};charset=utf8";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                $pdo = new PDO($dsn, $dbuser, $dbpass, $options);
                
                $sql = "SELECT member_id, username, password FROM member_login WHERE username = :username AND password = :password";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['username' => $username, 'password' => $password]);
                $row = $stmt->fetch();
                
                if ($row) {
                    $_SESSION['user_id'] = $row["member_id"];
                    $_SESSION['status'] = 'login';
                    header("Location: home.php");
                    exit;
                } else {
                    echo "
                    <div class='alert alert-danger' role='alert'>
                        รหัสผ่านผิดพลาด หรือ ไม่พบผู้ใช้งานนี้
                    </div>
                    ";
                }
            } catch (PDOException $e) {
                echo "
                <div class='alert alert-danger' role='alert'>
                    เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล
                </div>
                ";
            }
        }
?>
            <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            </form>
        </div>
        </div>
        </div>
        </div>
        </div>
    </body>
    </html>