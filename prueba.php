<?php
// Datos de conexión a la base de datos
$host = 'localhost';
$dbname = 'testeo';
$user = 'postgres';
$password = '123456';

try {
    // Establecer la conexión utilizando PDO
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    
    // Establecer el modo de error para PDO a excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Si la conexión se establece correctamente, mostrar un mensaje de éxito
    echo "Conexión establecida correctamente a la base de datos PostgreSQL.";
} catch (PDOException $e) {
    // Si ocurre un error durante la conexión, mostrar el mensaje de error
    echo "Error en la conexión: " . $e->getMessage();
}
?>
