<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Definición de la codificación de caracteres y la adaptabilidad a dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título de la página -->
    <title>Consultar Tareas</title>
    <!-- Enlace al archivo CSS para aplicar los estilos de la página -->
    <link rel="stylesheet" href="EstilosHito2.css">
</head>
<body>
    <!-- Barra de navegación con enlaces a otras páginas -->
    <nav>
        <a href="AñadirTarea.php">Añadir una tarea.</a>
        <a href="ConsultarTareas.php">Consultar tareas.</a>
    </nav>

    <?php
        // Inicia la sesión para acceder a los datos del usuario
        session_start();

        // Comprobar si la sesión está activa y si el email del usuario está guardado
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];  // Almacenar el email de la sesión
        } else {
            // Si no hay sesión activa, redirigir al usuario a la página de inicio de sesión
            echo "No hay sesión iniciada.";
            header('Location: InicioSesion.php');
            exit();  // Detiene la ejecución del código si no hay sesión
        }

        // Incluir el archivo con la conexión a la base de datos
        include 'Plataforma2.php';

        // Verifica si se ha solicitado actualizar una tarea
        if (isset($_POST['actualizar_tarea'])) {
            // Recoger los valores enviados por el formulario para actualizar la tarea
            $id_tarea = $_POST['id_tarea'];
            $dia = $_POST['dia'];
            $hora = $_POST['hora'];
            $estado = $_POST['estado'];
            
            // Ejecuta la consulta para actualizar la tarea en la base de datos
            $mysql->query("UPDATE tareas SET dia='$dia', hora='$hora', estado='$estado' WHERE id='$id_tarea'");
            echo "<p>Tarea actualizada correctamente.</p>";
            // Recargar automáticamente la página después de la actualización
            echo "<meta http-equiv='refresh' content='0'>";
        }

        // Eliminar tarea
        if (isset($_POST['eliminar_tarea'])) {
            // Recoge el ID de la tarea a eliminar
            $id_tarea = $_POST['id_tarea_eliminar'];
            // Ejecuta la consulta para eliminar la tarea de la base de datos
            $mysql->query("DELETE FROM tareas WHERE id='$id_tarea'");
            echo "<p>Tarea eliminada correctamente.</p>";
            // Recargar la página después de eliminar la tarea
            echo "<meta http-equiv='refresh' content='0'>";
        }

        // Consulta para obtener las tareas del usuario desde la base de datos
        $sql = "SELECT * FROM tareas WHERE autor = '$email'";
        $resultado = $mysql->query($sql);

        // Si el usuario tiene tareas registradas, mostrarlas en una tabla
        if ($resultado->num_rows > 0) {
            echo "<h1>Tareas Registradas</h1>";
            echo "<table border='1'>";
            echo "<tr><th>Autor</th><th>ID</th><th>Nombre</th><th>Descripción</th><th>Día</th><th>Hora</th><th>Estado</th><th>Acciones</th></tr>";

            // Itera sobre las tareas y las muestra en filas dentro de la tabla
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila["autor"] . "</td>";
                echo "<td>" . $fila["id"] . "</td>";
                echo "<td>" . $fila["nombre"] . "</td>";
                echo "<td>" . $fila["descripcion"] . "</td>";
                echo "<td>" . $fila["dia"] . "</td>";
                echo "<td>" . $fila["hora"] . "</td>";
                echo "<td>" . $fila["estado"] . "</td>";
                echo "<td>
                        <form action='' method='POST'>
                            <input type='hidden' name='id_tarea_eliminar' value='" . $fila['id'] . "'>
                            <input type='submit' name='eliminar_tarea' value='Eliminar'>
                        </form>
                      </td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            // Si no hay tareas registradas, mostrar mensaje correspondiente
            echo "<h2>No tienes tareas registradas.</h2>";
        }
    ?>

    <h2>Modificar Tarea</h2>
    <!-- Formulario para seleccionar la tarea a modificar -->
    <form action="" method="POST">
        <label for="tarea_modificar">Selecciona una tarea:</label>
        <select name="tarea_modificar" required>
            <option value="">Selecciona una tarea</option>
            <?php
                // Consulta para obtener las tareas del usuario y mostrarlas en un selector
                $resultado = $mysql->query("SELECT id, nombre FROM tareas WHERE autor = '$email'");
                if ($resultado->num_rows > 0) {
                    // Muestra las tareas disponibles como opciones en el selector
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
                    }
                } else {
                    // Si no hay tareas registradas, mostrar una opción vacía
                    echo "<option>No hay tareas registradas</option>";
                }
            ?>
        </select>
        <input type="submit" name="modificar" value="Modificar">
    </form>

    <?php
    // Si el usuario selecciona una tarea para modificar
    if (isset($_POST['modificar']) && !empty($_POST['tarea_modificar'])) {
        // Recupera la tarea seleccionada
        $tarea_id = $_POST['tarea_modificar'];
        $resultado = $mysql->query("SELECT * FROM tareas WHERE id = '$tarea_id'");
        $fila = $resultado->fetch_assoc();
    ?>
    
    <!-- Formulario para editar los detalles de la tarea seleccionada -->
    <form action="" method="POST">
        <input type="hidden" name="id_tarea" value="<?php echo $fila['id']; ?>">
        
        <!-- Muestra los datos actuales de la tarea, pero el nombre y la descripción son solo de lectura -->
        Nombre: <input type="text" value="<?php echo $fila['nombre']; ?>" disabled><br><br>
        Descripción: <input type="text" value="<?php echo $fila['descripcion']; ?>" disabled><br><br>
        
        <!-- Los campos de Día, Hora y Estado son editables -->
        Día: <input type="date" name="dia" value="<?php echo $fila['dia']; ?>"><br><br>
        Hora: <input type="time" name="hora" value="<?php echo $fila['hora']; ?>"><br><br>
        
        Estado: 
        <select name="estado" required>
            <option value="Pendiente" <?php echo ($fila['estado'] == "Pendiente" ? 'selected' : ''); ?>>Pendiente</option>
            <option value="En progreso" <?php echo ($fila['estado'] == "En progreso" ? 'selected' : ''); ?>>En progreso</option>
            <option value="Completada" <?php echo ($fila['estado'] == "Completada" ? 'selected' : ''); ?>>Completada</option>
        </select><br><br>
        
        <!-- Botón para actualizar la tarea -->
        <input type="submit" name="actualizar_tarea" value="Actualizar Tarea">
    </form>
    
    <?php } ?>
    <!-- Botón para cerrar sesión -->
    <div class="button-container">
        <button type="button" onclick="window.location.href='CierreSesion.php'">Cerrar Sesión</button>
    </div>
</body>
</html>
