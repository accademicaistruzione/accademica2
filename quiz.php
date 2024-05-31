<?php
session_start();

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esercitazioni e Verifiche</title>
    <link href="//fonts.googleapis.com/css?family=Plus+Jakarta+Sans:600%2C700%7CRoboto:400&amp;display=swap" rel="stylesheet" property="stylesheet" media="all" type="text/css">
    <style>
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FFFFFF; /* Cambio il colore di sfondo */
            color: #C786E5; /* Cambio il colore del testo */
            margin: 0;
            padding: 0;
        }
   
        .row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .subject {
            background-color: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 250px;
            transition: transform 0.3s ease-in-out;
        }
        .subject:hover {
            transform: scale(1.05);
        }
        .subject h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .subject div {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .subject a {
            background-color: #C786E5;
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .subject a:hover {
            background-color: #F2ACF2;
        }

        /* Stile per il titolo */
        .container h1 {
            margin-bottom: 10px;
        }

        /* Stile per il paragrafo */
        .container p {
            margin-bottom: 0;
            align-items: center;
        }

/* Stile per il contenitore *//* Stile per il contenitore */
.container {
            height: 350px;
            width: auto;
            background-color: #c786e5;
            color: white;
            padding: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }


    </style>
</head>
<body>
    <div class="container">
        <h1>Benvenuto sulla sezione esercizi di Accademica</h1>
        <p>Mettiti alla prova!</p>
      </div>
</body>
</html>



    <div class="row">
        
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "RobertaMazza05!";
        $dbname = "quiz";

        // Connessione al database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica della connessione
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        // Query per recuperare le materie
        $sql_materie = "SELECT * FROM materie";
        $result_materie = $conn->query($sql_materie);

        if ($result_materie->num_rows > 0) {
            // Output delle materie
            while($row_materie = $result_materie->fetch_assoc()) {
                echo '<div class="subject">';
                echo '<h2>' . $row_materie["Nome_Materia"] . '</h2>';
                echo '<div>' . $row_materie["Descrizione"] . '</div>';
                
                if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                    // Genera il link alla pagina delle sezioni con l'ID della materia come parametro
                    echo '<a href="sezioni.php?id_materia=' . $row_materie["ID_Materia"] . '" class="prova-button">Esercitati</a>';
                } else {
                    // Mostra il messaggio se l'utente non è autenticato
                    echo '<div>Devi accedere per esercitarti</div>';
                }
                
                echo '</div>';
            }
        } else {
            echo "Nessuna materia trovata.";
        }

        // Chiusura della connessione
        $conn->close();
    ?>
    </div>

    <!-- Script JavaScript non più necessario per il reindirizzamento -->
</body>
</html>
