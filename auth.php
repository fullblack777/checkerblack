<?php
session_start();
require_once '../database/database.php';

function checkAuth($requireAdmin = false) {
    $db = new Database();
    
    $sessionToken = $_COOKIE['session_token'] ?? '';
    
    if (!$sessionToken) {
        header('Location: login.php');
        exit;
    }
    
    $session = $db->validateSession($sessionToken);
    
    if (!$session) {
        setcookie('session_token', '', time() - 3600, '/'); // Remove cookie
        header('Location: login.php');
        exit;
    }
    
    if ($requireAdmin && !$session['is_admin']) {
        header('Location: checker.php');
        exit;
    }
    
    return $session;
}

function logout() {
    setcookie('session_token', '', time() - 3600, '/');
    header('Location: login.php');
    exit;
}

// Criado por @savefullblack e @tropadoreiofc
?>

