# 🚚 Sistema de Gestão de Transportadora e Logística

Sistema CRUD desenvolvido em PHP e MySQL para gerenciamento de entregas, motoristas, veículos e rotas — projeto acadêmico desenvolvido em grupo nas disciplinas de Engenharia de Software e Linguagem PHP, Centro Universitário Fundação Santo André (2026).

---

## 📋 Sobre o Projeto

O sistema simula a gestão operacional de uma transportadora, permitindo o controle completo de entregas, frota e ocorrências por um administrador autenticado.
Fui responsável pela implementação do backend em PHP, modelagem do banco de dados MySQL, integração com servidor EC2 (Amazon) e estruturação do repositório.

---

## ✅ Funcionalidades

- Autenticação com sessão e bloqueio de cache (impede acesso via botão "Voltar" após logout)
- Dashboard com contadores em tempo real por status de entrega
- CRUD completo de **Motoristas**, **Veículos**, **Rotas** e **Entregas**
- Atribuição de motorista e rota a cada entrega
- Controle de manutenção periódica de veículos com alerta visual
- Status de entrega: `Pendente`, `Em rota`, `Entregue`, `Não entregue`, `Estabelecimento Fechado`
- Registro de ocorrências vinculadas a cada entrega
- Filtros de listagem por status

---

## 🛠 Tecnologias

- PHP 8 + MySQLi
- MySQL (hospedado em EC2 - Amazon)
- Bootstrap 5.3
- Bootstrap Icons
- JavaScript (controle de sessão/cache)
- HTML5 / CSS3
- XAMPP (ambiente local)
- Git / GitHub

---

## 🖼 Preview

| Login | Dashboard |
|---|---|
| ![Login](assets/screenshots/login.png) | ![Dashboard](assets/screenshots/dashboard.png) |

| Entregas | Ocorrências |
|---|---|
| ![Entregas](assets/screenshots/entregas.png) | ![Ocorrências](assets/screenshots/ocorrencias.png) |

---

## 🚀 Como executar localmente

### Pré-requisitos
- XAMPP (ou qualquer servidor com PHP 8+ e MySQL)
- MySQL Workbench (opcional, para visualizar o banco)

### Passo a passo

**1. Clone o repositório**
```bash
git clone https://github.com/leo-gouvea/transportadora-logistica-crud.git
```

**2. Configure a conexão com o banco**

Renomeie o arquivo de exemplo:
```bash
cp conexao.exemplo.php conexao.php
```

Edite o `conexao.php` com suas credenciais:
```php
define('HOST', 'seu-host');
define('USUARIO', 'seu-usuario');
define('SENHA', 'sua-senha');
define('DB', 'seu-banco');
```

**3. Importe o banco de dados**

No MySQL Workbench (ou phpMyAdmin), importe o arquivo:
```
database/schema.sql
```

**4. Acesse no navegador**
```
http://localhost/transportadora-logistica-crud/login.php
```

---

## 🔑 Credenciais de demonstração

> ⚠️ Ambiente acadêmico — dados fictícios para fins de demonstração.

| Campo | Valor |
|---|---|
| E-mail | `admin@transportadora.com` |
| Senha | `admin123` |

---

## 🗄 Estrutura do Banco

```
usuarios        → autenticação do sistema
motoristas      → cadastro de motoristas e vínculo com veículo
veiculos        → frota com controle de manutenção
rotas           → origem, destino e distância
entregas        → core do sistema, vincula motorista + veículo + rota
ocorrencias     → registro de eventos por entrega
```

---

## 📁 Estrutura de Arquivos

```
/
├── assets/
│   └── screenshots/        → imagens do README
├── database/
│   └── schema.sql          → script completo de criação e dados
├── docs/
│   └── ...                 → documentação e diagramas UML
├── conexao.exemplo.php     → modelo de configuração (sem credenciais)
├── login.php
├── logout.php
├── index.php               → dashboard
├── navbar.php
├── mensagem.php
├── acoes.php               → processamento central (CREATE/UPDATE/DELETE)
├── motoristas.php
├── motorista-create.php
├── motorista-edit.php
├── motorista-view.php
├── veiculos.php
├── veiculo-create.php
├── veiculo-edit.php
├── veiculo-view.php
├── entregas.php
├── entrega-create.php
├── entrega-edit.php
├── entrega-view.php
├── rotas.php
├── rota-create.php
├── rota-edit.php
├── rota-view.php
└── ocorrencias.php
```

---

## 📐 Diagramas UML

Os diagramas de Casos de Uso e Sequência estão disponíveis na pasta `/docs`.

---

## ⚠️ Nota de segurança

Este é um projeto acadêmico desenvolvido com foco em aprendizado. Para um ambiente de produção real, seriam implementados:

- Prepared statements (PDO) no lugar de `mysqli_real_escape_string()`
- Rate limiting no login para prevenir força bruta
- Variáveis de ambiente para credenciais (`.env`)
- HTTPS obrigatório
- Validação de tipos no servidor além do front-end

---

## 👥 Grupo

Projeto desenvolvido por 8 integrantes — Turma 3B/Noturno, Grupo 4  
Engenharia de Software, Linguagem PHP — Fundação Santo André, 2026