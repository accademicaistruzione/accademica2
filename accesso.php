<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "RobertaMazza05!";
$dbname = "utente";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
} 

// Verifica se il modulo è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ottieni i valori inseriti dall'utente
    $username = $_POST["nom"];
    $email = $_POST["email"];
    $password = $_POST["password"];
}

// Query per verificare le credenziali nel database
$sql = "SELECT id, nome_utente, hashed_password FROM persona WHERE nome_utente = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se esiste una riga nel risultato della query
if ($result->num_rows > 0) {
    // Ottieni i dettagli dell'utente
    $row = $result->fetch_assoc();

    // Verifica se la password inviata corrisponde alla password memorizzata nel database
    if (password_verify($password, $row['hashed_password'])) {
        $is_admin = false;

        // Verifica se l'utente è un amministratore
        if ($row['nome_utente'] === 'Accademica' && $row['email'] === 'accademicaistruzione@gmail.com' && $password === 'accademica') {
            $is_admin = true;
        }

        // Avvia la sessione e memorizza le informazioni sull'utente
        $_SESSION["logged_in"] = true;
        $_SESSION["username"] = $username;
        $_SESSION["id_utente"] = $row['id']; // Memorizza l'ID utente
        $_SESSION["is_admin"] = $is_admin; // Memorizza il flag di amministratore

        // Redirect alla pagina di index.php o a qualsiasi altra pagina desiderata
        header("Location: index.php");
        exit();
    } else {
        // Password non corretta
        echo "Password errata.";
    }
} else {
    // Credenziali non corrispondono
    echo "L'account non esiste. Registrati.";
}

$stmt->close();
$conn->close();
?>
