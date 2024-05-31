
<html>
<body>
<?php
// Avvia la sessione se non è già stata avviata
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se l'utente è autenticato
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['username'])) {
    // Mantieni la connessione ai database
    $servername_quiz = "localhost";
    $username_quiz = "root";
    $password_quiz = "RobertaMazza05!";
    $dbname_quiz = "quiz";

    $servername_utente = "localhost";
    $username_utente = "root";
    $password_utente = "RobertaMazza05!";
    $dbname_utente = "utente";

    // Connessione al database "utente"
    $conn_utente = new mysqli($servername_utente, $username_utente, $password_utente, $dbname_utente);

    // Verifica della connessione al database "utente"
    if ($conn_utente->connect_error) {
        die("Connessione al database utente fallita: " . $conn_utente->connect_error);
    }
    
    // Connessione al database "quiz"
    $conn_quiz = new mysqli($servername_quiz, $username_quiz, $password_quiz, $dbname_quiz);
    
    // Verifica della connessione al database "quiz"
    if ($conn_quiz->connect_error) {
        die("Connessione al database quiz fallita: " . $conn_quiz->connect_error);
    }

    // Query per ottenere il nome della materia presente nell'URL
    if(isset($_GET['id_materia']) && is_numeric($_GET['id_materia'])) {
        $id_materia = $_GET['id_materia'];
        $query_materia = "SELECT Nome_Materia FROM materie WHERE ID_Materia = ?";
        $stmt_materia = $conn_quiz->prepare($query_materia);
        $stmt_materia->bind_param("i", $id_materia);
        $stmt_materia->execute();
        $result_materia = $stmt_materia->get_result();
        
        // Verifica se ci sono risultati
        if ($result_materia->num_rows > 0) {
            $row_materia = $result_materia->fetch_assoc();
            echo "<div style='
            display: flex;
            justify-content: center;
            align-items: center;
        '>";
        echo "<h2 style='
            box-sizing: border-box;
            font-weight: bold; 
            font-size: 35px;
            font-weight: 600;
            line-height: 51px;
            font-family: \"Plus Jakarta Sans\", sans-serif; /* Gli apici singoli vanno scappati con \ */
            color: #c786e5;
            font-variant: tabular-nums;
        '>" . $row_materia['Nome_Materia'] . "</h2>";
        echo "</div>";
          } else {
            echo "Nessuna materia trovata per questo ID.";
        }
        $stmt_materia->close();
    } else {
        echo "ID Materia non valido.";
    }

    // Query per ottenere tutte le sezioni relative alla materia presente nell'URL
    if(isset($_GET['id_materia']) && is_numeric($_GET['id_materia'])) {
        $id_materia = $_GET['id_materia'];
        $query_sezioni = "SELECT sezioni.ID_Sezione, sezioni.Nome_Sezione, COUNT(quiz.domande.ID_Domanda) AS Numero_Domande
                          FROM sezioni 
                          LEFT JOIN domande ON sezioni.ID_Sezione = domande.ID_Sezione
                          WHERE sezioni.ID_Materia = ?
                          GROUP BY sezioni.ID_Sezione";
        $stmt_sezioni = $conn_quiz->prepare($query_sezioni);
        $stmt_sezioni->bind_param("i", $id_materia);
        $stmt_sezioni->execute();
        $result_sezioni = $stmt_sezioni->get_result();
        
        // Verifica se ci sono risultati
        if ($result_sezioni->num_rows > 0) {
            while($row_sezioni = $result_sezioni->fetch_assoc()) {
                $nome_sezione = $row_sezioni['Nome_Sezione'];
                $num_domande = $row_sezioni['Numero_Domande'];

                // Aggiunta della query per il numero di domande risposte dall'utente per ogni sezione
                $query_domande_risposte_utente = "SELECT COUNT(utente.risposte_utenti.ID_Domanda) AS Numero_Risposte
                                                   FROM utente.risposte_utenti
                                                   INNER JOIN quiz.domande ON utente.risposte_utenti.ID_Domanda = quiz.domande.ID_Domanda
                                                   WHERE quiz.domande.ID_Sezione = ?";
                $stmt_domande_risposte_utente = $conn_utente->prepare($query_domande_risposte_utente);
                $stmt_domande_risposte_utente->bind_param("i", $row_sezioni['ID_Sezione']);
                $stmt_domande_risposte_utente->execute();
                $result_domande_risposte_utente = $stmt_domande_risposte_utente->get_result();
                $num_risposte = 0;
                if ($result_domande_risposte_utente->num_rows > 0) {
                    $row_domande_risposte_utente = $result_domande_risposte_utente->fetch_assoc();
                    $num_risposte = $row_domande_risposte_utente['Numero_Risposte'];
                }
                
                echo "<span>";
                echo "<div class='showcase' style=''>";
                echo "<div class='explore-card-base'>";
                echo "<div class='explore-card'>";
                echo "<div class='top-base leetcodes-interview-crash-course-data-structures-and-algorithms'>";
                echo "<div class='top-inner-layer'>";
                echo "<div class='explore-heading'>";
                echo "<div class='title'>$nome_sezione</div>";
                echo "</div>"; // Chiudi explore-heading
                echo "</div>"; // Chiudi top-inner-layer
                echo "</div>"; // Chiudi top-base
                echo "<div class='bot-base'>";
                echo "<div class='start-button'>";
                echo "<a href='es.php?id_sezione={$row_sezioni['ID_Sezione']}'>"; // Aggiungi l'ID della sezione come parametro nell'URL
                echo "<div class='start-button-base'>";
                echo "<div class='progress-chart-wrapper'>";
                echo "</div>"; // Chiudi progress-chart-wrapper
                echo "<i class='icon fa fa-play' aria-hidden='true'></i>";
                echo "</div>"; // Chiudi start-button-base
                echo "</a>"; // Chiudi link
                echo "</div>"; // Chiudi start-button
                echo "<div class='card-stats'>";
                echo "<div class='stats col-xs-6'>";
                echo "<div class='big-number chapter'>$num_domande</div>";
                echo "<div class='text-label'>Domande</div>";
                echo "</div>"; // Chiudi stats col-xs-6
                echo "<div class='stats col-xs-6'>";
                echo "<div class='big-number item'>$num_risposte</div>";
                echo "<div class='text-label'>Risposte</div>";
                echo "</div>"; // Chiudi stats col-xs-6
                echo "<span>";
                echo "<div class='stats status complete-precentage'>0%</div>";
                echo "</span>"; // Chiudi span
                echo "</div>"; // Chiudi card-stats
                echo "</div>"; // Chiudi bot-base
                echo "</div>"; // Chiudi explore-card
                echo "</div>"; // Chiudi explore-card-base
                echo "</div>"; // Chiudi showcase
                echo "</span>";
                
                $stmt_domande_risposte_utente->close();
            }
        } else {
            echo "Nessuna sezione trovata per questa materia.";
        }
        $stmt_sezioni->close();
    }

    // Chiudi la connessione al database "quiz"
    $conn_quiz->close();
} else {
    // L'utente non è autenticato, mostra un messaggio di accesso limitato
    echo "<h1>Accesso Limitato</h1>";
    echo "<p>Per accedere alle sezioni, effettua il login.</p>";
}
?>


<style>

    
    span {
    display: inline-block; /* Imposta gli elementi span come blocchi in linea */
    vertical-align: center; /* Allinea verticalmente al centro rispetto al testo circostante */
    margin-left:135px;
    margin-right:-113px;
}

@font-face { 
  font-family:'FontAwesome';
  src:url('https://assets.leetcode.com/static_assets/public/font-awesome/fonts/fontawesome-webfont.eot?v=4.6.3');
  src:url('https://assets.leetcode.com/static_assets/public/font-awesome/fonts/fontawesome-webfont.eot?#iefix&v=4.6.3') format('embedded-opentype'),url('https://assets.leetcode.com/static_assets/public/font-awesome/fonts/fontawesome-webfont.woff2?v=4.6.3') format('woff2'),url('https://assets.leetcode.com/static_assets/public/font-awesome/fonts/fontawesome-webfont.woff?v=4.6.3') format('woff'),url('https://assets.leetcode.com/static_assets/public/font-awesome/fonts/fontawesome-webfont.ttf?v=4.6.3') format('truetype'),url('https://assets.leetcode.com/static_assets/public/font-awesome/fonts/fontawesome-webfont.svg?v=4.6.3#fontawesomeregular') format('svg');
  font-weight:normal;
  font-style:normal;
} 

* { 
    -webkit-box-sizing: border-box; 
    -moz-box-sizing: border-box; 
    box-sizing: border-box;
} 

body { 
    margin: 0; 
    color: rgba(0, 0, 0, 0.65); 
    font-size: 14px; 
    font-family: "Plus Jakarta Sans",sans-serif;
    font-variant: tabular-nums; 
    line-height: 1.5; 
    background-color: #fff; 
    -webkit-font-feature-settings: 'tnum'; 
    font-feature-settings: 'tnum';


.showcase  { 
    -webkit-box-flex: 0; 
    -ms-flex: 0 0 auto; 
    flex: 0 0 auto; 
    width: 280px; 
    margin: 0 10px;
} 

.showcase  { 
    margin: 0 5px;
} 

.showcase:last-child  { 
    width: 300px; 
    padding-right: 20px;
} 

.explore-card-base { 
    margin-top: 15px !important; 
    margin-bottom: 15px !important;
} 

.explore-card-base .explore-card  { 
    margin: auto; 
    max-width: 300px; 
    background: #ffffff; 
    -webkit-transition: 0.4s all; 
    -o-transition: 0.4s all; 
    transition: 0.4s all; 
    -webkit-box-shadow: inset 0 4px 7px 1px #ffffff, inset 0 -5px 20px rgba(173, 186, 204, 0.25), 0 2px 6px rgba(0, 21, 64, 0.14), 0 10px 20px rgba(0, 21, 64, 0.05); 
    box-shadow: inset 0 4px 7px 1px #ffffff, inset 0 -5px 20px rgba(173, 186, 204, 0.25), 0 2px 6px rgba(0, 21, 64, 0.14), 0 10px 20px rgba(0, 21, 64, 0.05); 
    border-radius: 15px;
} 

.explore-card-base .explore-card:hover { 
    -webkit-box-shadow: inset 0 4px 7px 1px #ffffff, inset 0 -5px 20px rgba(173, 186, 204, 0.25), 0 0 20px rgba(0, 21, 64, 0.14), 0 0 40px rgba(0, 0, 0, 0.2); 
    box-shadow: inset 0 4px 7px 1px #ffffff, inset 0 -5px 20px rgba(173, 186, 204, 0.25), 0 0 20px rgba(0, 21, 64, 0.14), 0 0 40px rgba(0, 0, 0, 0.2);
} 

.explore-card-base .explore-card .bot-base  { 
    height: 84px; 
    position: relative; 
    padding: 10px; 
    text-align: center; 
    z-index: 1;
} 

.explore-card-base .explore-card .top-base  { 
    height: 200px; 
    position: relative; 
    border-top-left-radius: 15px; 
    border-top-right-radius: 15px; 
    background: #c786e5; 
    background: -webkit-gradient(linear, left top, left bottom, from(#c786e5), to(#c786e5)); 
    background: -o-linear-gradient(#f2acf2, #c786e5); 
    background: linear-gradient(#f2acf2, #c786e5);
} 

.explore-card-base .explore-card .bot-base .card-stats  { 
    width: calc(100% - 85px);
} 

.explore-card-base .explore-card .top-base .top-inner-layer  { 
    -webkit-transition: 0.4s all; 
    -o-transition: 0.4s all; 
    transition: 0.4s all; 
    position: relative; 
    border-top-left-radius: 15px; 
    border-top-right-radius: 15px; 
    padding: 15px; 
    height: 100%;
} 

.col-xs-6 { 
    position: relative; 
    min-height: 1px; 
    padding-right: 15px; 
    padding-left: 15px;
} 

.col-xs-6 { 
    float: left;
} 

.col-xs-6 { 
    width: 50%;
} 

.title  { 
    color: #afafaf; 
    font-size: 16px; 
    font-weight: 500;
} 

.explore-card-base .explore-card .top-base .explore-heading .title  { 
    text-shadow: 0 0 20px rgba(0, 0, 0, 0.6); 
    line-height: 1.2; 
    margin-top: 7px; 
    font-size: 30px; 
    font-weight: 600; 
    color: white;
} 


.explore-card-base a  { 
    color: black;
} 


.explore-card-base a:hover { 
    color: #3fbbff;
} 

.explore-card-base .explore-card .bot-base .text-label  { 
    margin-top: -5px; 
    font-size: 12px; 
    color: grey;
} 

.explore-card-base .explore-card .bot-base .big-number  { 
    font-size: 26px; 
    font-weight: 500; 
    color: #222222;
} 

.explore-card-base .explore-card .bot-base .card-stats .stats.status  { 
    -webkit-transition: 0.4s all; 
    -o-transition: 0.4s all; 
    transition: 0.4s all; 
    position: absolute; 
    width: 70px; 
    text-align: center; 
    right: 15px; 
    font-size: 12px; 
    font-weight: 400; 
    top: 49px;
} 

.explore-card-base .explore-card .bot-base .card-stats .stats.status.complete-precentage  { 
    color: grey; 
    top: 42px; 
    font-size: 18px; 
    font-weight: 400;
} 

.explore-card-base .explore-card .start-button-base  { 
    cursor: pointer; 
    position: absolute; 
    top: -35px; 
    right: 15px; 
    height: 70px; 
    width: 70px; 
    border-radius: 1020px; 
    background: white; 
    -webkit-transition: 0.4s all; 
    -o-transition: 0.4s all; 
    transition: 0.4s all; 
    text-align: center; 
    font-size: 16px; 
    padding-top: 23px; 
    padding-left: 3px; 
    z-index: 2;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    display: flex; /* Utilizza il layout flexbox */
    justify-content: center; /* Allinea orizzontalmente al centro */
    } 


.fa { 
    margin-top: -20px!important;
    font: normal normal normal 14px/1 FontAwesome;
   color: #C887E6;
    line-height: 70px; /* Altezza uguale all'altezza del contenitore per centrare verticalmente */
}
.fa-play:before { 
    content: "\f04b";
} 

.progress-chart-wrapper { 
    position: relative;
} 




#style-YvLER.style-YvLER {  
   position: absolute;  
    top: -11px;  
    left: -6px;  
}  




}  
</style>
</body>
</html>