# Documentação do SisNote v1

## Introdução
O **SisNote v1** é um sistema de gerenciamento de notas e compromissos que permite aos usuários armazenar e organizar suas anotações e compromissos de maneira eficiente. Possui funcionalidades como cadastro de usuários, login, criação e exclusão de notas, além de um calendário para compromissos.

## Requisitos do Sistema
- Servidor web (Apache ou Nginx)
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Composer (se necessário para gerenciamento de dependências)

## Estrutura do Projeto
```
/sisnote-v1/
│── admin/                   # Módulo administrativo
│── css/                     # Estilos do sistema
│── includes/                # Arquivos de inclusão reutilizáveis
│── uploads/                 # Diretório de uploads e backups do banco de dados
│── db.php                   # Configuração da conexão com o banco de dados
│── index.php                # Página inicial
│── login.php                # Página de login
│── cadastro.php             # Página de cadastro
│── notas.php                # Gerenciamento de notas
│── compromissos.php         # Gerenciamento de compromissos
│── painel.php               # Painel de controle
│── logout.php               # Logout do sistema
│── save_event.php           # Salva compromissos no banco
│── fetch_events.php         # Busca eventos para exibição no calendário
```

## Configuração do Banco de Dados
### 1. Criando o Banco de Dados
```sql
CREATE DATABASE sisnote;
```

### 2. Importação da Estrutura
No diretório `uploads/`, há backups do banco de dados no formato `.sql`. Para importar:
```sh
mysql -u seu_usuario -p sisnote < sisnote.sql
```

### 3. Configuração do Arquivo `db.php`
Edite o arquivo `db.php` e configure os dados de acesso ao MySQL:
```php
<?php
$host = 'localhost';
$usuario = 'root'; // Altere conforme necessário
$senha = ''; // Altere conforme necessário
$banco = 'sisnote';

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}
?>
```

## Instalação e Configuração
1. Envie os arquivos do projeto para o servidor.
2. Configure o banco de dados conforme as instruções acima.
3. Acesse o sistema no navegador: `http://seu-servidor/sisnote-v1`
4. Crie um usuário para acessar o sistema.

## Módulo Administrativo
O painel administrativo pode ser acessado via:
- `http://seu-servidor/sisnote-v1/admin/admin.php`
- O login do administrador deve ser criado no banco de dados manualmente.

## Funcionalidades Principais
- Cadastro e login de usuários
- Gerenciamento de notas (criação, edição e exclusão)
- Calendário de compromissos
- Painel administrativo para gerenciar usuários

## Segurança
- Modifique as credenciais padrão do banco de dados para maior segurança.
- Restrinja o acesso ao diretório `admin/` com autenticação adicional.

## Conclusão
O **SisNote v1** é uma solução simples e eficiente para gerenciar notas e compromissos. Ele pode ser personalizado conforme suas necessidades alterando os arquivos PHP e CSS.

contato: retononcode@gmail.com
