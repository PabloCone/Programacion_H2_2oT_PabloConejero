<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Definición de metadatos para la página, incluyendo codificación de caracteres y adaptabilidad a dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Tarea</title>
    <!-- Enlace al archivo CSS para aplicar estilos -->
    <link rel="stylesheet" href="EstilosHito2.css">
</head>
<body>
    <!-- Barra de navegación con enlaces a las páginas de añadir tarea y consultar tareas -->
    <nav>     
        <a href="AñadirTarea.php">Añadir una tarea.</a>
        <a href="ConsultarTareas.php">Consultar tareas.</a>
    </nav>

    <!-- Comprobación de la sesión activa -->
    <?php
        session_start();  // Inicia la sesión para gestionar el estado del usuario (por ejemplo, su correo electrónico)
        
        if (isset($_SESSION['email'])) {
            // Si la sesión está activa, se obtiene el correo electrónico del usuario
            $email = $_SESSION['email'];
        } else {
            // Si no hay sesión activa, se redirige al usuario a la página de inicio de sesión
            echo "No hay sesión iniciada.";
            header('Location: InicioSesion.php');
            exit();  // Se detiene la ejecución para evitar que se ejecute el código siguiente sin sesión activa
        }
    ?>

    <!-- Formulario para añadir una tarea -->
    <form action="" method="POST">
        <!-- Campo de autor que se rellena automáticamente con el correo del usuario registrado -->
        Autor: <input type='text' name='autor' value="<?php echo $email; ?>" readonly><br><br>
        
        <!-- Campos para capturar el nombre, descripción, día, hora y estado de la tarea -->
        Nombre*: <input type='text' name='nombre' min='0' max='120' required><br><br>
        Descripción: <input type='text' name='descripcion'><br><br>
        Día: <input type="date" name="dia" required><br><br>
        Hora: <input type="time" name="hora" required><br><br>

        <!-- Desplegable para seleccionar el estado de la tarea -->
        Estado: 
        <select name="estado" id="estado" required>
            <option value="" disabled selected>Selecciona</option>
            <option value="Pendiente">Pendiente</option>
            <option value="En progreso">En progreso</option>
            <option value="Completada">Completada</option>
        </select>
        <br><br>
        
        <!-- Botón para enviar el formulario -->
        <input type="submit" value="Registrar">
    </form>

    <?php
        // Incluir el archivo de conexión a la base de datos
        include 'Plataforma2.php';

        // Verificar si la conexión a la base de datos fue exitosa
        if ($mysql->connect_error) {
            // Si hay un error de conexión, muestra un mensaje y termina la ejecución
            die('Error de Conexión (' . $mysql->connect_errno . ') ' . $mysql->connect_error);
        }

        // Verifica si el formulario fue enviado mediante el método POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recoge los datos del formulario
            $nombre_tarea = $_POST["nombre"];
            $descripcion = $_POST["descripcion"];
            $dia = $_POST["dia"];
            $hora = $_POST["hora"];
            $estado = $_POST["estado"];

            // Realiza una consulta SQL para insertar la tarea en la base de datos
            $sql_insert = "INSERT INTO tareas (autor, nombre, descripcion, dia, hora, estado) 
                           VALUES ('$email', '$nombre_tarea', '$descripcion', '$dia', '$hora', '$estado')";
            $resultado_insert = $mysql->query($sql_insert);  // Ejecuta la consulta

            // Verifica si la inserción fue exitosa
            if ($resultado_insert) {
                // Si la tarea se registró correctamente, muestra un mensaje de éxito
                echo "<div class='message'>La tarea se ha registrado correctamente. </div>";
                echo "<br>";
            } else {
                // Si hubo un error al insertar la tarea, muestra un mensaje de error
                echo "<div class='message' style='color: red;'>Error al registrar la tarea: " . $mysql->error . "</div>";
            }
        }
    ?>

    <!-- Botón para cerrar sesión -->
    <div class="button-container">
        <a href="CierreSesion.php">
            <!-- Enlace al archivo para cerrar sesión, mostrando un botón -->
            <button type="button">Cerrar Sesión</button>
        </a>
    </div>
</body>
</html>

