<?php
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = 'Gestion des Messages';

// Traitement des actions
$message = '';

// Répondre à un message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_message'])) {
    $message_id = $_POST['message_id'];
    $reply = clean_input($_POST['admin_reply']);

    if (!empty($reply)) {
        try {
            $stmt = $pdo->prepare("UPDATE contact_messages 
                                   SET admin_reply = ?, reply_date = NOW(), status = 'repondu' 
                                   WHERE id = ?");
            $stmt->execute([$reply, $message_id]);
            $message = show_message("Réponse envoyée avec succès.", "success");
        } catch (PDOException $e) {
            $message = show_message("Erreur lors de l'envoi de la réponse.", "danger");
        }
    }
}

// Marquer comme lu
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    try {
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'lu' WHERE id = ?");
        $stmt->execute([$_GET['mark_read']]);
        $message = show_message("Message marqué comme lu.", "success");
    } catch (PDOException $e) {
        $message = show_message("Erreur.", "danger");
    }
}

// Suppression
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = show_message("Message supprimé.", "success");
    } catch (PDOException $e) {
        $message = show_message("Erreur lors de la suppression.", "danger");
    }
}

// Paramètres de recherche
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status_filter'] ?? '';

// Construction de la requête
$sql = "SELECT * FROM contact_messages WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
}

if (!empty($status_filter)) {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}

$sql .= " ORDER BY created_at DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    $messages = [];
}

// Statuts
$statuses = [
    'non_lu' => ['label' => 'Non lu', 'class' => 'danger', 'icon' => 'envelope'],
    'lu' => ['label' => 'Lu', 'class' => 'warning', 'icon' => 'envelope-open'],
    'repondu' => ['label' => 'Répondu', 'class' => 'success', 'icon' => 'reply']
];

// Message spécifique à afficher
$single_message = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $single_message = $stmt->fetch();

        // Marquer automatiquement comme lu si non lu
        if ($single_message && $single_message['status'] == 'non_lu') {
            $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'lu' WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $single_message['status'] = 'lu';
        }
    } catch (PDOException $e) {}
}

require_once 'admin_header.php';
?>

<div class="container-fluid">
    <h2 class="fw-bold mb-4"><i class="fas fa-envelope me-2 text-primary"></i>Gestion des Messages</h2>

    <?php echo $message; ?>

    <?php if ($single_message): ?>
    <!-- Vue détaillée d'un message -->
    <div class="card shadow-smooth mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Détail du Message #<?php echo $single_message['id']; ?></h5>
            <a href="messages.php" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Retour à la liste
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong><i class="fas fa-user me-2"></i>De:</strong> <?php echo htmlspecialchars($single_message['name']); ?></p>
                    <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> 
                       <a href="mailto:<?php echo $single_message['email']; ?>"><?php echo htmlspecialchars($single_message['email']); ?></a>
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong><i class="fas fa-clock me-2"></i>Reçu le:</strong> 
                       <?php echo date('d/m/Y à H:i', strtotime($single_message['created_at'])); ?>
                    </p>
                    <p><strong><i class="fas fa-tag me-2"></i>Statut:</strong>
                        <span class="badge bg-<?php echo $statuses[$single_message['status']]['class']; ?>">
                            <?php echo $statuses[$single_message['status']]['label']; ?>
                        </span>
                    </p>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold"><i class="fas fa-heading me-2"></i>Sujet:</h6>
                <p class="lead"><?php echo htmlspecialchars($single_message['subject']); ?></p>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold"><i class="fas fa-comment me-2"></i>Message:</h6>
                <div class="p-3 bg-light rounded">
                    <?php echo nl2br(htmlspecialchars($single_message['message'])); ?>
                </div>
            </div>

            <!-- Réponse existante -->
            <?php if (!empty($single_message['admin_reply'])): ?>
            <div class="message-reply mb-4">
                <div class="reply-header">
                    <i class="fas fa-reply me-2"></i>Votre réponse (envoyée le <?php echo date('d/m/Y à H:i', strtotime($single_message['reply_date'])); ?>)
                </div>
                <div class="reply-content">
                    <?php echo nl2br(htmlspecialchars($single_message['admin_reply'])); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Formulaire de réponse -->
            <?php if (empty($single_message['admin_reply'])): ?>
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-reply me-2"></i>Répondre à ce message
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="message_id" value="<?php echo $single_message['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Votre réponse:</label>
                            <textarea name="admin_reply" id="reply_<?php echo $single_message['id']; ?>" 
                                      class="form-control" rows="5" required
                                      placeholder="Écrivez votre réponse ici..."></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="reply_message" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer la réponse
                            </button>
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="previewReply(<?php echo $single_message['id']; ?>)">
                                <i class="fas fa-eye me-2"></i>Prévisualiser
                            </button>
                        </div>
                        <div id="preview_<?php echo $single_message['id']; ?>" class="mt-3" style="display:none;"></div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <div class="mt-3">
                <a href="?delete=<?php echo $single_message['id']; ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Supprimer ce message ?');">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Filtres -->
    <div class="card shadow-smooth mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-6">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher par nom, email, sujet ou contenu..."
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status_filter" class="form-select">
                        <option value="">Tous les statuts</option>
                        <?php foreach ($statuses as $key => $info): ?>
                            <option value="<?php echo $key; ?>" <?php echo $status_filter == $key ? 'selected' : ''; ?>>
                                <?php echo $info['label']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filtrer
                    </button>
                </div>
            </form>

            <?php if (!empty($search) || !empty($status_filter)): ?>
            <div class="mt-2">
                <a href="messages.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Réinitialiser
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Liste des messages -->
    <div class="card shadow-smooth">
        <div class="card-header">
            <h5 class="mb-0">Messages reçus (<?php echo count($messages); ?>)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Statut</th>
                            <th>Expéditeur</th>
                            <th>Sujet</th>
                            <th>Aperçu</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Aucun message trouvé
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($messages as $msg): ?>
                            <tr class="<?php echo $msg['status'] == 'non_lu' ? 'table-warning' : ''; ?>">
                                <td>
                                    <span class="badge bg-<?php echo $statuses[$msg['status']]['class']; ?>">
                                        <i class="fas fa-<?php echo $statuses[$msg['status']]['icon']; ?> me-1"></i>
                                        <?php echo $statuses[$msg['status']]['label']; ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($msg['name']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($msg['email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                <td>
                                    <span class="text-muted">
                                        <?php echo substr(htmlspecialchars($msg['message']), 0, 50) . (strlen($msg['message']) > 50 ? '...' : ''); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($msg['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="?id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-primary" 
                                           data-bs-toggle="tooltip" title="Voir / Répondre">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <?php if ($msg['status'] == 'non_lu'): ?>
                                            <a href="?mark_read=<?php echo $msg['id']; ?>" class="btn btn-sm btn-warning" 
                                               data-bs-toggle="tooltip" title="Marquer comme lu">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="?delete=<?php echo $msg['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Supprimer ce message ?');"
                                           data-bs-toggle="tooltip" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
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

<?php require_once 'admin_footer.php'; ?>
