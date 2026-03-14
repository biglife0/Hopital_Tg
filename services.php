<?php
$pageTitle = 'Nos Services - Hôpital Saint-Anténor';
require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-3">Nos Services Médicaux</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Services</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-5 bg-light-gradient">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-gradient fw-bold">Spécialités Médicales</h2>
            <p class="text-muted">Des services complets pour répondre à tous vos besoins de santé</p>
        </div>

        <div class="row g-4">
            <!-- Cardiologie -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-smooth">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h4 class="card-title">Cardiologie</h4>
                        <p class="card-text text-muted">
                            Diagnostic et traitement des maladies cardiovasculaires. Électrocardiogramme, échocardiographie, monitoring cardiaque.
                        </p>
                        <a href="reservation.php?specialty=1" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dermatologie -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-smooth">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-allergies"></i>
                        </div>
                        <h4 class="card-title">Dermatologie</h4>
                        <p class="card-text text-muted">
                            Soins de la peau, traitement des affections cutanées, dermatologie esthétique et dépistage des cancers de la peau.
                        </p>
                        <a href="reservation.php?specialty=2" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pédiatrie -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-smooth">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-baby"></i>
                        </div>
                        <h4 class="card-title">Pédiatrie</h4>
                        <p class="card-text text-muted">
                            Soins médicaux pour enfants de 0 à 18 ans. Vaccination, suivi de croissance, traitement des maladies infantiles.
                        </p>
                        <a href="reservation.php?specialty=3" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Neurologie -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-smooth">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-brain"></i>
                        </div>
                        <h4 class="card-title">Neurologie</h4>
                        <p class="card-text text-muted">
                            Diagnostic et traitement des troubles du système nerveux : cerveau, moelle épinière et nerfs périphériques.
                        </p>
                        <a href="reservation.php?specialty=4" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Orthopédie -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-smooth">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-bone"></i>
                        </div>
                        <h4 class="card-title">Orthopédie</h4>
                        <p class="card-text text-muted">
                            Soins des affections du système musculo-squelettique : os, articulations, ligaments, tendons et muscles.
                        </p>
                        <a href="reservation.php?specialty=5" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ophtalmologie -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-smooth">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h4 class="card-title">Ophtalmologie</h4>
                        <p class="card-text text-muted">
                            Soins complets des yeux : examen de la vue, correction optique, chirurgie des cataractes et traitement des maladies oculaires.
                        </p>
                        <a href="reservation.php?specialty=6" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gynécologie -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-smooth">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-female"></i>
                        </div>
                        <h4 class="card-title">Gynécologie</h4>
                        <p class="card-text text-muted">
                            Santé reproductive féminine, suivi de grossesse, planification familiale et dépistage gynécologique.
                        </p>
                        <a href="reservation.php?specialty=7" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Médecine Générale -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-smooth">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h4 class="card-title">Médecine Générale</h4>
                        <p class="card-text text-muted">
                            Consultations générales, bilans de santé, suivi des maladies chroniques et prévention.
                        </p>
                        <a href="reservation.php?specialty=8" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dentisterie -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-smooth">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-tooth"></i>
                        </div>
                        <h4 class="card-title">Dentisterie</h4>
                        <p class="card-text text-muted">
                            Soins dentaires complets : prévention, orthodontie, chirurgie dentaire et esthétique du sourire.
                        </p>
                        <a href="reservation.php?specialty=9" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-plus me-1"></i>Réserver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Service -->
        <div class="mt-5">
            <div class="card bg-danger text-white shadow-lg">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="fw-bold mb-2"><i class="fas fa-ambulance me-2"></i>Service d'Urgence 24/7</h3>
                            <p class="mb-0">Notre service d'urgence est ouvert en permanence pour tous les cas médicaux urgents. N'attendez pas en cas d'urgence !</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="tel:+50912345678" class="btn btn-light btn-lg">
                                <i class="fas fa-phone me-2"></i>+509 1234 5678
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
