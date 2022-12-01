<?php

    session_start();

    include 'config/database.php';

    if (isset($_SESSION["user_id"])){
        $stmt = $conexion->prepare("SELECT id, email, password FROM users WHERE id=:id");
        $stmt->bindParam(":id", $_SESSION["user_id"]);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        $user = null;

        if(count($results) > 0){
            $user = $results;
        }
    }

?>

<?php

    include 'utilities/escape.php';

    $error = false;
    $config = include 'config/database.php';

    try {
        // Search bar logic

        if (isset($_POST['apellido'])) {
            $query = "SELECT * FROM students WHERE apellido LIKE '%" . $_POST['apellido'] . "%'";
        } else {
            $query = "SELECT * FROM students";
        }

        $stmt = $conexion->prepare($query);
        $stmt->execute();

        
        $students = $stmt->fetchAll();

    } catch(PDOException $error) {
        $error = $error->getMessage();
    }

    $title = isset($_POST['apellido']) ? 'Lista de alumnos (' . $_POST['apellido'] . ')' : 'Lista de alumnos';
?>

<?php include "templates/header.php"; ?>

<?php
    if ($error) {
    ?>
        <div class="container mt-2">
            <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                <?= $error ?>
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
            
            <!-- If user is logged in -->
            <?php if(!empty($user)): ?>
            <div class="d-flex justify-content-around">
                <div>
                    <h3 class="mt-4">Welcome <?= $user["email"] ?> 👋 
                </div>
                <div>
                    <a href="create.php" class="btn btn-primary mt-4">Crear alumno</a>
                    <a href="./auth/logout.php" class="btn btn-primary mt-4">Cerrar sesión</a>
                </div>
            </div>

            <?php else: ?>    
                <h3 class="mt-3">Regístrate o inicia sesión para añadir nuevos alumnos</h3>
                <a href="auth/login.php" class="btn btn-primary mt-4">Iniciar Sesión</a>
                <a href="./auth/signup.php" class="btn btn-primary mt-4">Registrarse</a>

            <?php endif; ?>
            
                <hr>
                <form method="post" class="form-inline">
                    <div class="form-group mr-3">
                        <input type="text" id="apellido" name="apellido" placeholder="Buscar por apellido" class="form-control">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-3"><?= $title ?></h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Edad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //If students exist we print them out on a table
                            if ($students && $stmt->rowCount() > 0) {
                                foreach ($students as $fila) {
                                ?>
                                    <tr>
                                        <td><?php echo escape($fila["id"]); ?></td>
                                        <td><?php echo escape($fila["nombre"]); ?></td>
                                        <td><?php echo escape($fila["apellido"]); ?></td>
                                        <td><?php echo escape($fila["email"]); ?></td>
                                        <td><?php echo escape($fila["edad"]); ?></td>
                                        <td>
                                            <a href="<?= 'edit.php?id=' . escape($fila["id"]) ?>">✏️&nbsp;Editar &nbsp;</a>
                                            <a href="<?= 'delete.php?id=' . escape($fila["id"]) ?>">🗑️&nbsp;Eliminar</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            }
                        ?>
                    <tbody>
                </table>
            </div>
        </div>
    </div>


<?php include "templates/footer.php"; ?>