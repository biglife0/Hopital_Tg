<?php
$pageTitle = 'Confirmation de Rendez-vous - Hôpital Saint-Anténor';
require_once 'includes/config.php';

// Vérifier qu'une confirmation existe en session
if (!isset($_SESSION['confirmation'])) {
    header('Location: reservation.php');
    exit;
}

$conf = $_SESSION['confirmation'];

// Récupérer la réponse admin depuis la base (si déjà répondu)
$admin_response = null;
$apt_status     = 'en_attente';
try {
    $stmt = $pdo->prepare("SELECT status, admin_response, confirmation_date FROM appointments WHERE id = ?");
    $stmt->execute([$conf['id']]);
    $row = $stmt->fetch();
    if ($row) {
        $apt_status     = $row['status'];
        $admin_response = $row['admin_response'];
    }
} catch (PDOException $e) { /* silencieux */ }

$statuses_labels = [
    'en_attente' => ['label' => 'En attente de confirmation', 'color' => 'warning',  'icon' => 'fa-hourglass-half'],
    'confirme'   => ['label' => 'Confirmé',                   'color' => 'success',  'icon' => 'fa-check-circle'],
    'annule'     => ['label' => 'Annulé',                     'color' => 'danger',   'icon' => 'fa-times-circle'],
    'complete'   => ['label' => 'Complété',                   'color' => 'info',     'icon' => 'fa-check-double'],
];
$status_info = $statuses_labels[$apt_status] ?? $statuses_labels['en_attente'];

require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-3"><i class="fas fa-check-circle me-2"></i>Confirmation de Rendez-vous</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="reservation.php" class="text-white">Réservation</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Confirmation</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Confirmation Content -->
<section class="py-5 bg-light-gradient">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Carte de succès -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <span class="display-1 text-success"><i class="fas fa-calendar-check"></i></span>
                        </div>
                        <h2 class="fw-bold text-success mb-2">Demande enregistrée avec succès !</h2>
                        <p class="text-muted fs-5 mb-3">
                            Votre rendez-vous #<strong><?php echo $conf['id']; ?></strong> a bien été reçu.<br>
                            Notre équipe administrative va traiter votre demande dans les meilleurs délais.
                        </p>
                        <span class="badge bg-<?php echo $status_info['color']; ?> fs-6 px-3 py-2">
                            <i class="fas <?php echo $status_info['icon']; ?> me-2"></i>
                            <?php echo $status_info['label']; ?>
                        </span>
                    </div>
                </div>

                <!-- Récapitulatif du rendez-vous -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Récapitulatif de votre rendez-vous</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-user text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Nom du patient</div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($conf['full_name']); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-envelope text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Email</div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($conf['email']); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-phone text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Téléphone</div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($conf['phone']); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-stethoscope text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Spécialité</div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($conf['specialty']); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-calendar-alt text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Date du rendez-vous</div>
                                        <div class="fw-bold"><?php echo date('d/m/Y', strtotime($conf['appointment_date'])); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-clock text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Heure</div>
                                        <div class="fw-bold"><?php echo $conf['appointment_time']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($conf['message'])): ?>
                            <div class="col-12">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="fas fa-comment text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Votre message</div>
                                        <div class="fw-bold"><?php echo nl2br(htmlspecialchars($conf['message'])); ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Réponse de l'administrateur -->
                <div class="card shadow-sm border-0 mb-4" id="admin-response-card">
                    <div class="card-header bg-<?php echo $admin_response ? 'success' : 'secondary'; ?> text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-<?php echo $admin_response ? 'reply' : 'clock'; ?> me-2"></i>
                            Réponse de l'Administration
                        </h5>
                    </div>
                    <div class="card-body" id="admin-response-body">
                        <?php if ($admin_response): ?>
                            <div class="d-flex align-items-start">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3 flex-shrink-0">
                                    <i class="fas fa-user-md text-success fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-success mb-1">Message de l'équipe administrative :</div>
                                    <p class="mb-0 fs-6"><?php echo nl2br(htmlspecialchars($admin_response)); ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3 text-muted">
                                <i class="fas fa-hourglass-half fa-2x mb-2 text-warning"></i>
                                <p class="mb-0">Aucune réponse pour le moment. L'administration vous contactera bientôt.</p>
                                <small>Vous pouvez rafraîchir cette page pour voir les mises à jour.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="reservation.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Nouveau Rendez-vous
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-home me-2"></i>Retour à l'accueil
                    </a>
                    <button class="btn btn-outline-info btn-lg" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimer
                    </button>
                </div>

                <!-- Info supplémentaire -->
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Rappel :</strong> Conservez votre numéro de rendez-vous <strong>#<?php echo $conf['id']; ?></strong>.
                    En cas de besoin, contactez-nous au <strong>(+509) 4700-0000</strong> ou par email à <strong>info@hopital-saintantenor.ht</strong>.
                </div>

            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
