<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "No has iniciado sesión, vuelve a la pagina de inicio";
    // header("Location: ../../index.php");
    exit();
}

require_once '../../config/database.php';

$database = new Database();
$conn = $database->connect();

$portfolio = [
    'foto' => 'foto',
    'nombre' => 'Tu nombre',
    'apellido' => 'Tu apellido',
    'correo' => 'correo@ejemplo.com',
    'puesto' => 'Diseñador, desarrollador, profesor...',
    'experiencia' => [
        'Ejemplo de experiencia laboral 1',
        'Ejemplo de experiencia laboral 2'
    ],
    'perfil_personal' => 'Este es un resumen breve de tu experiencia y habilidades.',
    'habilidades' => 'Habilidad 1, habilidad 2, habilidad 3...',
    'educacion' => 'Licenciatura en Diseño Gráfico, Maestría en Desarrollo Web...'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $imageData = file_get_contents($_FILES['image']['tmp_name']);


        $sql = "UPDATE portfolio SET foto = :foto WHERE user_id = :user_id";


        $stmt = $conn->prepare($sql);


        $stmt->bindParam(':foto', $imageData, PDO::PARAM_LOB);
        $stmt->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_INT);


        if ($stmt->execute()) {
            echo "La imagen se actualizó correctamente.";
        } else {
            echo "Hubo un error al actualizar la imagen.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Portafolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f9f9f9;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="text-center mb-4">Editar Mi Portafolio</h2>


        <form action="save_portfolio.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4 text-center">
                <label for="foto" class="form-label">Subir foto</label>
                <input type="file" class="form-control mt-2" name="foto">
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre(s)</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="<?= htmlspecialchars($portfolio['nombre']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido(s)</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="<?= htmlspecialchars($portfolio['apellido']) ?>" required>
                </div>
                <div class="col-md-12">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="<?= htmlspecialchars($portfolio['correo']) ?>" required>
                </div>
                <div class="col-md-12">
                    <label for="puesto" class="form-label">Puesto</label>
                    <input type="text" class="form-control" id="puesto" name="puesto" placeholder="<?= htmlspecialchars($portfolio['puesto']) ?>">
                </div>
            </div>


            <h4 class="mt-4">Experiencia Laboral</h4>
            <div id="experiencia-laboral">
                <?php foreach ($portfolio['experiencia'] as $index => $exp): ?>
                    <div class="mb-3">
                        <textarea class="form-control" name="experiencia[]" rows="2"><?= htmlspecialchars($exp) ?></textarea>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-primary btn-sm" id="agregar-experiencia">Agregar experiencia</button>


            <h4 class="mt-4">Perfil Personal</h4>
            <div class="mb-3">
                <textarea class="form-control" name="perfil_personal" rows="4"><?= htmlspecialchars($portfolio['perfil_personal']) ?></textarea>
            </div>
            <h4 class="mt-4">Habilidades</h4>
            <div class="mb-3">
                <textarea class="form-control" name="habilidades" rows="4"><?= htmlspecialchars($portfolio['habilidades']) ?></textarea>
            </div>

            <h4 class="mt-4">Educación</h4>
            <div class="mb-3">
                <textarea class="form-control" name="educacion" rows="4"><?= htmlspecialchars($portfolio['educacion']) ?></textarea>
            </div>


            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
                <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('agregar-experiencia').addEventListener('click', function() {
            const experienciaDiv = document.getElementById('experiencia-laboral');
            const nuevaExperiencia = document.createElement('div');
            nuevaExperiencia.classList.add('mb-3');
            nuevaExperiencia.innerHTML = '<textarea class="form-control" name="experiencia[]" rows="2"></textarea>';
            experienciaDiv.appendChild(nuevaExperiencia);
        });
    </script>
</body>

</html>