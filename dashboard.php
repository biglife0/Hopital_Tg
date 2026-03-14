<?php
require_once '../includes/config.php';

// Vérifier l'authentification
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = 'Tableau de Bord - Administration';

// Statistiques
try {
    // Total rendez-vous
    $total_appointments = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();

    // Rendez-vous en attente
    $pending_appointments = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'en_attente'")->fetchColumn();

    // Rendez-vous confirmés
    $confirmed_appointments = $pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'confirme'")->fetchColumn();

    // Messages non lus
    $unread_messages = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'non_lu'")->fetchColumn();

    // Derniers rendez-vous
    $recent_appointments = $pdo->query("
        SELECT a.*, s.name as specialty_name 
        FROM appointments a 
        JOIN specialties s ON a.specialty_id = s.id 
        ORDER BY a.created_at DESC 
        LIMIT 5
    ")->fetchAll();

    // Derniers messages
    $recent_messages = $pdo->query("
        SELECT * FROM contact_messages 
        ORDER BY created_at DESC 
        LIMIT 5
    ")->fetchAll();

} catch (PDOException $e) {
    die("Erreur de base de données");
}

require_once 'admin_header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Tableau de Bord</h2>
        <div>
            <span class="text-muted">Bienvenue, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-smooth">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Rendez-vous</h6>
                            <h3 class="mb-0 mt-2"><?php echo $total_appointments; ?></h3>
                        </div>
                        <i class="fas fa-calendar-check fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-smooth">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">En Attente</h6>
                            <h3 class="mb-0 mt-2"><?php echo $pending_appointments; ?></h3>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-smooth">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Confirmés</h6>
                            <h3 class="mb-0 mt-2"><?php echo $confirmed_appointments; ?></h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-smooth">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Messages Non Lus</h6>
                            <h3 class="mb-0 mt-2"><?php echo $unread_messages; ?></h3>
                        </div>
                        <i class="fas fa-envelope fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Derniers Rendez-vous -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-smooth">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Derniers Rendez-vous</h5>
                    <a href="appointments.php" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Spécialité</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_appointments as $apt): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($apt['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($apt['specialty_name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($apt['appointment_date'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $apt['status']; ?>">
                                            <?php echo str_replace(['en_attente', 'confirme', 'annule', 'complete'], 
                                                                 ['En attente', 'Confirmé', 'Annulé', 'Complété'], $apt['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Derniers Messages -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-smooth">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Derniers Messages</h5>
                    <a href="messages.php" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_messages as $msg): ?>
                        <a href="messages.php?id=<?php echo $msg['id']; ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo htmlspecialchars($msg['subject']); ?></h6>
                                <small class="text-muted"><?php echo date('d/m H:i', strtotime($msg['created_at'])); ?></small>
                            </div>
                            <p class="mb-1 text-truncate"><?php echo htmlspecialchars($msg['message']); ?></p>
                            <small class="<?php echo $msg['status'] == 'non_lu' ? 'text-danger fw-bold' : 'text-muted'; ?>">
                                <i class="fas fa-<?php echo $msg['status'] == 'non_lu' ? 'envelope' : 'envelope-open'; ?> me-1"></i>
                                <?php echo $msg['status'] == 'non_lu' ? 'Non lu' : 'Lu'; ?> - 
                                <?php echo htmlspecialchars($msg['name']); ?>
                            </small>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'admin_footer.php'; ?>
