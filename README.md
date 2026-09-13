# Sistema de Controle de Consumo de Água

**Prova Final da disciplina de Programação Web 1 — Evolução e Avaliação de uma Aplicação Web em Laravel.**
**Autor:**
Germano de Oliveira Moraes

## Descrição do sistema
O projeto consiste em um sistema web completo para o gerenciamento de consumo de água de associações comunitárias. A aplicação evoluiu a partir de conceitos básicos vistos em sala de aula para uma plataforma robusta, com controle de consumidores, lançamento de leituras mensais de medidores, cálculo automatizado de faturas, e controle de acesso restrito por papéis de usuário (Gestor e Leiturista).

## Objetivo
Evoluir uma aplicação Laravel aplicando conceitos avançados estudados na disciplina, como banco de dados SQLite, ORM Eloquent, relacionamentos, Middlewares de autenticação e autorização (Roles), validação de dados, versionamento com Git e implantação real (deploy) em ambiente de nuvem.

## Credenciais de Acesso (Para Avaliação)
O sistema possui rotas protegidas. Utilize as credenciais abaixo para testar as funcionalidades:

**Acesso de Gestor (Admin):**
- **E-mail:** `gestor@teste.com`
- **Senha:** `12345678`
*(Permissões: Acesso total ao sistema, gerenciar consumidores, alterar configuração de taxas e visualizar faturas).*

**Acesso de Leiturista:**
- **E-mail:** `leiturista@teste.com`
- **Senha:** `12345678`
*(Permissões: Registrar leituras mensais e visualizar faturas).*

## Tecnologias utilizadas
- **PHP 8.2**
- **Laravel 12.x**
- **SQLite** (Banco de dados leve e integrado)
- **Eloquent ORM**
- **Blade & CSS/Tailwind**
- **Git / GitHub Desktop**
- **Render** (Hospedagem em nuvem)

## Funcionalidades principais
- Autenticação e autorização baseada em papéis (Gestor vs Leiturista).
- **CRUD de Consumidores:** Cadastro e edição de moradores (nome, endereço, número do medidor).
- **Leituras:** Lançamento mensal da medição de água de cada consumidor.
- **Faturamento Automatizado:** Geração de fatura com base na leitura atual e nas regras de cobrança.
- **Configuração de Taxas:** Painel exclusivo para o Gestor ajustar a taxa básica e o valor do excedente.

## Regra de Cobrança do Sistema
O cálculo da fatura segue a regra de negócio estabelecida no sistema:
- **Até 10 m³:** Cobrança apenas da **taxa fixa básica** (ex: R$ 25,00 — ajustável pelo Gestor).
- **Acima de 10 m³:** Taxa fixa básica + valor por cada 1 m³ excedente (ex: R$ 2,00 por m³ extra).

## Estrutura do banco de dados e Arquitetura
- **Tabelas principais:** `users`, `consumidores`, `leituras`, `faturas`, `configuracao_taxas`.
- **Relacionamentos:** Um consumidor possui várias leituras (1:N) e várias faturas (1:N). Uma leitura gera uma fatura (1:1).
- **Middlewares:** Implementação de proteção nas rotas (`auth`) e restrição de acesso por nível (`admin`), garantindo que apenas gestores acessem as rotas de taxas e CRUD de consumidores.

## Como executar localmente
```bash
git clone [https://github.com/SEU_USUARIO/SEU_REPOSITORIO.git](https://github.com/SEU_USUARIO/SEU_REPOSITORIO.git)
cd NOME_DA_PASTA
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

## A aplicação estará disponível em `http://localhost:8000`.

## Configuração do banco de dados
O projeto utiliza **SQLite** por padrão (`DB_CONNECTION=sqlite`), dispensando a instalação de servidores externos (como MySQL ou PostgreSQL). O banco é gerado no arquivo local `database/database.sqlite` e as credenciais iniciais são populadas pelo comando `--seed`.

## Testes
Foram realizados testes manuais nas rotas de autenticação, inserção de leitura e geração de faturas para garantir que a lógica de cálculo do excedente funciona corretamente, além de validações de formulário para impedir leituras com valor inferior ao mês anterior.

## Versionamento
O projeto utilizou Git e GitHub Desktop para controle de versão. O histórico comprova a evolução do sistema, incluindo:
* Criação e estruturação inicial.
* Implementação da lógica de autenticação.
* Correção de bugs específicos do ambiente de produção (criação de branch `agents/fix-sqlite-session-error-in-laravel` e posterior merge para a `main`).

## Deploy
* **Serviço utilizado:** Render (Web Service)
* **Endereço da aplicação publicada:** https://agua-evolucao.onrender.com
* **Procedimento de implantação:** O deploy foi automatizado via conexão direta com o GitHub.
* **Dificuldades encontradas e soluções:**
  * O Render apresentou o erro HTTP 500 informando que a tabela `sessions` do SQLite não existia durante o boot. **Solução:** Alteração da variável `SESSION_DRIVER` para `file` para evitar conflito de inicialização do banco.
  * Dificuldade em persistir e gerar o banco no Render. **Solução:** Implementação de um script `entrypoint.sh` executando `php artisan migrate --force` e `--seed` automaticamente no start do container.

## Uso de Inteligência Artificial
Durante o desenvolvimento e implantação, a ferramenta de Inteligência Artificial **Google Gemini** foi utilizada como suporte técnico, principalmente para:
* Diagnóstico e resolução do erro de sessão (`SESSION_DRIVER`) no ambiente do Render.
* Instruções passo a passo para resolução de conflitos e merges de branches no GitHub Desktop.
* Apoio na configuração do script de inicialização do banco de dados em produção.




