<?php
$pageTitle = 'Contact - Hôpital Saint-Anténor';
require_once 'includes/config.php';

// Traitement du formulaire
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $subject = clean_input($_POST['subject'] ?? '');
    $msg = clean_input($_POST['message'] ?? '');

    // Validation
    if (empty($name) || strlen($name) < 2) {
        $errors[] = "Le nom doit contenir au moins 2 caractères.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Veuillez entrer une adresse email valide.";
    }

    if (empty($subject)) {
        $errors[] = "Veuillez entrer un sujet.";
    }

    if (empty($msg) || strlen($msg) < 10) {
        $errors[] = "Le message doit contenir au moins 10 caractères.";
    }

    // Enregistrement
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $msg]);

            $message = show_message("Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.", "success");
            $name = $email = $subject = $msg = '';
        } catch (PDOException $e) {
            $message = show_message("Erreur lors de l'envoi du message. Veuillez réessayer.", "danger");
        }
    } else {
        $message = show_message(implode("<br>", $errors), "danger");
    }
}

require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-3">Contactez-Nous</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Contact</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5 bg-light-gradient">
    <div class="container">
        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="card shadow-smooth h-100">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 text-gradient">Informations de Contact</h4>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <div class="card-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold mb-1">Adresse</h6>
                                <p class="text-muted mb-0">123 Rue de la Santé<br>Cap-Haïtien, Haïti</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <div class="card-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold mb-1">Téléphone</h6>
                                <p class="text-muted mb-0">+509 1234 5678<br>+509 8765 4321</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <div class="card-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="text-muted mb-0">contact@hopital-stantenor.ht<br>urgence@hopital-stantenor.ht</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="card-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold mb-1">Horaires</h6>
                                <p class="text-muted mb-0">Lun-Ven: 8h00 - 18h00<br>Urgence: 24h/24</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card shadow-smooth">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Envoyez-nous un Message</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php echo $message; ?>

                        <form method="POST" action="" data-validate novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?php echo htmlspecialchars($name ?? ''); ?>" required minlength="2">
                                    <div class="invalid-feedback">Veuillez entrer votre nom.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                    <div class="invalid-feedback">Veuillez entrer une email valide.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Sujet *</label>
                                <input type="text" class="form-control" id="subject" name="subject" 
                                       value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                                <div class="invalid-feedback">Veuillez entrer un sujet.</div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" 
                                          required minlength="10"><?php echo htmlspecialchars($msg ?? ''); ?></textarea>
                                <div class="invalid-feedback">Le message doit contenir au moins 10 caractères.</div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-send me-2"></i>Envoyer le Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-smooth">
                    <div class="card-body p-0">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1422937950147!2d-72.2196!3d19.7595!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTnCsDQ1JzM0LjIiTiA3MsKwMTMnMTAuNiJX!5e0!3m2!1sfr!2sht!4v1234567890"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade" class="rounded-xl">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
