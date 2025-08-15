<?php
require_once 'auth.php';
$session = checkAuth();
require_once '../database/database.php';

$db = new Database();

if (isset($_GET['logout'])) {
    logout();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Checker - Dashboard</title>
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
            overflow-x: hidden;
        }
        
        .header {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #0ff;
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
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-name {
            color: #0ff;
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .checker-section {
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid #0ff;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .checker-section::before {
            content: '';
            position: absolute;
            top: -2px; bottom: -2px; left: -2px; right: -2px;
            background: linear-gradient(45deg, #0ff, #f0f, #0ff);
            border-radius: 17px;
            z-index: -1;
            filter: blur(8px);
            opacity: 0.6;
            animation: border-glow 3s ease-in-out infinite alternate;
        }
        
        @keyframes border-glow {
            from { opacity: 0.4; }
            to { opacity: 0.8; }
        }
        
        .section-title {
            color: #0ff;
            font-size: 1.5em;
            margin-bottom: 20px;
            text-shadow: 0 0 10px #0ff;
        }
        
        .input-area {
            width: 100%;
            height: 150px;
            background: rgba(51, 51, 51, 0.8);
            border: 2px solid #0ff;
            border-radius: 10px;
            color: #fff;
            padding: 15px;
            font-size: 14px;
            resize: vertical;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .input-area:focus {
            outline: none;
            border-color: #f0f;
            box-shadow: 0 0 15px rgba(255, 0, 255, 0.5);
        }
        
        .controls {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .btn {
            background: linear-gradient(45deg, #0ff, #f0f);
            color: #000;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 255, 255, 0.4);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-stop {
            background: linear-gradient(45deg, #ff0033, #ff6666);
            color: white;
        }
        
        .btn-clear {
            background: linear-gradient(45deg, #666, #999);
            color: white;
        }
        
        .results-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }
        
        .result-section {
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid;
            border-radius: 10px;
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .live-section {
            border-color: #00ff00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.3);
        }
        
        .die-section {
            border-color: #ff0033;
            box-shadow: 0 0 15px rgba(255, 0, 51, 0.3);
        }
        
        .result-header {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .live-section .result-header {
            color: #00ff00;
            text-shadow: 0 0 10px #00ff00;
        }
        
        .die-section .result-header {
            color: #ff0033;
            text-shadow: 0 0 10px #ff0033;
        }
        
        .card-result {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 8px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            border-left: 4px solid;
            animation: slideIn 0.5s ease-out;
        }
        
        .card-live {
            border-left-color: #00ff00;
            color: #00ff00;
        }
        
        .card-die {
            border-left-color: #ff0033;
            color: #ff0033;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid #0ff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
        }
        
        .stat-label {
            color: #aaa;
            margin-top: 5px;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #666;
            border-top: 1px solid #333;
        }
        
        .footer a {
            color: #0ff;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        /* Animações para cartão 3D e texto LIVE */
        .live-animation {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            pointer-events: none;
        }
        
        .live-text {
            font-size: 4em;
            font-weight: bold;
            color: #00ff00;
            text-shadow: 0 0 30px #00ff00;
            animation: liveTextAnimation 2s forwards;
        }
        
        .card-3d {
            width: 200px;
            height: 120px;
            background: linear-gradient(45deg, #0ff, #f0f);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #000;
            animation: card3DAnimation 2s forwards;
            margin-top: 20px;
        }
        
        @keyframes liveTextAnimation {
            0% { opacity: 0; transform: translate(-50%, 100px); }
            50% { opacity: 1; transform: translate(-50%, -50px); }
            100% { opacity: 0; transform: translate(-50%, -200px); }
        }
        
        @keyframes card3DAnimation {
            0% { opacity: 0; transform: rotateY(0deg) scale(0.5); }
            50% { opacity: 1; transform: rotateY(180deg) scale(1); }
            100% { opacity: 0; transform: rotateY(360deg) scale(0.5); }
        }
        
        @media (max-width: 768px) {
            .results-grid {
                grid-template-columns: 1fr;
            }
            
            .controls {
                flex-direction: column;
            }
            
            .stats {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Save Checker</div>
        <div class="user-info">
            <span class="user-name">Bem-vindo, <?php echo htmlspecialchars($session['username']); ?>!</span>
            <a href="?logout=1" class="logout-btn">Sair</a>
        </div>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-item">
                <div class="stat-number" id="liveCount">0</div>
                <div class="stat-label">Lives</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="dieCount">0</div>
                <div class="stat-label">Dies</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="totalCount">0</div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="successRate">0%</div>
                <div class="stat-label">Taxa de Sucesso</div>
            </div>
        </div>
        
        <div class="checker-section">
            <h2 class="section-title">🔍 Checker de Cartões</h2>
            <textarea 
                id="cardList" 
                class="input-area" 
                placeholder="Cole suas CCs aqui, uma por linha:&#10;4000000000000000|12|2025|123&#10;4111111111111111|01|2026|456&#10;..."></textarea>
            
            <div class="controls">
                <button class="btn" onclick="startChecker()">🚀 Iniciar Checker</button>
                <button class="btn btn-stop" onclick="stopChecker()" id="stopBtn" style="display: none;">⏹️ Parar</button>
                <button class="btn btn-clear" onclick="clearResults()">🗑️ Limpar Resultados</button>
            </div>
        </div>
        
        <div class="results-grid">
            <div class="result-section live-section">
                <div class="result-header">✅ LIVES (<span id="liveCounter">0</span>)</div>
                <div id="liveResults"></div>
            </div>
            
            <div class="result-section die-section">
                <div class="result-header">❌ DIES (<span id="dieCounter">0</span>)</div>
                <div id="dieResults"></div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        Criado por <a href="#">@savefullblack</a> e <a href="#">@tropadoreiofc</a>
    </div>

    <script>
        let isChecking = false;
        let liveCount = 0;
        let dieCount = 0;
        let totalCount = 0;
        let currentIndex = 0;
        let cards = [];

        function startChecker() {
            const cardList = document.getElementById('cardList').value.trim();
            cards = cardList.split('\n').filter(card => card.trim().length > 0);

            if (cards.length === 0) {
                alert('Por favor, insira as CCs.');
                return;
            }

            isChecking = true;
            currentIndex = 0;
            document.getElementById('stopBtn').style.display = 'inline-block';
            document.querySelector('.btn').style.display = 'none';
            
            checkNextCard();
        }

        function stopChecker() {
            isChecking = false;
            document.getElementById('stopBtn').style.display = 'none';
            document.querySelector('.btn').style.display = 'inline-block';
        }

        function checkNextCard() {
            if (!isChecking || currentIndex >= cards.length) {
                stopChecker();
                return;
            }

            const card = cards[currentIndex].trim();
            currentIndex++;

            // Fazer requisição AJAX para o checker
            fetch('../api/checker.php?lista=' + encodeURIComponent(card))
                .then(response => response.text())
                .then(result => {
                    processResult(card, result);
                    
                    // Continuar com o próximo cartão após um pequeno delay
                    if (isChecking) {
                        setTimeout(checkNextCard, 1000);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    addResult(card, false, 'Erro na verificação');
                    
                    if (isChecking) {
                        setTimeout(checkNextCard, 1000);
                    }
                });
        }

        function processResult(card, result) {
            const isLive = result.includes('✅') || result.includes('Aprovada') || result.includes('success":true');
            addResult(card, isLive, result);
            
            if (isLive) {
                showLiveAnimation();
                // Salvar no repositório
                saveLiveCard(card);
            }
        }

        function addResult(card, isLive, fullResult) {
            const resultDiv = isLive ? document.getElementById('liveResults') : document.getElementById('dieResults');
            const cardElement = document.createElement('div');
            cardElement.className = isLive ? 'card-result card-live' : 'card-result card-die';
            
            const status = isLive ? '✅ LIVE' : '❌ DIE';
            cardElement.innerHTML = `<strong>${status}</strong><br>${card}<br><small>${new Date().toLocaleTimeString()}</small>`;
            
            resultDiv.insertBefore(cardElement, resultDiv.firstChild);
            
            // Atualizar contadores
            if (isLive) {
                liveCount++;
                document.getElementById('liveCounter').textContent = liveCount;
            } else {
                dieCount++;
                document.getElementById('dieCounter').textContent = dieCount;
            }
            
            totalCount++;
            updateStats();
        }

        function updateStats() {
            document.getElementById('liveCount').textContent = liveCount;
            document.getElementById('dieCount').textContent = dieCount;
            document.getElementById('totalCount').textContent = totalCount;
            
            const successRate = totalCount > 0 ? ((liveCount / totalCount) * 100).toFixed(1) : 0;
            document.getElementById('successRate').textContent = successRate + '%';
        }

        function showLiveAnimation() {
            const animationDiv = document.createElement('div');
            animationDiv.className = 'live-animation';
            
            const liveText = document.createElement('div');
            liveText.className = 'live-text';
            liveText.textContent = 'LIVEEEEEE!';
            
            const card3D = document.createElement('div');
            card3D.className = 'card-3d';
            card3D.textContent = 'CARD 3D';
            
            animationDiv.appendChild(liveText);
            animationDiv.appendChild(card3D);
            document.body.appendChild(animationDiv);
            
            setTimeout(() => {
                document.body.removeChild(animationDiv);
            }, 2000);
        }

        function saveLiveCard(card) {
            const parts = card.split('|');
            if (parts.length >= 4) {
                fetch('save_live_card.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `card=${encodeURIComponent(card)}`
                });
            }
        }

        function clearResults() {
            document.getElementById('liveResults').innerHTML = '';
            document.getElementById('dieResults').innerHTML = '';
            liveCount = 0;
            dieCount = 0;
            totalCount = 0;
            updateStats();
            document.getElementById('liveCounter').textContent = '0';
            document.getElementById('dieCounter').textContent = '0';
        }
    </script>
</body>
</html>

