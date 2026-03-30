<?php
header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];

$urls = [
    '/',
    '/offers',
    '/companies',
    '/dashboard',
    '/login',
    '/contact',
    '/cgu',
    '/legal',
    '/privacy',
    '/terms',
];


// Générer dynamiquement les URLs d'offres depuis la base de données
$pdo = new PDO('mysql:host=localhost;dbname=thepiston', 'userthepiston', 'Thepiston1%'); // Adapter identifiants si besoin
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->query('SELECT ID_offre FROM Offre');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $urls[] = '/offers/detail&id=' . $row['ID_offre'];
}


foreach ($urls as $url) {
    echo "  <url>\n";
    echo "    <loc>{$baseUrl}{$url}</loc>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    echo "  </url>\n";
}

echo "</urlset>";
