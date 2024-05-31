<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $materia = $_POST['materia'] ?? '';
    $argomento = $_POST['argomento'] ?? '';
    $dettagli = $_POST['dettagli'] ?? '';

    // Verifica se il campo "Argomento richiesto" è vuoto
    if (empty($argomento)) {
        // Se è vuoto, mostra un messaggio all'utente e interrompi l'elaborazione
        echo "Il campo Argomento richiesto non può essere vuoto.";
        exit;
    }

    // Formatta i dati del modulo
    $data = "Materia richiesta: $materia\nArgomento richiesto: $argomento\nDettagli: $dettagli\n\n";

    // Scrivi i dati nel file richiesta.txt
    if (file_put_contents('richiesta.txt', $data, FILE_APPEND | LOCK_EX) !== false) {
        // Se la registrazione è avvenuta con successo, stampa un messaggio di avviso
        echo "Richiesta effettuata con successo.";
    } else {
        // Se si verifica un errore durante la registrazione, mostra un messaggio di errore
        echo "Si è verificato un errore durante la registrazione della richiesta.";
    }
}
?>
