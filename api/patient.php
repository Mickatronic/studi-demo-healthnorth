<?php
header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

$host = 'localhost';
$db   = 'labo';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo json_encode(['message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
    exit;
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route pour créer un patient
if ($path == '/test/api/patient.php/register' && $method == 'POST') {
    try {
        $nom = $input['nom'];
        $prenom = $input['prenom'];
        $email = $input['email'];
        $mdp = password_hash($input['mdp'], PASSWORD_DEFAULT);
        $adresse = $input['adresse'];

        $stmt = $pdo->prepare("INSERT INTO patient (nom, prenom, email, mdp, adresse) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $mdp, $adresse]);
        echo json_encode(['message' => 'Patient créé avec succès']);
    } catch (\Exception $e) {
        echo json_encode(['message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()]);
    }
}

// Route pour la connexion d'un patient
elseif ($path == '/test/api/patient.php/login' && $method == 'POST') {
    try {
        $email = $input['email'];
        $mdp = $input['mdp'];

        $stmt = $pdo->prepare("SELECT * FROM patient WHERE email = ?");
        $stmt->execute([$email]);
        $patient = $stmt->fetch();

        if ($patient && password_verify($mdp, $patient['mdp'])) {
            echo json_encode(['message' => 'Connexion réussie', 'patient' => $patient]);
        } else {
            echo json_encode(['message' => 'Identifiants incorrects']);
        }
    } catch (\Exception $e) {
        echo json_encode(['message' => 'Erreur lors de la connexion: ' . $e->getMessage()]);
    }
}

// Route pour obtenir la liste de tous les patients
elseif ($path == '/test/api/patient.php/patients' && $method == 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM patient");
        $patients = $stmt->fetchAll();
        echo json_encode($patients);
    } catch (\Exception $e) {
        echo json_encode(['message' => 'Erreur lors de la récupération des patients: ' . $e->getMessage()]);
    }
}

// Route pour obtenir un patient spécifique
elseif (preg_match('#^/test/api/patient.php/patients/(\d+)$#', $path, $matches) && $method == 'GET') {
    try {
        $id = $matches[1];
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE id = ?");
        $stmt->execute([$id]);
        $patient = $stmt->fetch();

        if ($patient) {
            echo json_encode($patient);
        } else {
            echo json_encode(['message' => 'Patient non trouvé']);
        }
    } catch (\Exception $e) {
        echo json_encode(['message' => 'Erreur lors de la récupération du patient: ' . $e->getMessage()]);
    }
}

// Route pour mettre à jour un patient
elseif (preg_match('#^/test/api/patient.php/patients/(\d+)$#', $path, $matches) && $method == 'PUT') {
    try {
        $id = $matches[1];
        $nom = $input['nom'];
        $prenom = $input['prenom'];
        $email = $input['email'];
        $adresse = $input['adresse'];

        // Ne pas hacher le mot de passe si ce n'est pas nécessaire
        $mdp = isset($input['mdp']) ? password_hash($input['mdp'], PASSWORD_DEFAULT) : null;

        if ($mdp) {
            $stmt = $pdo->prepare("UPDATE patient SET nom = ?, prenom = ?, email = ?, mdp = ?, adresse = ? WHERE id = ?");
            $stmt->execute([$nom, $prenom, $email, $mdp, $adresse, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE patient SET nom = ?, prenom = ?, email = ?, adresse = ? WHERE id = ?");
            $stmt->execute([$nom, $prenom, $email, $adresse, $id]);
        }

        echo json_encode(['message' => 'Patient mis à jour avec succès']);
    } catch (\Exception $e) {
        echo json_encode(['message' => 'Erreur lors de la mise à jour du patient: ' . $e->getMessage()]);
    }
}

// Route pour supprimer un patient
elseif (preg_match('#^/test/api/patient.php/patients/(\d+)$#', $path, $matches) && $method == 'DELETE') {
    try {
        $id = $matches[1];
        $stmt = $pdo->prepare("DELETE FROM patient WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Patient supprimé avec succès']);
    } catch (\Exception $e) {
        echo json_encode(['message' => 'Erreur lors de la suppression du patient: ' . $e->getMessage()]);
    }
}

// Route par défaut si aucune route ne correspond
else {
    echo json_encode(['message' => 'Route non trouvée']);
}
?>
