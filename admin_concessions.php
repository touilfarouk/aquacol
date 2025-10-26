<?php
require_once 'config.php';

// Start session if not already started
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// // Check if user is logged in as admin
// $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

// if (!$isAdmin) {
//     // Redirect to login or show access denied
//     header('Location: index.php?error=access_denied');
//     exit();
// }

// Handle concession deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("DELETE FROM coordinates WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success_message'] = "Concession supprimée avec succès.";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur lors de la suppression de la concession: " . $e->getMessage();
    }
    
    header('Location: admin_concessions.php');
    exit();
}

// Get all concessions
$concessions = [];
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT * FROM coordinates ORDER BY id DESC");
    $concessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Erreur lors de la récupération des concessions: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Concessions - Administration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .table-responsive { margin: 20px 0; }
        .action-btns .btn { margin-right: 5px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Administration</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                   <li class="nav-item">
                        <a class="nav-link active" href="index.html">Carte</a>
                    </li>
                </ul>
                <!-- <a href="index.php" class="btn btn-light">Retour au site</a> -->
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestion des Concessions</h2>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Zone</th>
                        <!-- <th>Type</th> -->
                         <!-- nom_zone -->
                        <th>Superficie (m²)</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($concessions as $concession): ?>
                    <tr>
                        <td><?= htmlspecialchars($concession['id']) ?></td>
                        <td><?= htmlspecialchars($concession['code_concession']) ?></td>
                        <td><?= htmlspecialchars($concession['zone']) ?></td>
                        
                        <td><?= number_format($concession['superficie'], 2) ?></td>
                        <td>
                            <?php $statut = $concession['statut'] ?? 'inactive'; ?>
                            <span class="badge bg-<?= $statut === 'active' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($statut) ?>
                            </span>
                        </td>
                        <td class="action-btns">
                            <a href="edit_concession.php?id=<?= $concession['id'] ?>" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" onclick="confirmDelete(<?= $concession['id'] ?>)" class="btn btn-sm btn-danger" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($concessions)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Aucune concession trouvée</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette concession ? Cette action est irréversible.')) {
            window.location.href = 'admin_concessions.php?action=delete&id=' + id;
        }
    }
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
