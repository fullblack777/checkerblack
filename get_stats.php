<?php
require_once 'auth.php';
$session = checkAuth(true); // Requer admin
require_once '../database/database.php';

header('Content-Type: application/json');

$db = new Database();

$stats = [
    'online_users' => $db->getOnlineUsersCount(),
    'total_users' => $db->getTotalUsersCount(),
    'live_cards_count' => count($db->getLiveCards(1000))
];

echo json_encode($stats);

// Criado por @savefullblack e @tropadoreiofc
?>

