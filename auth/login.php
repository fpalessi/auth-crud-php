<?php
    // Initialize the session
    session_start();

    include "../config/database.php";
      
    if (!empty($_POST["email"]) && !empty($_POST["password"])){
      $stmt = $conexion -> prepare("SELECT id, email, password FROM users WHERE email = :email");
      $stmt->bindParam(":email", $_POST["email"]);
      $stmt->execute();
      $results = $stmt->fetch(PDO::FETCH_ASSOC);

      $msg = "";

      // If user with 'x' email does exist and pass matches -> store the id on session variable
      if(is_countable($results) > 0 && password_verify($_POST["password"], $results["password"])){
        $_SESSION['user_id'] = $results["id"];
        header("Location: ../index.php");
      } else {
        $msg = "Usuario o contraseña incorrectos, inténtelo de nuevo.";
      }
    }
?>


<?php include "../templates/header.php"; ?>    

  <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="mt-4">Bienvenido, inicia sesión aquí</h2>
          <hr>

          <form action="login.php" method="post">
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="password">Contraseña</label>
              <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <?php if(!empty($msg)): ?>
              <p style="color: red;"> <?= $msg ?></p>
            <?php endif; ?>
            <div class="form-group">
              <input type="submit" name="login" class="btn btn-primary" value="Iniciar Sesión">
              <a class="btn btn-primary" href="../index.php">Regresar al inicio</a>
              <a class="btn btn-primary" href="./signup.php">Registrarme</a>
            </div>
          </form>
        </div>
      </div>
  </div>

<?php include "../templates/footer.php"; ?>