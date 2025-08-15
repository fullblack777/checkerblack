<?php
require_once 'auth.php';
$session = checkAuth();
require_once '../database/database.php';

if ($_POST && isset($_POST['card'])) {
    $db = new Database();
    $card = $_POST['card'];
    $parts = explode('|', $card);
    
    if (count($parts) >= 4) {
        $cardNumber = $parts[0];
        $expMonth = $parts[1];
        $expYear = $parts[2];
        $cvv = $parts[3];
        
        // Obter informações do BIN (simulado)
        $bin = substr($cardNumber, 0, 6);
        $binInfo = "BIN: $bin"; // Aqui você pode integrar com uma API real de BIN
        
        $success = $db->saveLiveCard($cardNumber, $expMonth, $expYear, $cvv, $binInfo, $session['user_id']);
        
        if ($success) {
            $db->logActivity($session['user_id'], 'live_card_saved', "Cartão live salvo: $cardNumber", $_SERVER['REMOTE_ADDR']);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erro ao salvar cartão']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Formato de cartão inválido']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Dados não fornecidos']);
}

// Criado por @savefullblack e @tropadoreiofc
?>

