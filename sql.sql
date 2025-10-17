-- Table patient (déjà existante normalement)
CREATE TABLE IF NOT EXISTS patient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    adresse TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table spécialiste
CREATE TABLE IF NOT EXISTS specialiste (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    specialite VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    adresse TEXT,
    telephone VARCHAR(20),
    email VARCHAR(150),
    photo VARCHAR(255) DEFAULT 'default-doctor.jpg',
    tarif DECIMAL(10,2),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion de quelques spécialistes pour tester
INSERT INTO specialiste (nom, prenom, specialite, ville, adresse, telephone, email, tarif) VALUES
('Dupont', 'Marie', 'Cardiologue', 'Lille', '15 Rue de la République, 59000 Lille', '03 20 12 34 56', 'marie.dupont@healthnorth.fr', 50.00),
('Martin', 'Pierre', 'Dermatologue', 'Lille', '28 Avenue du Peuple Belge, 59000 Lille', '03 20 98 76 54', 'pierre.martin@healthnorth.fr', 45.00),
('Durand', 'Sophie', 'Généraliste', 'Roubaix', '42 Rue du Général Leclerc, 59100 Roubaix', '03 20 11 22 33', 'sophie.durand@healthnorth.fr', 25.00),
('Lefebvre', 'Jean', 'Pédiatre', 'Tourcoing', '8 Boulevard Gambetta, 59200 Tourcoing', '03 20 44 55 66', 'jean.lefebvre@healthnorth.fr', 35.00),
('Bernard', 'Claire', 'Ophtalmologue', 'Lille', '55 Rue Nationale, 59000 Lille', '03 20 77 88 99', 'claire.bernard@healthnorth.fr', 55.00),
('Petit', 'Thomas', 'ORL', 'Villeneuve-d\'Ascq', '12 Rue Jean Jaurès, 59650 Villeneuve-d\'Ascq', '03 20 22 33 44', 'thomas.petit@healthnorth.fr', 48.00),
('Rousseau', 'Emma', 'Gynécologue', 'Lille', '33 Rue Solférino, 59000 Lille', '03 20 55 66 77', 'emma.rousseau@healthnorth.fr', 52.00),
('Moreau', 'Lucas', 'Dentiste', 'Marcq-en-Barœul', '19 Avenue Foch, 59700 Marcq-en-Barœul', '03 20 88 99 00', 'lucas.moreau@healthnorth.fr', 40.00);