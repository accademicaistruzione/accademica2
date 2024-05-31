<?php
// Includi il file di connessione al database o inserisci qui la logica per la connessione
$servername = "localhost";
$username = "root";
$password = "RobertaMazza05!";
$dbname = "accademica";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
} 

// Query per recuperare gli ID delle lezioni dal database
$sql = "SELECT id FROM lezione";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Genera un array per memorizzare gli ID delle lezioni disponibili
    $lesson_ids = array();

    // Popola l'array degli ID delle lezioni
    while ($row = $result->fetch_assoc()) {
        $lesson_ids[] = $row['id'];
    }

    // Seleziona casualmente un ID di lezione dall'array
    $random_lesson_id = $lesson_ids[array_rand($lesson_ids)];

    // Reindirizza l'utente a quella lezione
    header("Location: lezione.php?id=$random_lesson_id");
    exit();
} else {
    echo "Nessuna lezione disponibile.";
}
?>
