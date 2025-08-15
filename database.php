<?php
// Configuração do banco de dados SQLite
class Database {
    private $db_file = __DIR__ . '/savechecker.db';
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO('sqlite:' . $this->db_file);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            die('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
    }

    private function createTables() {
        // Tabela de usuários
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                is_admin INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                last_login DATETIME
            )
        ");

        // Tabela de sessões ativas
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS active_sessions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                session_token VARCHAR(255) UNIQUE,
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                last_activity DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ");

        // Tabela de cartões live (repositório)
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS live_cards (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                card_number VARCHAR(19),
                exp_month VARCHAR(2),
                exp_year VARCHAR(4),
                cvv VARCHAR(4),
                bin_info TEXT,
                user_id INTEGER,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ");

        // Tabela de logs de atividade
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS activity_logs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                action VARCHAR(100),
                details TEXT,
                ip_address VARCHAR(45),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ");

        // Criar usuário admin padrão se não existir
        $this->createDefaultAdmin();
    }

    private function createDefaultAdmin() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute(['save']);
        
        if ($stmt->fetchColumn() == 0) {
            $hashedPassword = password_hash('black', PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 1)");
            $stmt->execute(['save', $hashedPassword]);
        }
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Métodos para autenticação
    public function authenticateUser($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Atualizar último login
            $stmt = $this->pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$user['id']]);
            return $user;
        }
        return false;
    }

    // Criar sessão
    public function createSession($userId, $ipAddress, $userAgent) {
        $sessionToken = bin2hex(random_bytes(32));
        $stmt = $this->pdo->prepare("INSERT INTO active_sessions (user_id, session_token, ip_address, user_agent) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $sessionToken, $ipAddress, $userAgent]);
        return $sessionToken;
    }

    // Validar sessão
    public function validateSession($sessionToken) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, u.username, u.is_admin 
            FROM active_sessions s 
            JOIN users u ON s.user_id = u.id 
            WHERE s.session_token = ? AND s.last_activity > datetime('now', '-24 hours')
        ");
        $stmt->execute([$sessionToken]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($session) {
            // Atualizar última atividade
            $stmt = $this->pdo->prepare("UPDATE active_sessions SET last_activity = CURRENT_TIMESTAMP WHERE session_token = ?");
            $stmt->execute([$sessionToken]);
        }

        return $session;
    }

    // Contar usuários online
    public function getOnlineUsersCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(DISTINCT user_id) FROM active_sessions WHERE last_activity > datetime('now', '-5 minutes')");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Contar total de usuários
    public function getTotalUsersCount() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Salvar cartão live
    public function saveLiveCard($cardNumber, $expMonth, $expYear, $cvv, $binInfo, $userId) {
        $stmt = $this->pdo->prepare("INSERT INTO live_cards (card_number, exp_month, exp_year, cvv, bin_info, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$cardNumber, $expMonth, $expYear, $cvv, $binInfo, $userId]);
    }

    // Obter cartões live
    public function getLiveCards($limit = 100) {
        $stmt = $this->pdo->prepare("SELECT * FROM live_cards ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Log de atividade
    public function logActivity($userId, $action, $details, $ipAddress) {
        $stmt = $this->pdo->prepare("INSERT INTO activity_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userId, $action, $details, $ipAddress]);
    }
}

// Criado por @savefullblack e @tropadoreiofc
?>

