<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accademica</title>
    <link href="//fonts.googleapis.com/css?family=Plus+Jakarta+Sans:600%2C700%7CRoboto:400&amp;display=swap" rel="stylesheet" property="stylesheet" media="all" type="text/css">
    <link rel="icon" href="logo.ico" type="image/x-icon">
    <style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #C786E5;
        }
        p {
            color: #666;
            line-height: 1.6;
            white-space: pre-wrap; /* Consente la visualizzazione di spazi e salti di riga */
        }
        /* Stilizzazione dei link */
        .links {
            display: flex; /* Rende i link disposti orizzontalmente */
            margin-top: 20px; /* Aggiunge spazio sopra i link */
        }
        .links a {
            color: #C786E5;
            text-decoration: none;
            margin-right: 20px; /* Aggiunge del margine tra i link */
        }
        .links a:last-child {
            margin-right: 0; /* Rimuove il margine dall'ultimo link */
        }
        /* Stile per il form di modifica */
        #modifica-form {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .text-area {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
            height: 300px; /* Altezza dell'area di testo */
        }
        .submit-btn {
            background-color: #C788E9; /* Cambia il colore di sfondo */
            color: white; /* Cambia il colore del testo */
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #ae6ec0; /* Cambia il colore di sfondo al passaggio del mouse */
        }
        /* Regole di formattazione Markdown */
        .bold {
            font-weight: bold;
        }
        .italic {
            font-style: italic;
        }
        .underline {
            text-decoration: underline;
        }
        .strike {
            text-decoration: line-through;
        }
        .highlight {
            background-color: #C786E5;
        }
    </style>

</head>
<body>
<div class="container">
<?php
session_start();

// Connessione al database
$conn = new mysqli('localhost', 'root', 'RobertaMazza05!', 'accademica');

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se è stata inviata una richiesta POST per salvare le modifiche
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i dati inviati dal form
    $lezione_id = mysqli_real_escape_string($conn, $_POST['lezione_id']);
    $titolo = mysqli_real_escape_string($conn, $_POST['titolo']);
    $testo = mysqli_real_escape_string($conn, $_POST['testo']);

    // Query per aggiornare i dati della lezione nel database
    $query = "UPDATE lezione SET titolo = '$titolo', testo = '$testo' WHERE id = '$lezione_id'";
    $result = $conn->query($query);

    if ($result) {
        echo "Modifiche salvate con successo.";
    } else {
        echo "Si è verificato un errore durante il salvataggio delle modifiche.";
    }
}

// Recupera l'ID della lezione dalla query string
$lezione_id = $_GET['id'];

// Query per recuperare i dati della lezione dal database
$query = "SELECT * FROM lezione WHERE id = '$lezione_id'";
$result = $conn->query($query);

// Verifica se ci sono risultati
    if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
   
    echo "<h2>" . htmlspecialchars($row['titolo']) . "</h2>";
    // Applica le regole di formattazione Markdown al testo della lezione
    $formatted_text = applyMarkdownFormatting($row['testo']);
    echo "<p id='testo'>" . nl2br($formatted_text) . "</p>";

    // Aggiungi i link "Homepage", "Tutte le lezioni" e "Random"
    echo "<div class='links'>";
    echo "<a href='index.php'>Homepage</a>";
    echo "<a href='tuttelematerie.php'>Tutte le lezioni</a>";
    // Recupera la materia della lezione corrente dal database
    $query_materia_corrente = "SELECT materia FROM lezione WHERE id = '$lezione_id'";
    $result_materia_corrente = $conn->query($query_materia_corrente);

    // Verifica se ci sono risultati
    if ($result_materia_corrente->num_rows > 0) {
        $row_materia_corrente = $result_materia_corrente->fetch_assoc();
        $materia_attuale = $row_materia_corrente['materia'];

        // Recupera l'ID della prossima lezione della stessa materia
        $query_next = "SELECT id FROM lezione WHERE id > '$lezione_id' AND materia = '$materia_attuale' ORDER BY id ASC LIMIT 1";
        $result_next = $conn->query($query_next);

        // Verifica se ci sono risultati per la prossima lezione
        if ($result_next->num_rows > 0) {
            $row_next = $result_next->fetch_assoc();
            $prossima_lezione_id = $row_next['id'];
            echo "<a href='lezione.php?id=$prossima_lezione_id'>Prossima lezione</a>";
        } else {
            echo "";
        }
    } else {
        echo "<span>Impossibile recuperare la materia della lezione corrente.</span>";
    }
    // Aggiungi il link alla "Lezione precedente"
    $query_prev = "SELECT id FROM lezione WHERE id < '$lezione_id' AND materia = '$materia_attuale' ORDER BY id DESC LIMIT 1";
    $result_prev = $conn->query($query_prev);
    if ($result_prev->num_rows > 0) {
        $row_prev = $result_prev->fetch_assoc();
        $lezione_precedente_id = $row_prev['id'];
        echo "<a href='lezione.php?id=$lezione_precedente_id'>Lezione precedente</a>";
    }

    echo "<a href='random.php'>Lezione random</a>";
    // Verifica se l'utente è autenticato e mostra il link "Modifica"
    if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true) {
        echo "<a href='salva_modifiche.php' id='modifica-link' style='color: #C786E5;'>Modifica</a>";
    }

    // Se l'utente non è autenticato, mostra il link "Accedi"
    if (!isset($_SESSION["is_admin"])) {
        echo "<a href='accedi.php' style='color: #C786E5;'>Accedi</a>";
    }

    echo "</div>"; // Chiudi il div dei link

    // Aggiungi il form di modifica
    echo "<div id='modifica-form' style='display: none;'>";
    echo "<form method='POST' action=''>";
    echo "<input type='hidden' name='lezione_id' value='$lezione_id'>";
    echo "<label for='titolo'>Titolo:</label><br>";
    echo "<input type='text' id='titolo' name='titolo' value='" . htmlspecialchars($row['titolo']) . "' class='input-field'><br>";
    echo "<label for='testo'>Testo:</label><br>";
    echo "<textarea id='testo' name='testo' class='text-area'>" . htmlspecialchars($row['testo']) . "</textarea><br>";
    echo "<input type='submit' value='Salva modifiche' class='submit-btn'>";
    echo "</form>";
    echo "</div>"; // Chiudi il div del form di modifica

} else {
    echo "Nessuna lezione trovata con l'ID specificato.";
}

// Chiudi la connessione al database
$conn->close();

// Funzione per applicare le regole di formattazione Markdown al testo
function applyMarkdownFormatting($text) {
    // Headers di livello 4
    $text = preg_replace('/^####\s(.*?)(?=\s####|$)/m', '<h4>$1</h4>', $text);
    // Headers di livello 3
    $text = preg_replace('/^###\s(.*?)(?=\s###|$)/m', '<h3>$1</h3>', $text);
    // Headers di livello 2
    $text = preg_replace('/^##\s(.*?)(?=\s##|$)/m', '<h2>$1</h2>', $text);
    // Evidenziato
    $text = preg_replace('/==(.*?)==/', '<span class="highlight">$1</span>', $text);
    // Sottolineato con colore #F2ACF2
    $text = preg_replace('/__(.*?)__/', '<span style="color: #F2ACF2; text-decoration: underline;">$1</span>', $text);
    // Barrato
    $text = preg_replace('/~~(.*?)~~/', '<span class="strike">$1</span>', $text);
    // Grassetto
    $text = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $text);
    // Blocco di codice racchiuso tra tre backticks
    $text = preg_replace('/```(.*?)```/s', '<pre><code>$1</code></pre>', $text);
    // Blocco di codice
    $text = preg_replace('/``([^`]*)``/s', '<pre><code>$1</code></pre>', $text);
    // Codice racchiuso tra backticks
    $text = preg_replace('/`([^`]*)`/', '<code>$1</code>', $text);
    // Trasformazione trattino in pallino
    $text = preg_replace('/^(-)(.*)/m', '<span class="bullet">&bull;</span>$2', $text);
    // Rimuove righe vuote in eccesso
    $text = preg_replace("/(\R{1,})\R+/", "$1", $text);
    //immagini
    $text = preg_replace('/!\[([^\]]+)\]\(([^)]+)\)/', '<img src="$2" alt="$1">', $text);
    //righe vuote
    $text = preg_replace("/(\R)(?![^\s])/", "", $text);
    // Rimuove le occorrenze di ![[Pasted image ...]]
    $text = preg_replace('/!\[\[Pasted image [^\]]+\]\]/', '', $text);

    //colore
    $text .= '<style>::selection {background-color: #C786E5;}</style>';
    return $text;
}
?>
<script>
    function cambioColoreSottolineato() {
        // Seleziona tutti gli elementi con il tag <u>
        var sottolineati = document.querySelectorAll('u');

        // Itera su tutti gli elementi sottolineati
        sottolineati.forEach(function(sottolineato) {
            // Imposta il colore del testo a #F2ACF2
            sottolineato.style.color = '#F2ACF2';
        });
    }

    // Richiama la funzione quando il documento è pronto
    document.addEventListener('DOMContentLoaded', cambioColoreSottolineato);
</script>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var modificaLink = document.getElementById("modifica-link");
        var modificaForm = document.getElementById("modifica-form");
        var testoInput = document.getElementById("testo");

        if (modificaLink && modificaForm) {
            modificaLink.addEventListener("click", function(event) {
                event.preventDefault();
                modificaForm.style.display = "block";
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        var loggedIn = <?php echo isset($_SESSION["is_admin"]) || isset($_SESSION["is_user"]) ? 'true' : 'false'; ?>;
        var testoElement = document.getElementById("testo");

        // Se l'utente è autenticato, abilita la selezione del testo
        if (loggedIn) {
            testoElement.style.userSelect = "text"; // Abilita la selezione del testo
        } else {
            testoElement.style.cursor = "not-allowed"; // Cambia il cursore quando non è autenticato
            testoElement.addEventListener("mousedown", function(event) {
                event.preventDefault(); // Previeni l'azione predefinita del clic
                alert("Devi accedere per copiare il testo."); // Mostra un messaggio
            });
        }
    });




</script>
</body>
</html>
