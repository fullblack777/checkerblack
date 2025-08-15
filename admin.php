<?php
require_once 'auth.php';
$session = checkAuth(true); // Requer admin
require_once '../database/database.php';

$db = new Database();

// Obter estatísticas
$onlineUsers = $db->getOnlineUsersCount();
$totalUsers = $db->getTotalUsersCount();
$liveCards = $db->getLiveCards(50); // Últimos 50 cartões live

if (isset($_GET['logout'])) {
    logout();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Checker - Painel Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .header {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #ff6600;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            font-size: 1.8em;
            font-weight: bold;
            color: #ff6600;
            text-shadow: 0 0 10px #ff6600;
        }
        
        .admin-badge {
            background: linear-gradient(45deg, #ff6600, #ffaa00);
            color: #000;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-name {
            color: #ff6600;
            font-weight: bold;
        }
        
        .logout-btn {
            background: linear-gradient(45deg, #ff0033, #ff6666);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.3s;
        }
        
        .logout-btn:hover {
            transform: scale(1.05);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid #ff6600;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: -2px; bottom: -2px; left: -2px; right: -2px;
            background: linear-gradient(45deg, #ff6600, #ffaa00, #ff6600);
            border-radius: 17px;
            z-index: -1;
            filter: blur(8px);
            opacity: 0.6;
            animation: admin-glow 3s ease-in-out infinite alternate;
        }
        
        @keyframes admin-glow {
            from { opacity: 0.4; }
            to { opacity: 0.8; }
        }
        
        .stat-number {
            font-size: 3em;
            font-weight: bold;
            color: #ff6600;
            text-shadow: 0 0 15px #ff6600;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: #ccc;
            font-size: 1.1em;
        }
        
        .online-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #00ff00;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(0, 255, 0, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(0, 255, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 255, 0, 0); }
        }
        
        .section {
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid #ff6600;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .section-title {
            color: #ff6600;
            font-size: 1.5em;
            margin-bottom: 20px;
            text-shadow: 0 0 10px #ff6600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .repository-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 15px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .card-item {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #00ff00;
            border-radius: 8px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 255, 0, 0.3);
        }
        
        .card-number {
            color: #00ff00;
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 8px;
        }
        
        .card-details {
            color: #ccc;
            font-size: 0.9em;
            line-height: 1.4;
        }
        
        .card-meta {
            color: #888;
            font-size: 0.8em;
            margin-top: 8px;
            border-top: 1px solid #333;
            padding-top: 8px;
        }
        
        .refresh-btn {
            background: linear-gradient(45deg, #ff6600, #ffaa00);
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        
        .refresh-btn:hover {
            transform: scale(1.05);
        }
        
        .empty-state {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #666;
            border-top: 1px solid #333;
        }
        
        .footer a {
            color: #ff6600;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .repository-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            Save Checker Admin
            <span class="admin-badge">ADMIN</span>
        </div>
        <div class="user-info">
            <span class="user-name">Admin: <?php echo htmlspecialchars($session['username']); ?></span>
            <a href="?logout=1" class="logout-btn">Sair</a>
        </div>
    </div>
    
    <div class="container">
        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-number" id="onlineCount"><?php echo $onlineUsers; ?></div>
                <div class="stat-label">
                    <span class="online-indicator"></span>
                    Usuários Online
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalUsers; ?></div>
                <div class="stat-label">Total de Usuários</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number" id="liveCardsCount"><?php echo count($liveCards); ?></div>
                <div class="stat-label">Cartões Live Salvos</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number" id="currentTime"><?php echo date('H:i:s'); ?></div>
                <div class="stat-label">Horário do Sistema</div>
            </div>
        </div>
        
        <div class="section">
            <h2 class="section-title">
                🏦 Repositório SaveDes
                <button class="refresh-btn" onclick="refreshRepository()">🔄 Atualizar</button>
            </h2>
            
            <div class="repository-grid" id="repositoryGrid">
                <?php if (empty($liveCards)): ?>
                    <div class="empty-state">
                        Nenhum cartão live encontrado ainda.
                    </div>
                <?php else: ?>
                    <?php foreach ($liveCards as $card): ?>
                        <div class="card-item">
                            <div class="card-number">
                                <?php echo htmlspecialchars($card['card_number']); ?>
                            </div>
                            <div class="card-details">
                                Exp: <?php echo htmlspecialchars($card['exp_month'] . '/' . $card['exp_year']); ?><br>
                                CVV: <?php echo htmlspecialchars($card['cvv']); ?><br>
                                <?php echo htmlspecialchars($card['bin_info']); ?>
                            </div>
                            <div class="card-meta">
                                Salvo em: <?php echo date('d/m/Y H:i:s', strtotime($card['created_at'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="footer">
        Criado por <a href="#">@savefullblack</a> e <a href="#">@tropadoreiofc</a>
    </div>

    <script>
        // Atualizar estatísticas em tempo real
        function updateStats() {
            fetch('get_stats.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('onlineCount').textContent = data.online_users;
                    document.getElementById('liveCardsCount').textContent = data.live_cards_count;
                })
                .catch(error => console.error('Erro ao atualizar estatísticas:', error));
        }
        
        // Atualizar repositório
        function refreshRepository() {
            location.reload();
        }
        
        // Atualizar horário
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('pt-BR');
            document.getElementById('currentTime').textContent = timeString;
        }
        
        // Atualizar estatísticas a cada 30 segundos
        setInterval(updateStats, 30000);
        
        // Atualizar horário a cada segundo
        setInterval(updateTime, 1000);
    </script>
</body>
</html>

