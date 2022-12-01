<?php

  include 'utilities/escape.php';


  if (isset($_POST['submit'])) {
  
    $result = [
      'error' => false,
      'msg' => 'El alumno ' . escape($_POST['nombre']) . ' ha sido agregado con éxito' 
    ];
    
    $config = include 'config/database.php';

    try {
      $student = [
        "nombre"   => $_POST['nombre'],
        "apellido" => $_POST['apellido'],
        "email"    => $_POST['email'],
        "edad"     => $_POST['edad'],
      ];

      // No need to sanitize as we using prepared-statements
      $query = "INSERT INTO students (nombre, apellido, email, edad)";
      $query .= "values (:" . implode(", :", array_keys($student)) . ")";

      $stmt = $conexion->prepare($query);

      // Execute the stmt
      $stmt->execute($student);

    } catch(PDOException $error) {
      $result['error'] = true;
      $result['msg'] = $error->getMessage();
    }
  }
?>

<?php include "templates/header.php"; ?>

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
        <h2 class="mt-4">Crea un alumno</h2>
        <hr>
        <form method="post">
          <div class="form-group mt-2">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="edad">Edad</label>
            <input type="number" name="edad" max="99" min="4" id="edad" class="form-control" required>
          </div>
          <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Crear Alumno">
            <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php include "templates/footer.php"; ?>