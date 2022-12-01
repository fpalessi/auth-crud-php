<?php

    include '../utilities/escape.php';
    session_start();

    if (isset($_POST["register"])){
        $result = [
            'error' => false,
            'msg' => 'El usuario ' . escape($_POST['username']) .  ' se ha registrado con éxito'
        ];

        $config = include '../config/database.php';

        // Store data on variables
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        try {
            // Hashing the password
            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password) VALUES (?,?,?)";

            $stmt = $conexion -> prepare($sql);
            $stmt -> execute([$username, $email, $password]);

        
        } catch (PDOException $error) {
            $result['error'] = true;
            $result['msg'] = $error -> getMessage();
        }
    }
?>

<?php include "../templates/header.php"; ?>    

<?php

  if (isset($result)) {
    ?>
    <div class="container mt-3">
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-<?= $result['error'] ? 'danger' : 'success' ?>" role="alert">
            <?= $result['msg'] ?>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

?>

    <div class="container">
        <div class="row">
        <div class="col-md-12">
            <h2 class="mt-4">Bienvenido, registrate aquí</h2>
            <hr>
            <form method="post">
            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" name="username" id="username" pattern="[a-zA-Z0-9]+" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" name="register" class="btn btn-primary" value="Registrarse">
                <a class="btn btn-primary" href="../index.php">Regresar al inicio</a>
                <a class="btn btn-primary" href="./login.php">Iniciar sesión</a>
            </div>
            </form>
        </div>
        </div>
    </div>

<?php include "../templates/footer.php"; ?>