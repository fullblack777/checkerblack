# Save Checker

Sistema avançado de verificação de cartões com interface web moderna, sistema de autenticação e painel administrativo.

## 🚀 Características

- **Interface Moderna**: Design com fundo preto e bordas neon
- **Sistema de Login**: Autenticação segura com sessões
- **Separação de Resultados**: Lives e Dies organizados separadamente
- **Verificação em Lote**: Suporte para múltiplos cartões simultaneamente
- **Painel Administrativo**: Monitoramento de usuários e repositório
- **Efeitos Visuais**: Animações 3D e texto "LIVEEEEEE!" para cartões aprovados
- **Repositório SaveDes**: Armazenamento automático de cartões live

## 🔐 Credenciais de Administrador

- **Usuário**: `save`
- **Senha**: `black`

## 📁 Estrutura do Projeto

```
savechecker/
├── public_html/           # Arquivos web públicos
│   ├── index.html        # Página inicial com efeitos
│   ├── login.php         # Sistema de login
│   ├── checker.php       # Interface principal do checker
│   ├── admin.php         # Painel administrativo
│   ├── auth.php          # Sistema de autenticação
│   ├── save_live_card.php # API para salvar cartões live
│   └── get_stats.php     # API para estatísticas em tempo real
├── api/                  # APIs do sistema
│   └── checker.php       # API de verificação de cartões
├── database/             # Sistema de banco de dados
│   ├── database.php      # Configuração e métodos do banco
│   └── savechecker.db    # Banco SQLite (criado automaticamente)
└── README.md            # Este arquivo
```

## 🛠️ Instalação

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/seu-usuario/savechecker.git
   cd savechecker
   ```

2. **Configure o servidor web**:
   - Aponte o DocumentRoot para a pasta `public_html/`
   - Certifique-se de que o PHP está habilitado
   - Permissões de escrita na pasta `database/`

3. **Acesse o sistema**:
   - Abra `http://seu-dominio/` no navegador
   - Faça login com as credenciais de administrador

## 🎯 Funcionalidades

### Para Usuários
- **Checker de Cartões**: Interface intuitiva para verificação
- **Resultados em Tempo Real**: Separação automática de lives e dies
- **Efeitos Visuais**: Animações especiais para cartões aprovados
- **Estatísticas**: Contadores de sucesso e taxa de aprovação

### Para Administradores
- **Painel de Controle**: Visão geral do sistema
- **Monitoramento**: Usuários online e estatísticas totais
- **Repositório SaveDes**: Visualização de todos os cartões live
- **Logs de Atividade**: Rastreamento de ações dos usuários

## 🎨 Design

- **Tema**: Fundo preto com bordas neon (ciano e magenta)
- **Responsivo**: Compatível com desktop e mobile
- **Animações**: Efeitos suaves e transições modernas
- **UX/UI**: Interface intuitiva e profissional

## 🔧 Tecnologias

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.4+
- **Banco de Dados**: SQLite
- **Autenticação**: Sistema de sessões seguro
- **API**: RESTful endpoints

## 📊 Recursos do Painel Admin

- Contagem de usuários online em tempo real
- Total de usuários registrados
- Repositório de cartões live salvos
- Estatísticas atualizadas automaticamente
- Interface administrativa dedicada

## 🚀 Deploy

O sistema está pronto para hospedagem no GitHub Pages ou qualquer servidor que suporte PHP.

### GitHub Pages (apenas frontend)
```bash
git add .
git commit -m "Deploy Save Checker"
git push origin main
```

### Servidor PHP
1. Faça upload dos arquivos para o servidor
2. Configure as permissões da pasta `database/`
3. Acesse via navegador

## 🔒 Segurança

- Senhas criptografadas com hash
- Validação de sessões
- Proteção contra SQL injection
- Logs de atividade para auditoria

## 👥 Créditos

Criado por **@savefullblack** e **@tropadoreiofc**

## 📝 Licença

Este projeto é de uso privado e não possui licença pública.

---

**Save Checker** - Sistema Avançado de Verificação 🔍

