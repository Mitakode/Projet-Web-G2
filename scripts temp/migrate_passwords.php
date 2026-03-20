<?php

declare(strict_types=1);

$dsn = getenv('DB_DSN') ?: 'mysql:host=localhost;dbname=thepiston;charset=utf8';
$user = getenv('DB_USER') ?: 'userthepiston';
$pass = getenv('DB_PASS') ?: 'Thepiston1%';

function write_stderr(string $message): void
{
    $line = $message . PHP_EOL;

    if (defined('STDERR') && is_resource(STDERR)) {
        if (@fwrite(STDERR, $line) !== false) {
            return;
        }
    }

    $stream = @fopen('php://stderr', 'ab');
    if ($stream !== false) {
        if (@fwrite($stream, $line) !== false) {
            fclose($stream);
            return;
        }
        fclose($stream);
    }

    echo $line;
}

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    write_stderr("Connexion BDD impossible: " . $e->getMessage());
    exit(1);
}

try {
    // Necessaire pour stocker les hashes bcrypt/argon.
    try {
        $pdo->exec('ALTER TABLE Utilisateur MODIFY COLUMN Mot_de_passe VARCHAR(255) NOT NULL');
    } catch (PDOException $e) {
        // 1142: l'utilisateur SQL n'a pas le droit ALTER.
        if (($e->errorInfo[1] ?? null) === 1142) {
            write_stderr("Warning: pas de droit ALTER. Demander a un admin SQL d'executer: ALTER TABLE Utilisateur MODIFY COLUMN Mot_de_passe VARCHAR(255) NOT NULL;");
        } else {
            throw $e;
        }
    }

    $rows = $pdo->query('SELECT ID_utilisateur, Mot_de_passe FROM Utilisateur')->fetchAll(PDO::FETCH_ASSOC);

    $updated = 0;
    $skipped = 0;
    $failedTooLong = 0;

    $updateStmt = $pdo->prepare('UPDATE Utilisateur SET Mot_de_passe = ? WHERE ID_utilisateur = ?');

    foreach ($rows as $row) {
        $id = (int) ($row['ID_utilisateur'] ?? 0);
        $stored = (string) ($row['Mot_de_passe'] ?? '');

        if ($id <= 0 || $stored === '') {
            $skipped++;
            continue;
        }

        // Sur certaines versions PHP, algo peut etre NULL. On se base sur algoName.
        $hashInfo = password_get_info($stored);
        if (($hashInfo['algoName'] ?? 'unknown') !== 'unknown') {
            $skipped++;
            continue;
        }

        $hash = password_hash($stored, PASSWORD_BCRYPT);
        if ($hash === false) {
            $skipped++;
            continue;
        }

        try {
            $updateStmt->execute([$hash, $id]);
            $updated++;
        } catch (PDOException $e) {
            // 1406: colonne trop courte pour le hash.
            if (($e->errorInfo[1] ?? null) === 1406) {
                $failedTooLong++;
                continue;
            }
            throw $e;
        }
    }

    echo "Migration terminee. Hashes mis a jour: {$updated}. Lignes ignorees: {$skipped}. Erreurs colonne trop courte: {$failedTooLong}." . PHP_EOL;
} catch (PDOException $e) {
    write_stderr("Erreur SQL: " . $e->getMessage());
    exit(1);
}
