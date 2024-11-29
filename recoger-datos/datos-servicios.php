<?php

include("../conection-db/conectiondb.php");

$errores_message = "";
$atencion_message = "";
$aciertos_message = "";
$url = "../login.html";

// Verificar si el usuario ha iniciado sesion
session_start(); 
// Verificar si el usuario ha iniciado sesion
if (isset($_SESSION['email-login'])) {
    $email_login = $_SESSION['email-login']; 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tipo_servicio = htmlspecialchars(trim($_POST['tipo-servicio']));
        $descripcion = htmlspecialchars(trim($_POST['textarea-servicios']));
        $fecha_pedida = date("Y-m-d");

        // Insertar los datos en la base de datos
        $insertar_datosservicios = "INSERT INTO pedir_servicios (email_cliente, tipo_servicio, descripcion, fecha_pedida) VALUES ('$email_login', '$tipo_servicio', '$descripcion', '$fecha_pedida')";
        if ($conex->query($insertar_datosservicios) === TRUE) {
            $aciertos_message = "¡Ya hemos informado a nuestros técnicos, pronto se pondrán en contacto contigo -> " . htmlspecialchars($email_login) . " por correo electrónico o por teléfono!";
            $url = "../index.html";
        } else {
            $errores_message = "Hubo un problema con tu petición: " . $conex->error;
            $url = "../index.html";
        }
    } else {
        $errores_message = "No puedes solicitar servicios si no eres cliente, regístrate o inicia sesión.";
        $url = "../registro.html";
    }
} else {
    $errores_message = "Por favor, inicia sesión primero.";
    $url = "../login.html";
}

// Cerrar la conexión
$conex->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../img/miqsa-logo.jpeg" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if (!empty($aciertos_message)) : ?>
    <script>
        Swal.fire({
            title: '¡Solicitud Exitosa!',
            text: '<?php echo $aciertos_message; ?>',
            icon: 'success',
            timer: 5000,
            showConfirmButton: false
        }).then(function() {
            window.location.href = '<?php echo $url; ?>';
        });
    </script>
<?php elseif (!empty($errores_message)) : ?>
    <script>
        Swal.fire({
            title: 'Error',
            html: '<?php echo htmlspecialchars($errores_message); ?>',
            icon: 'error',
            timer: 5000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = '<?php echo $url; ?>';
        });
    </script>
<?php elseif (!empty($atencion_message)) : ?>
    <script>
        Swal.fire({
            title: 'Atención',
            text: '<?php echo $atencion_message; ?>',
            icon: 'warning',
            timer: 5000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = '<?php echo $url; ?>';
        });
    </script>
<?php endif; ?>
</body>
</html>
