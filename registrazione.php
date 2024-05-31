<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$servername = "localhost";
$username = "root";
$password = "RobertaMazza05!";
$dbname = "utente";

$conn = new mysqli($servername, $username, $password, $dbname);
require __DIR__ . '/vendor/autoload.php';


if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
} 

function verificaLunghezzaPassword($password) {
    return strlen($password) >= 8;
}

function verificaUnicitaEmail($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT email FROM persona WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows === 0; // Se il risultato è vuoto, l'email non esiste già nel database
}

function verificaUnicitaNomeUtente($nome_utente) {
    global $conn;
    $stmt = $conn->prepare("SELECT nome_utente FROM persona WHERE nome_utente = ?");
    $stmt->bind_param("s", $nome_utente);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows === 0; // Se il risultato è vuoto, il nome utente non esiste già nel database
}

// funzione che prende una mail come parametro e ritorna l'id dell'utente con la mail associata
function getUserIdByEmail($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM persona WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc()['id'];
}

function generateRandomPassword() {
    $password = '';
    for ($i = 0; $i < 9; $i++) {
        $password .= rand(0, 9);
    }
    return $password;
}


$nome = $_POST['prenom'];
$nome_utente = $_POST['nom'];
$email = $_POST['email'];
$password = $_POST['password'];

if (!empty($nome) && !empty($nome_utente) && !empty($email) && !empty($password) && verificaLunghezzaPassword($password)) {
    if (verificaUnicitaEmail($email) && verificaUnicitaNomeUtente($nome_utente)) {
        // Hashing della password per maggiore sicurezza
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO persona (nome, nome_utente, email, hashed_password, verified) VALUES (?, ?, ?, ?, ?)");
        $verified = 0; // Store the value in a variable
        $stmt->bind_param("ssssi", $nome, $nome_utente, $email, $hashed_password, $verified); // Pass the variable by reference
        
        if ($stmt->execute()) {
            // Genera un token casuale
            $randomToken = generateRandomPassword(); // è utilizzata per generare il token
            
            // Costruisci il link di verifica
            $verificationLink = "http://localhost/mailconferma.php?token=$randomToken";

            

            // Invia l'email di conferma con PHPMailer
            inviaEmailConferma($email, $verificationLink);
            
            // Otteniamo l'ID dell'utente appena registrato
            $id_persona = getUserIdByEmail($email);
            
            // Inseriamo l'ID dell'utente e il token di conferma nella tabella mailconfirm
            $stmt_confirm = $conn->prepare("INSERT INTO mailconfirm (id_persona, code) VALUES (?, ?)");
            $stmt_confirm->bind_param("is", $id_persona, $randomToken);
            $stmt_confirm->execute();
            $stmt_confirm->close();
            
            echo "<script>alert('Registrazione quasi completata. Controlla la tua email per confermare il tuo account.');</script>";
            exit();
        } else {
            echo "<script>alert('Errore durante la registrazione.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Email o nome utente già in uso.');</script>";
    }
} else {
    echo "<script>alert('Errore durante la registrazione: assicurati di compilare tutti i campi e che la password sia lunga almeno 8 caratteri.');</script>";
}


$conn->close();
?>

<?php
// Funzione per inviare l'email di conferma con PHPMailer
function inviaEmailConferma($emailDestinatario, $verificationLink) {
    // Includi la libreria PHPMailer
    require 'Exception.php';
    require 'PHPMailer.php';
    require 'SMTP.php';

    // Crea una nuova istanza di PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configura il server SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Indirizzo del server SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'accademicaistruzione@gmail.com'; // Nome utente SMTP
        $mail->Password = 'wtqandbqtpwlcisg'; // Password SMTP
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Mittente e destinatario dell'email
        $mail->setFrom('accademicaistruzione@gmail.com', 'Accademica');
        $mail->addAddress($emailDestinatario);

        // Contenuto dell'email
        $mail->isHTML(true);
        $mail->Subject = 'Conferma registrazione account';
        $mail->Body = "Per favore, clicca sul seguente link per confermare il tuo account: <a href='$verificationLink'>$verificationLink</a>";

        // Invia l'email
        $mail->send();
        echo 'Email di conferma inviata con successo!';
    } catch (Exception $e) {
        // Gestione degli errori in caso di fallimento dell'invio dell'email
        echo "Errore nell'invio dell'email di conferma: {$mail->ErrorInfo}";
    }
}
?>