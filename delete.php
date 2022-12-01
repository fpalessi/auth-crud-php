<?php

    $config = include "config/database.php";

    $result = [
        "error" => false,
        "msg" => ""
    ];

    try {    
        $id = $_GET['id'];
        $query = "DELETE FROM students WHERE id =" . $id;

        $stmt = $conexion->prepare($query);
        $stmt->execute();

        header("Location: index.php");
    } catch(PDOException $error) {
        $result['error'] = true;
        $result['msg'] = $error->getMessage();
    }

?>

<?php require "templates/header.php"; ?>

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                <?= $result['msg'] ?>
                </div>
            </div>
        </div>
    </div>

<?php require "templates/footer.php"; ?>