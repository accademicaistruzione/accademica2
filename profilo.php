<?php
session_start();

// Verifica se l'utente è loggato
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    // Connessione al database
    $servername = "localhost";
    $username = "root";
    $password = "RobertaMazza05!";
    $dbname = "utente";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se la connessione al database è riuscita
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    } 

    // Ottieni il nome utente dalla sessione
    $username = $_SESSION["username"];

    // Query per recuperare l'email dell'utente dal database
    $sql = "SELECT email FROM persona WHERE nome_utente = '$username'";
    $result = $conn->query($sql);

    // Verifica se la query ha restituito dei risultati
    if ($result->num_rows > 0) {
        // Ottieni il risultato della query
        $row = $result->fetch_assoc();
        // Memorizza l'email dell'utente in una variabile
        $email = $row["email"];

        // Mostra il profilo dell'utente
        echo "<h1>Profilo Utente</h1>";
        echo "<p>Nome: $username</p>";
        echo "<p>Email: $email</p>";
    } else {
        echo "Errore nel recupero delle informazioni dell'utente.";
    }

    // Chiudi la connessione al database
    $conn->close();
} else {
    // L'utente non è loggato, reindirizza alla pagina di accesso
    header("Location: accesso.php");
    exit();
}
?>
