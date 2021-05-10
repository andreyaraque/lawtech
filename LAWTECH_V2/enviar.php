<?php

    $destino = "andreyaraque@gmail.com"; 
    $nombre = $_POST ["nombre"];
    $telefono = $_POST ["telefono"];
    $correo = $_POST ["correo"];
    $mensaje = $_POST ["mensaje"];
    $contenido = "Nombre: " . $nombre . "\nCorreo: " . $correo . "\nTelefono: " . $telefono . "\nMensaje: " . $mensaje;
    mail($destino,"Contacto pagina web", $contenido); 
    header("location:index.html");
    
?>