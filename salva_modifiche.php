<?php
session_start();

// Connessione al database
$conn = new mysqli('localhost', 'root', 'RobertaMazza05!', 'accademica');

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Recupera i dati inviati dal form
$id = $_POST['id'];
$titolo = $_POST['titolo'];
$testo = $_POST['testo'];

// Funzione per pulire il testo da caratteri HTML non consentiti
function pulisciTesto($testo) {
    return htmlspecialchars($testo);
}

// Query per aggiornare i dati della lezione nel database
$query = "UPDATE lezione SET titolo = '$titolo', testo = '$testo' WHERE id = '$id'";
$result = $conn->query($query);

if ($result) {
    echo "Modifiche salvate con successo.";
} else {
    echo "Si è verificato un errore durante il salvataggio delle modifiche.";
}

// Chiudi la connessione al database
$conn->close();
?>
