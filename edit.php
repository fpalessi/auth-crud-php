<?php
    include 'utilities/escape.php';

    $config = include 'config/database.php';

    $result = [
    'error' => false,
    'msg' => ''
    ];

    if (!isset($_GET['id'])) {
        $result['error'] = true;
        $result['msg'] = 'El alumno no existe';
    }

    if (isset($_POST['submit'])) {
        try {

            $student = [
                "id"        => $_GET['id'],
                "nombre"    => $_POST['nombre'],
                "apellido"  => $_POST['apellido'],
                "email"     => $_POST['email'],
                "edad"      => $_POST['edad']
            ];
            
            $query = "UPDATE students SET
                nombre = :nombre,
                apellido = :apellido,
                email = :email,
                edad = :edad,
                updated_at = NOW()
                WHERE id = :id";

            $stmt = $conexion->prepare($query);
            $stmt->execute($student);

        } catch(PDOException $error) {
            $result['error'] = true;
            $result['msg'] = $error->getMessage();
        }
    }

    try {
            
        $id = $_GET['id'];
        $query = "SELECT * FROM students WHERE id =" . $id;

        $stmt = $conexion->prepare($query);
        $stmt->execute();

        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            $result['error'] = true;
            $result['msg'] = 'No se ha encontrado el alumno';
        }

    } catch(PDOException $error) {
        $result['error'] = true;
        $result['msg'] = $error->getMessage();
    }
?>

<?php require "templates/header.php"; ?>

<?php
    if ($result['error']) {
    ?>
    <div class="container mt-2">
        <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
            <?= $result['msg'] ?>
            </div>
        </div>
        </div>
    </div>
    <?php
    }
?>

<?php
    if (isset($_POST['submit']) && !$result['error']) {
    ?>
        <div class="container mt-2">
            <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                El alumno <?= escape($student['nombre']) . ' ' . escape($student['apellido'])  ?>  ha sido actualizado correctamente
                </div>
            </div>
            </div>
        </div>
    <?php
    }
?>

<?php
    if (isset($student) && $student) {
    ?>
    <div class="container">
        <div class="row">
        <div class="col-md-12">
            <h2 class="mt-4">Editando el alumno <?= escape($student['nombre']) . ' ' . escape($student['apellido'])  ?></h2>
            <hr>
            <form method="post">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="<?= escape($student['nombre']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" id="apellido" value="<?= escape($student['apellido']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= escape($student['email']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="edad">Edad</label>
                <input type="number" name="edad" id="edad" value="<?= escape($student['edad']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
                <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
            </div>
            </form>
        </div>
        </div>
    </div>
    <?php
    }
?>

<?php require "templates/footer.php"; ?>