<?php include 'main/header.php'; ?>

<body>

  <?php include 'main/nav-bar.php'; ?>
  <!-- END nav -->

  <section class="home-slider owl-carousel" style="height: 400px;">
    <div class="slider-item" style="background-image: url('images/user-login1.jpg');"
      data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row slider-text align-items-center justify-content-center">
          <div class="col-md-10 col-sm-12 ftco-animate text-center" style="padding-bottom: 25%;">
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Inicio</a></span> <span>Login</span></p>
            <h1 class="mb-3">Bienvenido</h1>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section bg-light">
    <div class="container">
      <div class="row justify-content-center mb-5 pb-5">
        <div class="col-md-7 text-center heading-section ftco-animate">
          <span class="subheading">Inicia Sesion</span>
          <h2>Unete a nuestro sitio</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 dish-menu">

          <div class="nav nav-pills justify-content-center ftco-animate" id="v-pills-tab" role="tablist"
            aria-orientation="vertical">
            <a class="nav-link py-3 px-4 active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home"
              role="tab" aria-controls="v-pills-home" aria-selected="true"><span class="flaticon-meat"></span> Ingresa Credenciales</a>

          </div>

          <div class="tab-content py-5" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
              <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                  <div class="menus d-flex ftco-animate" style="background: white;">
                    <div class="row" style="width: 100%">
                      <div class="col-md-12">
                        <form action="" method="POST" enctype="multipart/form-data">
                          <div class="form-group">
                            <input type="email" name="email" class="form-control" required="" placeholder="Tu Correo">
                          </div>
                          <div class="form-group">
                            <input type="password" name="password" class="form-control" required=""
                              placeholder="Tu Contraseña">
                          </div>
                          <div class="form-group">
                            <input type="submit" value="Login" name="login" class="btn btn-primary py-3 px-5">
                          </div>
                        </form>
                        <p class="text-center">Para Registrarte <a href="register.php">Click Aqui.</a> </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- END -->

          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include 'main/instagram.php'; ?>

  <?php include 'main/footer.php'; ?>

  <?php include 'main/script.php'; ?>



</body>

</html>

<?php
if (isset($_POST['login'])) {

  $email = $_POST['email'];
  $password = $_POST['password'];

  include 'dbCon.php';
  $con = connect();

  function encryptPassword($password, $key)
  {
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($password, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    return base64_encode($iv . $hmac . $ciphertext_raw);
  }

  function decryptPassword($encryptedPassword, $key)
  {
    $c = base64_decode($encryptedPassword);
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    if (hash_equals($hmac, $calcmac)) {
      return $original_plaintext;
    }
    return false;
  }
  $encryptionKey = "YourSecretKeyHere";

  // Fetch user with the provided email
  $emailSQL = "SELECT * FROM users WHERE email = '$email';";
  $emailResult = $con->query($emailSQL);

  if ($emailResult->num_rows <= 0) {
    echo '<script>alert("This Email Does Not Exist.")</script>';
    echo '<script>window.location="login.php"</script>';
  } else {
    $user = $emailResult->fetch_assoc();

    // Check if the role is "user" first
    if ($user['role'] !== 'user') {
      echo '<script>alert("Access denied. You are not allowed to log in.")</script>';
      echo '<script>window.location="login.php";</script>';
      exit(); // Ensure no further code runs
    }

    // Decrypt the stored password and compare
    $decryptedPassword = decryptPassword($user['password'], $encryptionKey);

    // Verify password
    if ($decryptedPassword === false || $password !== $decryptedPassword) {
      echo '<script>alert("This Password is Incorrect.")</script>';
      echo '<script>window.location="login.php"</script>';
    } else {
      // Check user status
      if ($user['status'] == 0) {
        echo '<script>alert("Your account is not activated by the admin.")</script>';
        echo '<script>window.location="login.php"</script>';
      } elseif ($user['status'] == 9) {
        echo '<script>alert("Your account has been deactivated by the admin.")</script>';
        echo '<script>window.location="login.php"</script>';
      } else {
        // Successful login for role 'user'
        session_start(); // Ensure session is started if not already

        $_SESSION['isLoggedIn'] = TRUE;
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['role'] = $user['user'];
        $_SESSION['user_role'] = 'user';
        $_SESSION['address'] = $user['address'];
        $_SESSION['gender'] = $user['gender'];

        // Success message and redirection
        echo '<script>alert("Login Successful!"); window.location="index.php";</script>';
      }
    }
  }
}
?>