<?php
include '../includes/public_header.php';
?>
    <style>
        .form-control {
            background-color: #495057; /* Lichtere donkere achtergrondkleur voor invoervelden */
            color: #ffffff; /* Tekstkleur in invoervelden */
            border: 1px solid #ced4da; /* Randkleur */
        }
        .form-control:focus {
            background-color: #6c757d; /* Iets lichtere achtergrondkleur bij focus */
            color: #ffffff; /* Tekstkleur bij focus */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Contact</h2>
        <form action="contact.php" method="post">
            <div class="form-group">
                <label for="name">Naam</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Telefoonnummer (optioneel)</label>
                <input type="tel" class="form-control" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="message">Bericht</label>
                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Stuur bericht</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haal de formuliergegevens op
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Controleer of verplichte velden zijn ingevuld
    if (!empty($name) && !empty($email) && !empty($message)) {
        // E-mailadres waar het bericht naartoe gestuurd moet worden
        $to = "134denny@gmail.com";
        $subject = "Nieuw bericht van $name";

        // Maak de email body
        $body = "Naam: $name\n";
        $body .= "Email: $email\n";
        if (!empty($phone)) {
            $body .= "Telefoonnummer: $phone\n";
        }
        $body .= "Bericht:\n$message\n";

        // Stuur de email
        if (mail($to, $subject, $body)) {
            echo "Bericht verzonden! Bedankt voor het contact opnemen.";
        } else {
            echo "Er is een fout opgetreden bij het verzenden van het bericht. Probeer het later opnieuw.";
        }
    } else {
        echo "Vul alle verplichte velden in.";
    }
}
?>
