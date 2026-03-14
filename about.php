<?php
$pageTitle = 'À Propos - Hôpital Saint-Anténor';
require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-3">À Propos de Nous</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">À Propos</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-smooth">
                    <div class="card-body p-5">
                        <h2 class="text-gradient fw-bold mb-4">Notre Histoire</h2>

                        <p class="lead text-muted mb-4">
                            Fondé en 2009, l'Hôpital Saint-Anténor est devenu l'un des établissements de santé les plus respectés de la région du Nord d'Haïti. Notre mission est de fournir des soins médicaux accessibles, de qualité et centrés sur le patient.
                        </p>

                        <p class="mb-4">
                            Nous croyons que chaque patient mérite une attention personnalisée et des traitements basés sur les dernières avancées médicales. Notre équipe multidisciplinaire travaille en collaboration pour assurer les meilleurs résultats possibles pour nos patients.
                        </p>

                        <h3 class="fw-bold mt-5 mb-3">Notre Mission</h3>
                        <p class="mb-4">
                            Offrir des soins de santé de qualité supérieure, accessibles à tous, avec compassion et respect de la dignité humaine. Nous nous engageons à améliorer la santé de notre communauté à travers la prévention, le traitement et l'éducation.
                        </p>

                        <h3 class="fw-bold mt-5 mb-3">Nos Valeurs</h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-heart text-danger fa-2x me-3"></i>
                                    </div>
                                    <div>
                                        <h5>Compassion</h5>
                                        <p class="text-muted mb-0">Nous traitons chaque patient avec empathie et bienveillance.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-award text-warning fa-2x me-3"></i>
                                    </div>
                                    <div>
                                        <h5>Excellence</h5>
                                        <p class="text-muted mb-0">Nous visons l'excellence dans tous les aspects de nos soins.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-handshake text-primary fa-2x me-3"></i>
                                    </div>
                                    <div>
                                        <h5>Intégrité</h5>
                                        <p class="text-muted mb-0">Nous agissons avec honnêteté et transparence.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-users text-success fa-2x me-3"></i>
                                    </div>
                                    <div>
                                        <h5>Collaboration</h5>
                                        <p class="text-muted mb-0">Nous travaillons ensemble pour le bien de nos patients.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="fw-bold mt-5 mb-3">Notre Équipe</h3>
                        <p class="mb-4">
                            Notre hôpital compte plus de 50 professionnels de santé, incluant des médecins spécialistes, des infirmiers qualifiés, des techniciens de laboratoire et du personnel administratif dévoué. Ensemble, nous formons une équipe unie par la passion de soigner.
                        </p>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Université Anténor Firmin (UNAF)</strong><br>
                            Ce projet de développement web dynamique est réalisé dans le cadre du module de Développement Web Dynamique de la Faculté des Sciences Informatiques.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
