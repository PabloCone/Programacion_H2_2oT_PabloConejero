<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Definición de metadatos para la página, incluyendo codificación de caracteres y adaptabilidad a dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Enlace al archivo CSS para aplicar estilos -->
    <link rel="stylesheet" href="EstilosHito2.css">
</head>
<body>
    <!-- Barra de navegación con enlaces a las páginas de inicio de sesión, añadir tarea y consultar tareas -->
    <nav>   
        <a href="InicioSesion.php">Iniciar Sesión.</a>  
        <a href="AñadirTarea.php">Añadir una tarea.</a>
        <a href="ConsultarTareas.php">Consultar tareas.</a>
    </nav>

    <!-- Formulario para que los usuarios se registren proporcionando su información -->
    <form action="" method="POST">
        <!-- Campos para capturar nombre, apellidos, edad, ciudad, correo electrónico y contraseña -->
        Nombre*: <input type='text' name='nombre' required><br><br>
        Apellidos*: <input type='text' name='apellidos' required><br><br>
        Edad*: <input type='number' name='edad' min='0' max='120' required><br><br>
        Ciudad: <input type='text' name='ciudad'><br><br>
        <!-- Mensaje indicando que estos datos se usarán para iniciar sesión en el futuro -->
        Datos que se usarán para iniciar sesión en el futuro:
        <br><br>
        Correo Electrónico*: <input type="email" name="email" required><br><br>
        Contraseña*: <input type="password" name="contrasena" required><br><br>
        
        <!-- Checkbox para aceptar la política de privacidad, el cual es obligatorio -->
        <input type="checkbox" name="politica" value="1" required> Acepto la política de privacidad.<br><br>

        <!-- Botón para enviar el formulario -->
        <input type="submit" value="Registrar">
    </form>

    <?php
        // Inicia la sesión para gestionar el estado del usuario (por ejemplo, su correo electrónico)
        session_start();

        // Incluir el archivo de conexión a la base de datos
        include 'Plataforma2.php';

        // Verifica si la conexión a la base de datos fue exitosa
        if ($mysql->connect_error) {
            die('Error de Conexión (' . $mysql->connect_errno . ') ' . $mysql->connect_error);
        }

        // Verifica si el formulario ha sido enviado mediante el método POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Verifica si el checkbox de política de privacidad está marcado (es obligatorio)
            if (isset($_POST["politica"]) && $_POST["politica"] == "1") {
                // Recoge los datos del formulario
                $email = $_POST["email"];
                $contrasena = $_POST["contrasena"];
                $nombre = $_POST["nombre"];
                $apellidos = $_POST["apellidos"];
                $edad = $_POST["edad"];
                $ciudad = $_POST["ciudad"] ?? '';  // Si no se proporciona ciudad, se usa un valor vacío.

                // Realiza una consulta a la base de datos para verificar si el correo electrónico ya está registrado
                $sql_select = "SELECT * FROM usuarios WHERE email='$email'";
                $resultado_check = $mysql->query($sql_select);

                if ($resultado_check->num_rows > 0) {
                    // Si el correo ya está registrado, muestra un mensaje de error
                    echo "Este correo ya está registrado.";
                } else {
                    // Si el correo no está registrado, encripta la contraseña antes de almacenarla
                    $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);
                    // Inserta los datos del usuario en la base de datos
                    $sql_insert = "INSERT INTO usuarios (nombre, apellidos, email, contrasena, edad, ciudad) 
                                   VALUES ('$nombre', '$apellidos', '$email', '$hashed_password', $edad, '$ciudad')";
                    
                    // Ejecuta la consulta de inserción
                    if ($mysql->query($sql_insert)) {
                        // Si el registro es exitoso, guarda el email en la sesión y muestra un mensaje de éxito
                        $_SESSION['email'] = $email;
                        echo "<h2>¡Te has registrado correctamente! Haz click en el siguiente botón para iniciar sesión.</h2>";

                        // Muestra un formulario con un botón para iniciar sesión
                        echo "<form action='InicioSesion.php' method='POST'>
                                <input type='submit' value='Iniciar Sesión'>
                              </form>";
                    } else {
                        // Si hay un error al intentar insertar el usuario, muestra un mensaje de error
                        echo "Error al registrar: " . $mysql->error;
                    }
                }
            } else {
                // Si el usuario no ha aceptado la política de privacidad, muestra un mensaje de advertencia
                echo "Debes aceptar la política de privacidad para registrarte.";
            }
        }
    ?>

</body>
</html>
