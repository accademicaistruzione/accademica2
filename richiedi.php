<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Richiedi una lezione</title>
    <link href="//fonts.googleapis.com/css?family=Plus+Jakarta+Sans:600%2C700%7CRoboto:400&amp;display=swap" rel="stylesheet" property="stylesheet" media="all" type="text/css">
    <link rel="icon" href="logo.ico" type="image/x-icon">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #F2ACF2;
            background-color: #FFF; /* Colore di sfondo */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Altezza minima della finestra visualizzata */
            position: relative; /* Per consentire il posizionamento assoluto dei pseudo-elementi */
        }

        .container {
            display: flex;
            flex-direction: column; /* Stack gli elementi in colonne */
            align-items: center;
            max-width: 1060px; /* Massima larghezza del contenitore */
            padding: 20px;
            position: relative; /* Per consentire il posizionamento assoluto dei pseudo-elementi */
        }

        .section {
            text-align: center;
            margin-bottom: 0px;
        }

        h1 {
            font-weight: 700;
            font-size: 2.5em; /* Aumentato leggermente la dimensione del testo */
            margin-bottom: 20px;
            color: #F2ACF2; /* Colore del testo */
        }

        p {
            font-size: 1.1em;
            line-height: 1.6; /* Leggermente aumentato l'interlinea */
            margin-bottom: 20px; /* Ridotto lo spazio tra il paragrafo e il form */
            color: #000; /* Colore del testo in nero */
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            text-align: left;
            color: #F2ACF2; /* Colore del testo */
        }

        input[type="text"],
        textarea {
            width: calc(100% - 22px); /* Larghezza del 100% meno il bordo */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #F2ACF2;
            border-radius: 5px;
            background-color: transparent;
            color: #F2ACF2; /* Colore del testo */
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        textarea {
            height: 80px;
        }

        button {
            padding: 10px 20px;
            background-color: #F2ACF2;
            border: none;
            border-radius: 5px;
            color: #FFF;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #D28FD2;
        }

        .top-right-shape::before {
    content: '';
    position: fixed;
    top: 0;
    right: 0;
    width: 400px; /* Ingrossa l'immagine di tre volte rispetto all'originale */
    height: 100vh; /* Altezza uguale all'altezza della finestra */
    background-image: url('forma.png');
    background-size: cover; /* Fai sì che l'immagine si adatti completamente al contenitore */
    background-repeat: no-repeat; /* Assicurati che l'immagine non venga ripetuta */
    z-index: -1; /* Assicurati che l'immagine sia posizionata dietro agli altri contenuti */
}

.bottom-left-shape::after {
    content: '';
    position: fixed;
    bottom: -100px; /* Sposta l'immagine più in basso di 100px rispetto al bordo inferiore dello schermo */
    left: 0;
    width: 600px; /* Ingrossa l'immagine di tre volte rispetto all'originale */
    height: 600px; /* Altezza uguale alla larghezza per mantenere la forma quadrata */
    background-image: url('forma.png');
    background-size: cover; /* Fai sì che l'immagine si adatti completamente al contenitore */
    background-repeat: no-repeat; /* Assicurati che l'immagine non venga ripetuta */
    background-position: center bottom; /* Posiziona l'immagine centrata in basso */
    transform: rotate(180deg); /* Ruota l'immagine a destra di due volte */
    z-index: -1; /* Assicurati che l'immagine sia posizionata dietro agli altri contenuti */
}




    </style>
</head>
<body>
    <div class="container">
        <div class="section">
            <h1>Richiedi una lezione</h1>
            <p>In questa sezione puoi richiedere qualsiasi argomento desideri. </p>
            <p>Compila il modulo qui sotto per iniziare.</p>
        </div>
        <div class="section">
            <form action="richiesta.php" method="post">
                <label for="materia">Materia richiesta:</label>
                <input type="text" id="materia" name="materia" required>

                <label for="argomento">Argomento richiesto:</label>
                <input type="text" id="argomento" name="argomento" required>

                <label for="dettagli">Dettagli che vuoi siano trattati:</label>
                <textarea id="dettagli" name="dettagli" rows="4" required></textarea>

                <button type="submit">Invia richiesta</button>
            </form>
        </div>
    </div>
    <!-- Forma in alto a destra -->
    <div class="top-right-shape"></div>
    <!-- Forma in basso a sinistra -->
    <div class="bottom-left-shape"></div>
</body>
</html>
