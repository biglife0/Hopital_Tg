<?php
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = 'Gestion des Rendez-vous';

// Traitement des actions
$message = '';

// Suppression
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = show_message("Rendez-vous supprimé avec succès.", "success");
    } catch (PDOException $e) {
        $message = show_message("Erreur lors de la suppression.", "danger");
    }
}

// Changement de statut (confirmation/annulation)
if (isset($_GET['status']) && isset($_GET['id'])) {
    $new_status = $_GET['status'];
    $id = $_GET['id'];

    $valid_statuses = ['en_attente', 'confirme', 'annule', 'complete'];

    if (in_array($new_status, $valid_statuses)) {
        try {
            if ($new_status == 'confirme') {
                // Mettre à jour avec date de confirmation
                $stmt = $pdo->prepare("UPDATE appointments SET status = ?, confirmation_date = NOW() WHERE id = ?");
            } else {
                $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            }
            $stmt->execute([$new_status, $id]);
            $message = show_message("Statut du rendez-vous mis à jour avec succès.", "success");
        } catch (PDOException $e) {
            $message = show_message("Erreur lors de la mise à jour.", "danger");
        }
    }
}

// Enregistrement de la réponse admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_response_id'])) {
    $resp_id   = (int) $_POST['admin_response_id'];
    $resp_text = trim($_POST['admin_response_text'] ?? '');
    if ($resp_id > 0 && $resp_text !== '') {
        try {
            $stmt = $pdo->prepare("UPDATE appointments SET admin_response = ? WHERE id = ?");
            $stmt->execute([$resp_text, $resp_id]);
            $message = show_message("Réponse envoyée au patient avec succès.", "success");
        } catch (PDOException $e) {
            $message = show_message("Erreur lors de l'envoi de la réponse.", "danger");
        }
    } else {
        $message = show_message("Le message de réponse ne peut pas être vide.", "warning");
    }
}

// Paramètres de recherche et filtrage
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status_filter'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Construction de la requête
$sql = "SELECT a.*, s.name as specialty_name FROM appointments a 
        JOIN specialties s ON a.specialty_id = s.id WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (a.full_name LIKE ? OR a.email LIKE ? OR a.phone LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term]);
}

if (!empty($status_filter)) {
    $sql .= " AND a.status = ?";
    $params[] = $status_filter;
}

if (!empty($date_from)) {
    $sql .= " AND a.appointment_date >= ?";
    $params[] = $date_from;
}

if (!empty($date_to)) {
    $sql .= " AND a.appointment_date <= ?";
    $params[] = $date_to;
}

$sql .= " ORDER BY a.appointment_date DESC, a.appointment_time ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $appointments = $stmt->fetchAll();
} catch (PDOException $e) {
    $appointments = [];
    $message = show_message("Erreur de chargement des données.", "danger");
}

// Statuts pour le filtre
$statuses = [
    'en_attente' => 'En attente',
    'confirme' => 'Confirmé',
    'annule' => 'Annulé',
    'complete' => 'Complété'
];

require_once 'admin_header.php';
?>

<div class="container-fluid">
    <h2 class="fw-bold mb-4"><i class="fas fa-calendar-check me-2 text-primary"></i>Gestion des Rendez-vous</h2>

    <?php echo $message; ?>

    <!-- Filtres et Recherche -->
    <div class="card shadow-smooth mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-4">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher par nom, email ou téléphone..."
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status_filter" class="form-select">
                        <option value="">Tous les statuts</option>
                        <?php foreach ($statuses as $key => $label): ?>
                            <option value="<?php echo $key; ?>" <?php echo $status_filter == $key ? 'selected' : ''; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" 
                           placeholder="Date début" value="<?php echo $date_from; ?>">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" 
                           placeholder="Date fin" value="<?php echo $date_to; ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filtrer
                    </button>
                </div>
            </form>

            <?php if (!empty($search) || !empty($status_filter) || !empty($date_from) || !empty($date_to)): ?>
            <div class="mt-2">
                <a href="appointments.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Réinitialiser les filtres
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tableau des rendez-vous -->
    <div class="card shadow-smooth">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Rendez-vous (<?php echo count($appointments); ?>)</h5>
            <div>
                <button class="btn btn-success btn-sm" onclick="exportTableToCSV('appointmentsTable', 'rendez-vous.csv')">
                    <i class="fas fa-download me-1"></i>Exporter CSV
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="appointmentsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Contact</th>
                            <th>Spécialité</th>
                            <th>Date & Heure</th>
                            <th>Message</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Aucun rendez-vous trouvé
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $apt): ?>
                            <tr>
                                <td>#<?php echo $apt['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($apt['full_name']); ?></strong>
                                    <br><small class="text-muted">Créé le <?php echo date('d/m/Y', strtotime($apt['created_at'])); ?></small>
                                </td>
                                <td>
                                    <i class="fas fa-envelope text-muted me-1"></i><?php echo htmlspecialchars($apt['email']); ?><br>
                                    <i class="fas fa-phone text-muted me-1"></i><?php echo htmlspecialchars($apt['phone']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($apt['specialty_name']); ?></td>
                                <td>
                                    <i class="fas fa-calendar text-primary me-1"></i><?php echo date('d/m/Y', strtotime($apt['appointment_date'])); ?><br>
                                    <i class="fas fa-clock text-primary me-1"></i><?php echo $apt['appointment_time']; ?>
                                </td>
                                <td>
                                    <?php if (!empty($apt['message'])): ?>
                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" 
                                                title="<?php echo htmlspecialchars($apt['message']); ?>">
                                            <i class="fas fa-comment"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $apt['status']; ?>" style="font-size: 0.9rem;">
                                        <?php echo $statuses[$apt['status']]; ?>
                                    </span>
                                    <?php if ($apt['status'] == 'confirme' && $apt['confirmation_date']): ?>
                                        <br><small class="text-muted">Confirmé le <?php echo date('d/m H:i', strtotime($apt['confirmation_date'])); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($apt['status'] == 'en_attente'): ?>
                                            <a href="?status=confirme&id=<?php echo $apt['id']; ?>&<?php echo http_build_query($_GET); ?>" 
                                               class="btn btn-sm btn-success" 
                                               onclick="return confirmStatusChange(<?php echo $apt['id']; ?>, 'confirme');"
                                               data-bs-toggle="tooltip" title="Confirmer">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($apt['status'] != 'annule' && $apt['status'] != 'complete'): ?>
                                            <a href="?status=annule&id=<?php echo $apt['id']; ?>&<?php echo http_build_query($_GET); ?>" 
                                               class="btn btn-sm btn-warning" 
                                               onclick="return confirmStatusChange(<?php echo $apt['id']; ?>, 'annule');"
                                               data-bs-toggle="tooltip" title="Annuler">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($apt['status'] == 'confirme'): ?>
                                            <a href="?status=complete&id=<?php echo $apt['id']; ?>&<?php echo http_build_query($_GET); ?>" 
                                               class="btn btn-sm btn-info" 
                                               onclick="return confirmStatusChange(<?php echo $apt['id']; ?>, 'complete');"
                                               data-bs-toggle="tooltip" title="Marquer comme complété">
                                                <i class="fas fa-check-double"></i>
                                            </a>
                                        <?php endif; ?>

                                        <!-- Bouton Répondre au patient -->
                                        <button class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" data-bs-target="#replyModal"
                                                data-apt-id="<?php echo $apt['id']; ?>"
                                                data-apt-name="<?php echo htmlspecialchars($apt['full_name']); ?>"
                                                data-apt-current-reply="<?php echo htmlspecialchars($apt['admin_response'] ?? ''); ?>"
                                                title="Répondre au patient">
                                            <i class="fas fa-reply"></i>
                                        </button>

                                        <a href="?delete=<?php echo $apt['id']; ?>&<?php echo http_build_query($_GET); ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?');"
                                           data-bs-toggle="tooltip" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                    <?php if (!empty($apt['admin_response'])): ?>
                                        <br><small class="text-success"><i class="fas fa-check-circle me-1"></i>Réponse envoyée</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal : Répondre au patient -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="replyModalLabel">
                    <i class="fas fa-reply me-2"></i>Envoyer une réponse au patient
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-user me-2"></i>
                        Patient : <strong id="replyPatientName"></strong>
                        &nbsp;|&nbsp; Rendez-vous #<strong id="replyAptId"></strong>
                    </div>
                    <input type="hidden" name="admin_response_id" id="replyAptIdInput">
                    <div class="mb-3">
                        <label for="admin_response_text" class="form-label fw-bold">
                            <i class="fas fa-pen me-2 text-primary"></i>Message pour le patient
                        </label>
                        <textarea class="form-control" id="admin_response_text" name="admin_response_text" 
                                  rows="5" required
                                  placeholder="Écrivez votre réponse ici..."></textarea>
                        <div class="form-text">Ce message sera visible par le patient sur sa page de confirmation.</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted fw-bold">Réponses rapides :</small><br>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            <button type="button" class="btn btn-outline-success btn-sm quick-reply"
                                data-text="Votre rendez-vous a été confirmé. Veuillez vous présenter à l'heure prévue avec votre carte d'identité et vos documents médicaux.">
                                ✅ Confirmé
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm quick-reply"
                                data-text="Nous sommes désolés, mais votre rendez-vous a dû être annulé. Veuillez nous contacter pour prendre un nouveau rendez-vous.">
                                ❌ Annulé
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm quick-reply"
                                data-text="Votre demande de rendez-vous est en cours de traitement. Nous reviendrons vers vous sous 24h.">
                                ⏳ En traitement
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer la réponse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('replyModal').addEventListener('show.bs.modal', function (event) {
    const btn      = event.relatedTarget;
    const aptId    = btn.getAttribute('data-apt-id');
    const aptName  = btn.getAttribute('data-apt-name');
    const curReply = btn.getAttribute('data-apt-current-reply');

    document.getElementById('replyAptId').textContent       = aptId;
    document.getElementById('replyPatientName').textContent  = aptName;
    document.getElementById('replyAptIdInput').value         = aptId;
    document.getElementById('admin_response_text').value     = curReply;
});

document.querySelectorAll('.quick-reply').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('admin_response_text').value = this.getAttribute('data-text');
    });
});
</script>

<?php require_once 'admin_footer.php'; ?>
