<?php include 'main/header.php'; ?>
  <body>
    
    <?php include 'main/nav-bar.php'; ?>
    
    <section class="home-slider owl-carousel" style="height: 400px;">
      <div class="slider-item" style="background-image: url('images/user-register1.jpg');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
          <div class="row slider-text align-items-center justify-content-center">
            <div class="col-md-10 col-sm-12 ftco-animate text-center" style="padding-bottom: 25%;">
              <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Inicio</a></span> <span>Registro</span></p>
              <h1 class="mb-3">Bienvenido!</h1>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-5">
          <div class="col-md-7 text-center heading-section ftco-animate">
            <span class="subheading">Registro</span>
            <h2>Regístrese en nuestro sitio</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 dish-menu">

            <div class="nav nav-pills justify-content-center ftco-animate" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <a class="nav-link py-3 px-4 active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true"><span class="flaticon-meat"></span> Datos de Usuario</a>
            </div>

            <!--register as customer-->
            <div class="tab-content py-5" id="v-pills-tabContent">
              <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <div class="row">
                  <div class="col-lg-2"></div>
                  <div class="col-lg-8">
                    <div class="menus d-flex ftco-animate" style="background: white;">
                      <div class="row" style="width: 100%">
                        <div class="col-md-12">
                          <!-- Register as User -->
                          <form action="manage-insert.php" method="POST" enctype="multipart/form-data">
                            <!-- Username -->
                            <div class="form-group">
                              <input type="text" name="username" class="form-control" required="" placeholder="Nombre Completo">
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                              <input type="email" name="email" class="form-control" required="" placeholder="Tu Correo">
                            </div>
                            <!-- Phone -->
                            <div class="form-group">
                              <input type="text" name="phone" class="form-control" required="" placeholder="Celular">
                            </div>
                            <!-- Address -->
                            <div class="form-group">
                              <textarea name="address" cols="30" rows="2" class="form-control" placeholder="Direccion"></textarea>
                            </div>
                            <!-- Gender -->
                            <div class="form-group">
                              <select name="gender" class="form-control" required="">
                                <option value="">Selecciona Genero</option>
                                <option value="Male">Hombre</option>
                                <option value="Female">Mujer</option>
                                <option value="Other"></option>
                              </select>
                            </div>
                            <!-- Password -->
                            <div class="form-group">
                              <input type="password" name="password" class="form-control" required="" placeholder="Tu Contraseña">
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group">
                              <input type="submit" value="Register" name="regUser" class="btn btn-primary py-3 px-5">
                            </div>
                          </form>
                          <p class="text-center">¿Ya tienes una cuenta? <a href="login.php">Inicia Aqui</a></p>
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
