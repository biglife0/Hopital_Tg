<?php
if (!isset($pageTitle)) $pageTitle = 'Administration';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | Hôpital Saint-Anténor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse">
                <div class="text-center mb-4 pt-3">
                    <i class="fas fa-hospital-alt fa-3x text-warning mb-2"></i>
                    <h5 class="text-white">Admin Panel</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de Bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'appointments.php' ? 'active' : ''; ?>" href="appointments.php">
                            <i class="fas fa-calendar-check me-2"></i>Rendez-vous
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>" href="messages.php">
                            <i class="fas fa-envelope me-2"></i>Messages
                            <?php 
                            // Badge pour messages non lus
                            try {
                                $unread = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'non_lu'")->fetchColumn();
                                if ($unread > 0) echo '<span class="badge bg-danger ms-2">'.$unread.'</span>';
                            } catch(PDOException $e) {}
                            ?>
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-warning" href="../index.php" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>Voir le Site
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 admin-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom d-md-none">
                    <h1 class="h2"><?php echo $pageTitle; ?></h1>
                    <button class="btn btn-dark" type="button" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
