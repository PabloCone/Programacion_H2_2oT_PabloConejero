<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Se definen los metadatos como la codificación y la configuración para que la página sea adaptable a dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <!-- Enlace al archivo de estilos CSS -->
    <link rel="stylesheet" href="EstilosHito2.css">
</head>
<body>
    <!-- Barra de navegación con enlaces a las páginas de inicio de sesión, añadir tarea y consultar tareas -->
    <nav>   
        <a href="InicioSesion.php">Iniciar Sesión.</a>  
        <a href="AñadirTarea.php">Añadir una tarea.</a>
        <a href="ConsultarTareas.php">Consultar tareas.</a>
    </nav>

    <!-- Formulario para iniciar sesión con campos para el email y la contraseña -->
    <form action="" method="POST">
        <!-- Mensajes indicando que los campos son obligatorios -->
        Correo Electrónico*: <input type="email" name="email" required>
        <br><br>
        Contraseña*: <input type="password" name="contrasena" required>
        <br><br>
        <!-- Botón para enviar el formulario -->
        <input type="submit" value="Iniciar sesión">
        <br><br>
        <!-- Enlace para redirigir al usuario a la página de registro si no tiene cuenta -->
        ¿No estás registrado?
        <a href="Registro.php">Regístrate Aquí</a>
    </form>

    <?php
        // Inicia la sesión PHP para gestionar las sesiones de usuario
        session_start();

        // Incluye el archivo que contiene la conexión a la base de datos
        include 'Plataforma2.php';

        // Verifica si hay un error en la conexión con la base de datos
        if ($mysql->connect_error) {
            // Si hay un error, se detiene el proceso y muestra el error
            die('Error de Conexión (' . $mysql->connect_errno . ') ' . $mysql->connect_error);
        }

        // Verifica si el formulario de inicio de sesión fue enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recupera el email y la contraseña del formulario
            $email = $_POST["email"] ?? null;
            $contrasena = $_POST["contrasena"] ?? null;

            // Verifica si ambos campos fueron proporcionados
            if ($email && $contrasena) {
                // Realiza una consulta a la base de datos para encontrar un usuario con el email ingresado
                $sql = "SELECT * FROM usuarios WHERE email='$email'";
                $resultado = $mysql->query($sql);

                // Si se encuentra un usuario con ese email
                if ($resultado->num_rows > 0) {
                    $usuario = $resultado->fetch_assoc(); // Obtiene los datos del usuario

                    // Verifica si la contraseña proporcionada es correcta utilizando la función password_verify
                    if (password_verify($contrasena, $usuario['contrasena'])) {
                        // Si la contraseña es correcta, guarda el email del usuario en la sesión y lo redirige a la página para añadir tareas
                        $_SESSION['email'] = $email;
                        header('Location: AñadirTarea.php');
                        exit();
                    } else {
                        // Si la contraseña es incorrecta, muestra un mensaje de error
                        echo "<h2>Datos incorrectos.</h2>";
                    }
                } else {
                    // Si no se encuentra un usuario con ese email, muestra un mensaje de error
                    echo "<h2>Datos incorrectos.</h2>";
                }
            } else {
                // Si no se completaron todos los campos, muestra un mensaje de advertencia
                echo "<h2>Por favor, completa todos los campos.</h2>";
            }
        }
    ?>
</body>
</html>

