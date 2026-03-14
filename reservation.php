<?php
$pageTitle = 'Réservation - Hôpital Saint-Anténor';
require_once 'includes/config.php';

// Traitement du formulaire
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validation côté serveur
    $errors = [];

    $full_name = clean_input($_POST['full_name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $phone = clean_input($_POST['phone'] ?? '');
    $specialty_id = clean_input($_POST['specialty'] ?? '');
    $appointment_date = clean_input($_POST['appointment_date'] ?? '');
    $appointment_time = clean_input($_POST['appointment_time'] ?? '');
    $msg = clean_input($_POST['message'] ?? '');

    // Validation des champs
    if (empty($full_name) || strlen($full_name) < 3) {
        $errors[] = "Le nom complet doit contenir au moins 3 caractères.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Veuillez entrer une adresse email valide.";
    }

    if (empty($phone) || strlen($phone) < 8) {
        $errors[] = "Veuillez entrer un numéro de téléphone valide (8 chiffres minimum).";
    }

    if (empty($specialty_id)) {
        $errors[] = "Veuillez sélectionner une spécialité.";
    }

    if (empty($appointment_date)) {
        $errors[] = "Veuillez sélectionner une date.";
    } else {
        // Vérifier que la date n'est pas dans le passé
        $selected_date = strtotime($appointment_date);
        $today = strtotime(date('Y-m-d'));
        if ($selected_date < $today) {
            $errors[] = "La date du rendez-vous ne peut pas être dans le passé.";
        }
    }

    if (empty($appointment_time)) {
        $errors[] = "Veuillez sélectionner une heure.";
    }

    // Vérifier si le créneau est déjà pris
    if (empty($errors)) {
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments 
                                     WHERE appointment_date = ? 
                                     AND appointment_time = ? 
                                     AND specialty_id = ? 
                                     AND status != 'annule'");
        $check_stmt->execute([$appointment_date, $appointment_time, $specialty_id]);
        if ($check_stmt->fetchColumn() > 0) {
            $errors[] = "Ce créneau horaire est déjà réservé. Veuillez choisir un autre horaire.";
        }
    }

    // Si pas d'erreurs, enregistrer dans la base
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO appointments 
                (full_name, email, phone, specialty_id, appointment_date, appointment_time, message, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'en_attente')");

            $stmt->execute([
                $full_name, $email, $phone, $specialty_id, 
                $appointment_date, $appointment_time, $msg
            ]);

            $new_appointment_id = $pdo->lastInsertId();

            // Récupérer le nom de la spécialité pour la confirmation
            $spec_stmt = $pdo->prepare("SELECT name FROM specialties WHERE id = ?");
            $spec_stmt->execute([$specialty_id]);
            $specialty_name_confirm = $spec_stmt->fetchColumn();

            // Stocker les données dans la session pour la page de confirmation
            $_SESSION['confirmation'] = [
                'id'               => $new_appointment_id,
                'full_name'        => $full_name,
                'email'            => $email,
                'phone'            => $phone,
                'specialty'        => $specialty_name_confirm,
                'appointment_date' => $appointment_date,
                'appointment_time' => $appointment_time,
                'message'          => $msg,
            ];

            header('Location: confirmation.php');
            exit;

        } catch (PDOException $e) {
            $message = show_message("Erreur lors de l'enregistrement. Veuillez réessayer.", "danger");
        }
    } else {
        $message = show_message(implode("<br>", $errors), "danger");
    }
}

// Récupérer les spécialités pour le select
try {
    $stmt = $pdo->query("SELECT id, name FROM specialties ORDER BY name");
    $specialties = $stmt->fetchAll();
} catch (PDOException $e) {
    $specialties = [];
}

// Préselection depuis URL
$preselected_specialty = $_GET['specialty'] ?? '';

require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-3">Prendre Rendez-vous</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Réservation</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Reservation Form -->
<section class="py-5 bg-light-gradient">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <h3 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Formulaire de Rendez-vous</h3>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <?php echo $message; ?>

                        <form method="POST" action="" data-validate novalidate>
                            <div class="row">
                                <!-- Nom complet -->
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label">
                                        <i class="fas fa-user me-2 text-primary"></i>Nom et Prénom *
                                    </label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                           value="<?php echo htmlspecialchars($full_name ?? ''); ?>" 
                                           required minlength="3" placeholder="Ex: Jean Dupont">
                                    <div class="invalid-feedback">Veuillez entrer votre nom complet (min. 3 caractères).</div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2 text-primary"></i>Email *
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                                           required placeholder="exemple@email.com">
                                    <div class="invalid-feedback">Veuillez entrer une adresse email valide.</div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Téléphone -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone me-2 text-primary"></i>Téléphone *
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($phone ?? ''); ?>" 
                                           required minlength="8" placeholder="Ex: 12345678">
                                    <div class="invalid-feedback">Veuillez entrer un numéro valide (8 chiffres min).</div>
                                </div>

                                <!-- Spécialité -->
                                <div class="col-md-6 mb-3">
                                    <label for="specialty" class="form-label">
                                        <i class="fas fa-stethoscope me-2 text-primary"></i>Spécialité *
                                    </label>
                                    <select class="form-select" id="specialty" name="specialty" required>
                                        <option value="">Choisir une spécialité...</option>
                                        <?php foreach ($specialties as $spec): ?>
                                            <option value="<?php echo $spec['id']; ?>" 
                                                <?php echo ($preselected_specialty == $spec['id'] || ($specialty_id ?? '') == $spec['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($spec['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner une spécialité.</div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="appointment_date" class="form-label">
                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>Date du rendez-vous *
                                    </label>
                                    <input type="date" class="form-control" id="appointment_date" 
                                           name="appointment_date" min-today required
                                           value="<?php echo htmlspecialchars($appointment_date ?? ''); ?>">
                                    <div class="invalid-feedback">Veuillez sélectionner une date valide.</div>
                                </div>

                                <!-- Heure -->
                                <div class="col-md-6 mb-3">
                                    <label for="appointment_time" class="form-label">
                                        <i class="fas fa-clock me-2 text-primary"></i>Heure *
                                    </label>
                                    <select class="form-select" id="appointment_time" name="appointment_time" required>
                                        <option value="">Choisir une heure...</option>
                                        <option value="08:00" <?php echo ($appointment_time ?? '') == '08:00' ? 'selected' : ''; ?>>08:00</option>
                                        <option value="09:00" <?php echo ($appointment_time ?? '') == '09:00' ? 'selected' : ''; ?>>09:00</option>
                                        <option value="10:00" <?php echo ($appointment_time ?? '') == '10:00' ? 'selected' : ''; ?>>10:00</option>
                                        <option value="11:00" <?php echo ($appointment_time ?? '') == '11:00' ? 'selected' : ''; ?>>11:00</option>
                                        <option value="14:00" <?php echo ($appointment_time ?? '') == '14:00' ? 'selected' : ''; ?>>14:00</option>
                                        <option value="15:00" <?php echo ($appointment_time ?? '') == '15:00' ? 'selected' : ''; ?>>15:00</option>
                                        <option value="16:00" <?php echo ($appointment_time ?? '') == '16:00' ? 'selected' : ''; ?>>16:00</option>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner une heure.</div>
                                </div>
                            </div>

                            <!-- Message optionnel -->
                            <div class="mb-4">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comment me-2 text-primary"></i>Message (optionnel)
                                </label>
                                <textarea class="form-control" id="message" name="message" rows="3" 
                                          placeholder="Décrivez brièvement les raisons de votre consultation..."><?php echo htmlspecialchars($msg ?? ''); ?></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Confirmer la Réservation
                                </button>
                                <button type="reset" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-undo me-2"></i>Réinitialiser
                                </button>
                            </div>

                            <div class="mt-3 text-center text-muted">
                                <small><i class="fas fa-info-circle me-1"></i>Les champs marqués * sont obligatoires</small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
