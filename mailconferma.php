<?php
$servername = "localhost";
$username = "root";
$password = "RobertaMazza05!";
$dbname = "utente";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Ottieni il token dalla query string
$token = $_GET['token'];

// Debug del token
echo "Token dalla query string: " . $token;

// Inizializza una variabile per memorizzare il messaggio di stato
$message = '';

// Se il token è presente
if (!empty($token)) {
    // Ottieni l'id della persona associata al token
    $stmt = $conn->prepare("SELECT id_persona FROM mailconfirm WHERE code = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        // Ottieni l'id della persona
        $row = $result->fetch_assoc();
        $id_persona = $row['id_persona'];
        
        // Imposta il campo 'verified' a 1 nella tabella persona
        $stmt_update = $conn->prepare("UPDATE persona SET verified = 1 WHERE id = ?");
        $stmt_update->bind_param("i", $id_persona);
        $stmt_update->execute();
        
        // Cancella l'entrata nella tabella mailconfirm
        $stmt_delete = $conn->prepare("DELETE FROM mailconfirm WHERE id_persona = ?");
        $stmt_delete->bind_param("i", $id_persona);
        $stmt_delete->execute();
        
        $message = "Account confermato con successo!";
    } else {
        $message = "Token non valido.";
    }
} else {
    $message = "Token non presente nella query string.";
}

// Chiudi le query
$stmt->close();
$stmt_update->close();
$stmt_delete->close();

// Chiudi la connessione al database
$conn->close();

// Stampare il messaggio di stato
echo $message;
?>
