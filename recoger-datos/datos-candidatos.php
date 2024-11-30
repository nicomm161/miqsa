<!-- Validación y registro de los datos para almacenar en la base de datos -->
<?php 

// Conexión a la base de datos
include("../conection-db/conectiondb.php");

// Variables de patrones y control del bucle
$patronEmail = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
$patronTelefono = "/^[0-9]{3}-[0-9]{3}-[0-9]{3}$/";
$patronCurriculum = "/(\.pdf)$/i";
$control = true;
$errores = array(); // Control de errores añadiéndolos a un array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conex, $_POST["nombre"]);
    $email = mysqli_real_escape_string($conex, $_POST["email"]);
    $telefono = mysqli_real_escape_string($conex, $_POST["telefono"]);
    $experiencia = mysqli_real_escape_string($conex, $_POST["experiencia"]);
    $formacion = mysqli_real_escape_string($conex, $_POST["formacion"]);
    $disponibilidad = mysqli_real_escape_string($conex, $_POST["disponibilidad"]);
    $fecha_registro = date('Y-m-d');
    $success_message = "";
    $error_message = "";

    // Validaciones
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match($patronEmail, $email)) { 
        $errores[] = "¡El email no es correcto!"; 
        $control = false; 
    } 

    if (!preg_match($patronTelefono, $telefono)) { 
        $errores[] = "¡El teléfono no es correcto, sigue el formato 123-123-123!"; 
        $control = false; 
    }

    // Validar currículum
    if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] == 0) {
        $curriculumNombre = $_FILES['curriculum']['name'];
        $curriculumTemp = $_FILES['curriculum']['tmp_name'];
        $curriculumTipo = $_FILES['curriculum']['type'];
        $curriculumExtension = strtolower(pathinfo($curriculumNombre, PATHINFO_EXTENSION));

        if (!preg_match($patronCurriculum, $curriculumNombre) || $curriculumExtension != 'pdf') {
            $errores[] = "¡El currículum debe ser un archivo PDF!";
            $control = false;
        }
    } else {
        $errores[] = "¡El currículum es obligatorio de subir!";
        $control = false;
    }

    // Control de errores con la base de datos al insertar los datos
    if ($control) {
        // Mover el archivo subido a la carpeta de destino
        $rutaCurriculum = "../uploads/" . basename($curriculumNombre);
        if (move_uploaded_file($curriculumTemp, $rutaCurriculum)) {
            $insertar_candidato = "INSERT INTO candidatos (nombre, email, telefono, experiencia, formacion, disponibilidad, curriculum, fecha_creacion) 
                                  VALUES ('$nombre', '$email', '$telefono', '$experiencia', '$formacion', '$disponibilidad', '$rutaCurriculum', '$fecha_registro')";
            try {
                if ($conex->query($insertar_candidato) === TRUE) {
                    $success_message = "¡Candidatura registrada con éxito!";
                    $redirect_url = "../web-usuarios/confirmacion.html";
                }
            } catch (Exception $e) {
                if (mysqli_errno($conex) == 1062) {
                    $error_message = "¡Este correo ya existe! Por favor, intenta con otro.";
                    $redirect_url = "../web-usuarios/candidatura.html";
                } else {
                    $error_message = "Error: " . htmlspecialchars($e->getMessage());
                }
            }
        } else {
            $errores[] = "Hubo un problema al subir el archivo del currículum.";
            $error_message = implode("<br>", array_map('htmlspecialchars', $errores));
        }
    } else {
        $error_message = implode("<br>", array_map('htmlspecialchars', $errores));
    }
    $conex->close();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatura</title>
    <link rel="shortcut icon" href="../img/miqsa-logo.jpeg" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if (!empty($success_message)) : ?>
    <script>
        Swal.fire({
            title: '¡Candidatura registrada con éxito!',
            text: 'Gracias por aplicar, espera 5 segundos o pulse ok',
            icon: 'success',
            timer: 5000,
            showConfirmButton: false
        }).then(function() {
            window.location.href = '<?php echo $redirect_url; ?>';
        });
    </script>
<?php elseif (!empty($error_message)) : ?>
    <script>
        Swal.fire({
            title: 'Error',
            html: 'Algo salió mal, espera 5 segundos o pulse ok: <?php echo $error_message; ?>',
            icon: 'error',
            timer: 5000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = '<?php echo $redirect_url; ?>';
        });
    </script>
<?php endif; ?>
</body>
</html>
