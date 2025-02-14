<?php
// Inicia la sesión para poder manejar la información de la sesión del usuario
session_start();

// Elimina todas las variables de sesión, es decir, borra los datos almacenados en la sesión
session_unset();

// Destruye completamente la sesión, eliminando cualquier información de sesión guardada en el servidor
session_destroy();

// Redirige al usuario a la página de inicio de sesión después de cerrar la sesión
header('Location: InicioSesion.php');

// Detiene la ejecución del script para asegurarse de que no se ejecuten más líneas de código
exit();
?>
