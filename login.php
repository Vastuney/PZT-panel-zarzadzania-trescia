<?php
require $_SERVER["DOCUMENT_ROOT"]."/lib/sessions.php";
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: index.php");
  exit;
}
?>
<html lang="pl" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>PZT: Logowanie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" integrity="sha512-NmLkDIU1C/C88wi324HBc+S2kLhi08PN5GDeUVVVC/BVt/9Izdsc9SVeVfA1UZbY3sHUlDSyRXhCzHfr6hmPPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/main.css?v=2">
    <link rel="stylesheet" href="/assets/css/login.css?v=4">
  </head>
  <body>
    <section class="vh-100">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6 text-black login_v">

            <div class="px-5 ms-xl-4">
              <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
              <span class="h1 fw-bold mb-0">PZT</span>
            </div>

            <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">

              <form style="width: 23rem;" class="ajax needs-validation" method="POST">

                <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Zaloguj się</h3>

                  <div class="form-outline mb-4">
                    <label for="validation3" class="form-label">Email</label>
                    <input type="email" id="validation3" class="form-control form-control-lg" name="email" minlength="3" maxlength="60" required/>
                  </div>

                  <div class="form-outline mb-4">
                    <label for="validation4" class="form-label">Hasło</label>
                    <input type="password" id="validation4" class="form-control form-control-lg" name="password" minlength="5" maxlength="30" required/>
                  </div>

                  <div class="pt-1 mb-4">
                    <input type="hidden" name="method" value="loginIn"  required>
                    <input class="btn btn-info btn-lg btn-block" type="submit" value="Zaloguj"></input>
                  </div>

                <p>Nie posiadasz konta?  <a href="/register.php" class="link-info">Zarejestruj się</a></p>

              </form>

            </div>

          </div>
          <div class="col-sm-6 px-0 d-none d-sm-block">
            <img src="/assets/img/loginbg.jpg" alt="Logowanie" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
          </div>
        </div>
      </div>
    </section>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/5d0458e069.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="../assets/js/ajax.js?v=<?php echo time(); ?>" type="text/javascript"></script>


  </body>
</html>
