<?php
$pageTitle = 'Accueil - Hôpital Saint-Anténor';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="hero-title fade-in-up">Votre Santé, Notre Priorité</h1>
                <p class="hero-subtitle fade-in-up">L'Hôpital Saint-Anténor offre des soins médicaux de qualité supérieure avec une équipe de professionnels dévoués et des équipements modernes.</p>
                <div class="d-flex gap-3 fade-in-up">
                    <a href="reservation.php" class="btn btn-warning btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Prendre Rendez-vous
                    </a>
                    <a href="services.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-list me-2"></i>Nos Services
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block">
                <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=600&h=400&fit=crop" 
                     alt="Hôpital moderne" class="img-fluid rounded-xl shadow-lg" style="max-height: 400px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Médecins Spécialistes</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">10k+</div>
                    <div class="stat-label">Patients Guéris</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">15</div>
                    <div class="stat-label">Années d'Expérience</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Service d'Urgence</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview -->
<section class="py-5 bg-light-gradient">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-gradient fw-bold">Nos Services Médicaux</h2>
            <p class="text-muted">Des soins complets pour toute la famille</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card shadow-smooth">
                    <div class="service-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h4>Cardiologie</h4>
                    <p class="text-muted">Diagnostic et traitement des maladies cardiaques avec des équipements de dernière génération.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card shadow-smooth">
                    <div class="service-icon">
                        <i class="fas fa-baby"></i>
                    </div>
                    <h4>Pédiatrie</h4>
                    <p class="text-muted">Soins spécialisés pour les nourrissons, enfants et adolescents dans un environnement adapté.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card shadow-smooth">
                    <div class="service-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h4>Neurologie</h4>
                    <p class="text-muted">Traitement des troubles du système nerveux par des experts en neurologie.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="services.php" class="btn btn-primary">
                <i class="fas fa-arrow-right me-2"></i>Voir Tous les Services
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=600&h=450&fit=crop" 
                     alt="Équipe médicale" class="img-fluid rounded-xl shadow-lg">
            </div>
            <div class="col-lg-6">
                <h2 class="text-gradient fw-bold mb-4">Pourquoi Choisir Notre Hôpital ?</h2>

                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="card-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            <i class="fas fa-user-md"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5>Équipe Médicale Expertise</h5>
                        <p class="text-muted mb-0">Nos médecins sont hautement qualifiés avec des années d'expérience dans leurs domaines respectifs.</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="card-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            <i class="fas fa-hospital"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5>Infrastructures Modernes</h5>
                        <p class="text-muted mb-0">Équipements médicaux de pointe et installations confortables pour les patients.</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="card-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5>Disponibilité 24/7</h5>
                        <p class="text-muted mb-0">Notre service d'urgence est opérationnel 24 heures sur 24, 7 jours sur 7.</p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="card-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5>Sécurité Garantie</h5>
                        <p class="text-muted mb-0">Protocoles stricts d'hygiène et de sécurité pour la protection de nos patients.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Besoin d'une Consultation Médicale ?</h2>
        <p class="lead mb-4">Prenez rendez-vous en ligne en quelques clics. C'est rapide, simple et sécurisé.</p>
        <a href="reservation.php" class="btn btn-warning btn-lg">
            <i class="fas fa-calendar-check me-2"></i>Réserver Maintenant
        </a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
