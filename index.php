<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Checker</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            background-color: #1a1a1a;
            border: 2px solid #0ff;
            box-shadow: 0 0 15px #0ff, 0 0 25px #0ff inset;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }
        .neon-border::before, .neon-border::after {
            content: '';
            position: absolute;
            top: -5px; bottom: -5px; left: -5px; right: -5px;
            border-radius: 15px;
            background: linear-gradient(45deg, #0ff, #f0f, #0ff);
            z-index: -1;
            filter: blur(10px);
            opacity: 0.7;
        }
        .neon-border::after {
            filter: blur(20px);
            opacity: 0.5;
            animation: neon-glow 1.5s ease-in-out infinite alternate;
        }
        @keyframes neon-glow {
            from { transform: scale(1); opacity: 0.5; }
            to { transform: scale(1.02); opacity: 0.8; }
        }
        h1 {
            text-align: center;
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
            margin-bottom: 30px;
        }
        textarea {
            width: calc(100% - 20px);
            height: 150px;
            background-color: #333;
            border: 1px solid #0ff;
            color: #fff;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            resize: vertical;
        }
        button {
            background-color: #0ff;
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        button:hover {
            background-color: #f0f;
            box-shadow: 0 0 10px #f0f;
        }
        .results-section {
            margin-top: 30px;
            width: 100%;
        }
        .results-section h2 {
            color: #0ff;
            text-shadow: 0 0 8px #0ff;
            border-bottom: 1px solid #0ff;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .results-section div {
            background-color: #222;
            border: 1px solid #0ff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            max-height: 200px;
            overflow-y: auto;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
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
    <div class="container neon-border">
        <h1>Save Checker</h1>
        <textarea id="cardList" placeholder="Cole suas CCs aqui (ex: 4000000000000000|12|2025|123)"></textarea>
        <button onclick="startChecker()">Iniciar Checker</button>

        <div class="results-section">
            <h2>Lives</h2>
            <div id="liveResults"></div>
        </div>

        <div class="results-section">
            <h2>Dies</h2>
            <div id="dieResults"></div>
        </div>
    </div>

    <div class="footer">
        Criado por <a href="#">@savefullblack</a> e <a href="#">@tropadoreiofc</a>
    </div>

    <script>
        function startChecker() {
            const cardList = document.getElementById('cardList').value.trim();
            const cards = cardList.split('\n').filter(card => card.length > 0);

            if (cards.length === 0) {
                alert('Por favor, insira as CCs.');
                return;
            }

            document.getElementById('liveResults').innerHTML = '';
            document.getElementById('dieResults').innerHTML = '';

            cards.forEach(card => {
                checkCard(card);
            });
        }

        function checkCard(card) {
            // Aqui você faria uma requisição AJAX para o seu checker.php
            // Por enquanto, vamos simular um resultado.
            const isLive = Math.random() > 0.5; // Simula se é live ou die
            const resultDiv = isLive ? document.getElementById('liveResults') : document.getElementById('dieResults');
            const status = isLive ? '✅ LIVE' : '❌ DIE';
            const color = isLive ? 'lightgreen' : 'red';

            const cardElement = document.createElement('p');
            cardElement.style.color = color;
            cardElement.textContent = `${status}: ${card}`;
            resultDiv.prepend(cardElement); // Adiciona no topo

            // Simulação do cartão 3D e texto subindo para LIVE
            if (isLive) {
                const liveText = document.createElement('div');
                liveText.textContent = 'LIVEEEEEE!';
                liveText.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    font-size: 5em;
                    color: #0ff;
                    text-shadow: 0 0 20px #0ff;
                    animation: live-animation 2s forwards;
                    z-index: 1000;
                `;
                document.body.appendChild(liveText);

                const card3D = document.createElement('img');
                card3D.src = 'https://via.placeholder.com/150x90.png?text=CARD+3D'; // Substitua por uma imagem real de cartão 3D
                card3D.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotateY(0deg);
                    animation: card3d-animation 2s forwards;
                    z-index: 1001;
                `;
                document.body.appendChild(card3D);

                setTimeout(() => {
                    document.body.removeChild(liveText);
                    document.body.removeChild(card3D);
                }, 2000);
            }
        }

        @keyframes live-animation {
            0% { opacity: 0; transform: translate(-50%, 0%); }
            50% { opacity: 1; transform: translate(-50%, -50%); }
            100% { opacity: 0; transform: translate(-50%, -100%); }
        }

        @keyframes card3d-animation {
            0% { opacity: 0; transform: translate(-50%, -50%) rotateY(0deg); }
            50% { opacity: 1; transform: translate(-50%, -50%) rotateY(360deg); }
            100% { opacity: 0; transform: translate(-50%, -50%) rotateY(720deg); }
        }
    </script>
</body>
</html>

