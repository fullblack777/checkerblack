# 🚀 Instruções de Deploy - Save Checker

## Para GitHub Pages (Frontend apenas)

1. **Criar repositório no GitHub**:
   ```bash
   # No GitHub, crie um novo repositório chamado "savechecker"
   ```

2. **Conectar repositório local**:
   ```bash
   git remote add origin https://github.com/SEU-USUARIO/savechecker.git
   git branch -M main
   git push -u origin main
   ```

3. **Configurar GitHub Pages**:
   - Vá para Settings > Pages
   - Source: Deploy from a branch
   - Branch: main
   - Folder: / (root)

## Para Servidor PHP Completo

### Opção 1: Hospedagem Gratuita (InfinityFree, 000webhost, etc.)

1. **Fazer upload dos arquivos**:
   - Compacte a pasta `savechecker`
   - Faça upload via File Manager
   - Extraia na pasta `public_html` do seu hosting

2. **Configurar permissões**:
   ```bash
   chmod 755 database/
   chmod 666 database/database.php
   ```

3. **Acessar o sistema**:
   - `http://seu-dominio.com/`

### Opção 2: VPS/Servidor Próprio

1. **Instalar dependências**:
   ```bash
   sudo apt update
   sudo apt install apache2 php php-sqlite3
   ```

2. **Configurar Apache**:
   ```bash
   sudo cp -r savechecker /var/www/html/
   sudo chown -R www-data:www-data /var/www/html/savechecker
   sudo chmod 755 /var/www/html/savechecker/database
   ```

3. **Configurar Virtual Host** (opcional):
   ```apache
   <VirtualHost *:80>
       ServerName savechecker.local
       DocumentRoot /var/www/html/savechecker/public_html
       
       <Directory /var/www/html/savechecker/public_html>
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

## Estrutura para Upload

```
Pasta raiz do servidor/
├── public_html/          # Conteúdo web (PÚBLICO)
│   ├── index.html
│   ├── login.php
│   ├── checker.php
│   ├── admin.php
│   └── ...
├── api/                  # APIs (PROTEGIDO)
│   └── checker.php
├── database/             # Banco de dados (PROTEGIDO)
│   ├── database.php
│   └── savechecker.db
└── README.md
```

## ⚠️ Importante para Segurança

1. **Proteger pastas sensíveis**:
   ```apache
   # .htaccess na pasta database/
   Deny from all
   
   # .htaccess na pasta api/
   <Files "*.php">
       Order allow,deny
       Allow from all
   </Files>
   ```

2. **Configurar HTTPS** (recomendado):
   - Use Let's Encrypt ou certificado SSL
   - Force redirecionamento HTTP → HTTPS

## 🔧 Configurações Pós-Deploy

1. **Testar funcionalidades**:
   - [ ] Login de administrador (save/black)
   - [ ] Criação de usuários
   - [ ] Checker de cartões
   - [ ] Painel administrativo
   - [ ] Repositório SaveDes

2. **Monitoramento**:
   - Verificar logs de erro do servidor
   - Monitorar uso do banco de dados
   - Backup regular do arquivo `savechecker.db`

## 📱 URLs do Sistema

- **Página Inicial**: `/`
- **Login**: `/login.php`
- **Checker**: `/checker.php`
- **Admin**: `/admin.php`
- **API Checker**: `/api/checker.php?lista=CARD|MM|YYYY|CVV`

## 🆘 Solução de Problemas

### Erro de Permissão no Banco
```bash
chmod 666 database/savechecker.db
chown www-data:www-data database/
```

### Erro 500 - Internal Server Error
- Verificar logs do Apache: `/var/log/apache2/error.log`
- Verificar sintaxe PHP: `php -l arquivo.php`

### Banco não cria automaticamente
- Verificar permissões da pasta `database/`
- Executar manualmente: `php database/database.php`

---

**Save Checker** está pronto para deploy! 🎉

Criado por **@savefullblack** e **@tropadoreiofc**

