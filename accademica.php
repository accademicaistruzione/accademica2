<?php
$servername = "localhost";
$username = "root";
$password = "RobertaMazza05!";
$dbname = "accademica";

// Crea la connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Ottieni il nome della materia dalla query string
$nome_materia = isset($_GET['materia']) ? $_GET['materia'] : '';


if (empty($nome_materia)) {
    die("Nome materia non valido.");
}

// Query per ottenere i dati della materia
$query = "SELECT * FROM materia WHERE materia = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Errore nella preparazione della query: " . $conn->error);
}
$stmt->bind_param("s", $nome_materia);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se la materia esiste
if ($result->num_rows > 0) {
    $materia = $result->fetch_assoc();
} else {
    echo "Materia non trovata.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <!-- This site is optimized with the Yoast SEO Premium plugin v22.5 (Yoast SEO v22.5) - https://yoast.com/wordpress/plugins/seo/ -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,300;0,400;0,500;0,700;1,700&amp;display=swap" media="all" onload="this.media='all'">
    <link data-minify="1" rel="stylesheet" id="advanced_admin_search_style-css" href="https://www.powerschool.com/wp-content/cache/min/1/wp-content/plugins/advanced-admin-search/css/style.css?ver=1713883574" media="all">
    <link data-minify="1" rel="stylesheet" id="base-bootstrap-css" href="https://www.powerschool.com/wp-content/cache/min/1/wp-content/themes/powerschool/css/bootstrap.css?ver=1713883574" media="all">
    <link data-minify="1" rel="stylesheet" id="base-style-css" href="https://www.powerschool.com/wp-content/cache/min/1/wp-content/themes/powerschool/style.css?ver=1713883574" media="all">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="//fonts.googleapis.com/css?family=Plus+Jakarta+Sans:600%2C700%7CRoboto:400&amp;display=swap" rel="stylesheet" property="stylesheet" media="all" type="text/css">
    
   <!-- cambiamenti css a codice gia pronto -->
<style>
.btn.btn-danger {
    background-color: #c786e5 !important;
    color: #ffffff !important;
    border-color: #ffffff !important; /* Sovrappone il contorno con lo stesso colore */
    box-shadow: none !important; /* Rimuove eventuali ombre */
    outline: none !important; /* Rimuove eventuali contorni di focus */
}
h2.text-primary {
    color: #5c0d80 !important;
}
h3.text-primary {
    color: #c786e5 !important;
}
strong {
    color: #5c0d80 !important;
}

.circle {
    stroke: #6a0dad; /* Colore del contorno */
    stroke-width: 3px; /* Larghezza del contorno */
}
            
</style>
<?php
// Ottieni il nome della materia dalla variabile $materia
    $nome_materia = isset($materia['materia']) ? $materia['materia'] : 'Materia non specificata';

    // Aggiungi il nome della materia al titolo della pagina
    echo '<title>' . htmlspecialchars($nome_materia) . ' - Accademica</title>';
    ?>
    <link rel="icon" href="logo.ico" type="image/x-icon">
  </head>
  <body class="page-template page-template-templates page-template-acf-modules page-template-templatesacf-modules-php page page-id-19353 page-child parent-pageid-19351">
    <div id="consent_blackbar"></div>
    <div id="wrapper">
      <div id="dropdownBackdrop" class="fade" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"></div>
        <div class="bg-white hero-section in-viewport">
          <div class="container pt-0_2">
            <div class="row">
              <div class="col-md-6">
                <nav data-anim="fade-up" style="animation-delay: 0.1s;" aria-label="breadcrumb">
                  <ol class="breadcrumb font-weight-bold mb-1_5 mb-md-3" typeof="BreadcrumbList" vocab="https://schema.org/">
                    <li class="home">
                      <span property="itemListElement" typeof="ListItem">
                        <a property="item" typeof="WebPage" title="Vai ad Accademica" href="index.php" class="home">
                        <span property="name" style="color: #c786e5;">Accademica</span>

                        </a>
                        <meta property="position" content="1">
                      </span>
                    </li>
                    <li class="post post-page">
                      <span property="itemListElement" typeof="ListItem">
                        <a property="item" typeof="WebPage" title="Tutte le materie" href="tuttelematerie.php" class="tuttelematerie.php">
                        <span property="name" style="color:  #c786e5;">Tutte le materie</span>
                        </a>
                        <meta property="position" content="2">
                      </span>
                    </li>
                                <li class="post post-page current-item">
                                    <span style="color: #5c0d80;"><?php echo htmlspecialchars($materia['materia']); ?></span>
                                </li>
                            </ol>
                        </nav>
                        <h3 class="text-primary mb-1_75"><?php echo htmlspecialchars($materia['materia']); ?></h3>
                        <p class="lead mb-2"><?php echo htmlspecialchars($materia['descrizione']); ?></p>
                        <a href="index.php" class="btn btn-danger mt-1_75">Ritorna nella homepage</a>
                    </div>
                    <div class="col-md-6">
                        <div class="hero-visual hero-visual-type-3">
                            <svg class="circle fill-custom" viewBox="0 0 146 146">
                                <circle cx="73" cy="73" r="73" fill="#e5e3f9"></circle>
                            </svg>
                            <img width="411" height="403" src="<?php echo htmlspecialchars($materia['immagine']); ?>" alt="<?php echo htmlspecialchars($materia['materia']); ?>" class="attachment-411x403 size-411x403 entered lazyloaded">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Inizio lezioni -->
        <div class="container">
    <input type="text" class="INPUT-42yfp6if8i" id="searchInput" placeholder="Cerca lezione">
</div>
<div class="row lesson-row">
    <?php
    // Connessione al database
    $conn = new mysqli('localhost', 'root', 'RobertaMazza05!', 'accademica');

    // Verifica della connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

        // Ottieni il nome della materia dalla variabile $materia
        $nome_materia = $materia['materia'];

        // Query per ottenere le lezioni della materia corrente
        $query = "SELECT * FROM lezione WHERE materia = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nome_materia);
        $stmt->execute();
        $result = $stmt->get_result();
    // Contatore per tenere traccia del terzo elemento in ogni riga
    $count = 0;

    // Verifica se ci sono risultati
    if ($result->num_rows > 0) {
        // Creazione dei banner delle lezioni
        while ($row = $result->fetch_assoc()) {
            $link = "lezione.php?id=" . $row['id'];
            // Aggiungi una classe aggiuntiva al terzo banner di ogni riga
            $additionalClass = ($count % 3 == 2 && $count != 0) ? 'terzo-banner' : '';
            echo '
            <div class="px-1 col-md-4 mb-4 ' . $additionalClass . '"> <!-- Modifica la larghezza della colonna su dispositivi medi -->
                <div class="card shadow w-100">
                    <div class="card-body py-3 py-md-2"> 
                        <strong class="small font-weight-bold d-block mb-1 text-uppercase">Lezione</strong>
                        <h3 class="h5 pt-0_2 mb-1_5 text-primary">
                            <a class="text-current" href="' . $link . '">' . $row['titolo'] . '</a>
                        </h3>
                        <p class="mb-0">' . $row['descrizione'] . '</p>
                    </div>
                    <a href="' . $link . '" class="btn btn-danger py-1 text-left link">Studia</a>
                </div>
            </div>';
            $count++;
        }
    } else {
        echo "Arriveranno presto nuove lezioni!";
    }

    // Chiudi la connessione al database
    $conn->close();
    ?>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var input = document.getElementById('searchInput');
    var cards = document.querySelectorAll('.lesson-row .card');

    input.addEventListener('input', function() {
        var searchText = input.value.trim().toLowerCase();

        cards.forEach(function(card) {
            var title = card.querySelector('h3').textContent.toLowerCase();
            var description = card.querySelector('p').textContent.toLowerCase();

            if (title.includes(searchText) || description.includes(searchText)) {
                card.closest('.col-md-4').style.display = 'block';
            } else {
                card.closest('.col-md-4').style.display = 'none';
            }
        });
    });
});
</script>
<style>
.INPUT-42yfp6if8i {
  box-sizing: border-box;
  background: none 0% 0% / auto repeat scroll padding-box border-box rgba(0, 0, 0, 0);
  border-radius: 4px;
  color: #000; /* Modificato il colore del testo a nero */
  font-family: "font-family: "Plus Jakarta Sans",sans-serif;";
  font-size: 16px;
  height: 40px;
  padding: 0px 38px;
  width: 1278px;
  z-index: 1;
  outline: none;
  cursor: text;
  font-weight: 400;
  line-height: normal;
  border: 1px solid #dce1e6; /* Modificato il colore del bordo */
  margin-left: -2px;
}




</style>
        <style>
.card {
  width: 450px!important; /* Imposta la larghezza del banner */
    height: 350px!important;
    margin-left: 20px!important; /* Margine sinistro tra i banner */
    margin-right: 0px!important; /* Margine destro tra i banner */
    margin-top: 25px!important;
 
}

.lesson-row {
    transform: scale(0.9);
    transform-origin: top left;
    margin-left: 200px;
}
</style>

</body>
</html>
