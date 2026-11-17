-- Livres de Jules Verne, du plus ancien au plus récent
SELECT titre, auteur, annee
FROM livre
WHERE auteur = 'Jules Verne'
ORDER BY annee ASC;


-- Nombre de livres par genre
SELECT genre, COUNT(*) AS nombre_livres
FROM livre
GROUP BY genre
ORDER BY genre ASC;


-- Emprunts en cours, avec le nom de l'adhérent et le titre du livre
SELECT
    e.id,
    a.nom AS adherent,
    l.titre AS livre,
    e.date_emprunt
FROM emprunt AS e
INNER JOIN adherent AS a ON e.adherent_id = a.id
INNER JOIN livre AS l ON e.livre_id = l.id
WHERE e.date_retour IS NULL
ORDER BY e.date_emprunt DESC;


-- Les 5 livres les plus empruntés, avec leur nombre d'emprunts
SELECT
    l.id,
    l.titre,
    COUNT(e.id) AS nombre_emprunts
FROM livre AS l
LEFT JOIN emprunt AS e ON e.livre_id = l.id
GROUP BY l.id, l.titre
ORDER BY nombre_emprunts DESC, l.titre ASC
LIMIT 5;


-- Adhérents qui n'ont jamais emprunté de livre
SELECT a.id, a.nom, a.ville, a.email
FROM adherent AS a
LEFT JOIN emprunt AS e ON e.adherent_id = a.id
WHERE e.id IS NULL
ORDER BY a.nom ASC;