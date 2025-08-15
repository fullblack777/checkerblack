<?php
session_start();
require_once '../database/database.php';

$db = new Database();
$error = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username && $password) {
        $user = $db->authenticateUser($username, $password);
        
        if ($user) {
            $sessionToken = $db->createSession($user['id'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
            setcookie('session_token', $sessionToken, time() + (24 * 60 * 60), '/'); // 24 horas
            
            $db->logActivity($user['id'], 'login', 'Usuário fez login', $_SERVER['REMOTE_ADDR']);
            
            if ($user['is_admin']) {
                header('Location: admin.php');
            } else {
                header('Location: checker.php');
            }
            exit;
        } else {
            $error = 'Usuário ou senha inválidos!';
        }
    } else {
        $error = 'Preencha todos os campos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Checker - Login</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 20% 80%, #0ff 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, #f0f 0%, transparent 50%);
        }
        
        .login-container {
            background-color: #1a1a1a;
            border: 2px solid #0ff;
            box-shadow: 0 0 20px #0ff, 0 0 40px #0ff inset;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: -5px; bottom: -5px; left: -5px; right: -5px;
            border-radius: 20px;
            background: linear-gradient(45deg, #0ff, #f0f, #0ff);
            z-index: -1;
            filter: blur(15px);
            opacity: 0.7;
            animation: neon-pulse 2s ease-in-out infinite alternate;
        }
        
        @keyframes neon-pulse {
            from { opacity: 0.5; transform: scale(1); }
            to { opacity: 0.9; transform: scale(1.02); }
        }
        
        h1 {
            text-align: center;
            color: #0ff;
            text-shadow: 0 0 15px #0ff;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #0ff;
            text-shadow: 0 0 5px #0ff;
        }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            background-color: #333;
            border: 1px solid #0ff;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #f0f;
            box-shadow: 0 0 10px #f0f;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #0ff, #f0f);
            color: #000;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 255, 0.5);
        }
        
        .error {
            background-color: #ff0033;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #aaa;
            font-size: 14px;
        }
        
        .footer a {
            color: #0ff;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Save Checker</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Entrar</button>
        </form>
        
        <div class="footer">
            Criado por <a href="#">@savefullblack</a> e <a href="#">@tropadoreiofc</a>
        </div>
    </div>
</body>
</html>

