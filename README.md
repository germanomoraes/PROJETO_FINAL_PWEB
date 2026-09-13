# Sistema de Controle de Consumo de Água

Sistema web completo para gerenciamento de consumo de água de associações comunitárias, com controle de consumidores, lançamento de leituras mensais de medidores, cálculo automatizado de faturas, dashboard de acompanhamento e controle de acesso por papel de usuário (**Gestor** e **Leiturista**).

---

## 🛠️ Tecnologias Utilizadas

- **PHP 8.2**
- **Laravel 12.x**
- **PostgreSQL 16** (banco de dados em desenvolvimento e produção)
- **Docker & Docker Compose** (ambiente conteinerizado)
- **Blade & CSS puro** (interface leve e responsiva)
- **PHPUnit** (testes automatizados)
- **Render** (hospedagem em nuvem)

---

## 🚀 Como Rodar o Projeto Localmente (Docker Compose)

Você **não precisa ter PHP, Composer ou PostgreSQL instalados** na sua máquina física. Apenas o **Docker Desktop** é necessário.

### 1. Iniciar os containers
Na raiz do projeto, execute no terminal:

```bash
docker compose up -d
```
*(Se você alterou arquivos de código PHP e quer reconstruir a imagem, utilize `docker compose up -d --build`)*

### 2. Acessar a aplicação
- Abra o navegador em: **`http://localhost:8000`**
- As migrações do banco de dados e a criação dos usuários de teste são executadas **automaticamente** no primeiro boot.

### 3. Dados persistidos
- Todos os dados gerados pelo PostgreSQL e pela aplicação ficam salvos na pasta local **`./data/`** (já inclusa no `.gitignore`).
- Nenhum volume nomeado interno do Docker é criado.

### 4. Parar os containers
Quando quiser encerrar o ambiente:

```bash
docker compose down
```

---

## 🔑 Usuários de Teste

O banco de dados é populado automaticamente na primeira execução com duas contas de teste:

| Papel | E-mail | Senha | Permissões |
|---|---|---|---|
| **Gestor** | `gestor@teste.com` | `12345678` | Acesso total: alterar taxa fixa/excedente, dar baixa em faturas e dashboard. |
| **Leiturista** | `leiturista@teste.com` | `12345678` | Cadastrar consumidores, registrar leituras mensais e visualizar faturas. |

---

## ☁️ Como Fazer Deploy no RENDER (Passo a Passo)

O Render conecta-se diretamente ao seu repositório do **GitHub** e faz o build e deploy automáticos do container.

### Passo 1: Subir o código para o GitHub

1. Se ainda não criou o repositório no GitHub, acesse [github.com/new](https://github.com/new) e crie um novo repositório (ex: `consumo-agua`).
2. No terminal do seu computador (na pasta do projeto), rode os comandos:

```bash
# Adiciona todos os arquivos
git add .

# Faz o commit das alterações
git commit -m "feat: configuracao docker e suporte postgres para render"

# Garante a permissão de execução do script de boot para Linux/Render
git update-index --chmod=+x docker-entrypoint.sh

# Conecta ao repositório criado no GitHub:
git remote add origin https://github.com/germanomoraes/PROJETO_FINAL_PWEB.git

# Envia o código para a branch master
git branch -M master
git push -u origin master
```

---

### Passo 2: Fazer o Deploy no Render

Acesse [dashboard.render.com](https://dashboard.render.com) e entre com sua conta (pode fazer login com o GitHub).

#### Método 1: Automático via Blueprint (Recomendado - 1 Clique)
Como este projeto já possui o arquivo [`render.yaml`](render.yaml) configurado, o Render cria o Banco PostgreSQL e o Web Service conectados sozinhos:

1. No painel do Render, clique no botão superior **`New +`** e escolha **`Blueprint`**.
2. Selecione o repositório do GitHub que você acabou de subir (`PROJETO_FINAL_PWEB`).
3. O Render vai ler o arquivo `render.yaml` e mostrar os dois recursos que serão criados:
   - **`agua-evolucao`** (Web Service Docker)
   - **`agua-evolucao-db`** (PostgreSQL gratuito)
4. Clique no botão **`Apply`**.
5. O Render provisionará o banco de dados e construirá a imagem Docker automaticamente.
6. Ao finalizar, o link público da sua aplicação (ex: `https://agua-evolucao.onrender.com`) será exibido no topo da página.

---

#### Método 2: Manual (Caso prefira criar serviço por serviço)

Caso não queira usar o Blueprint, faça manualmente em 2 etapas:

**1. Criar o Banco de Dados:**
- Clique em **`New +`** → **`PostgreSQL`**.
- Dê o nome: `agua-db`.
- Escolha o plano **Free**.
- Clique em **`Create Database`**.
- Copie os dados de conexão que aparecem na tela: Host, Database, Username e Password.

**2. Criar a Aplicação Web:**
- Clique em **`New +`** → **`Web Service`**.
- Conecte o repositório do GitHub.
- Em **Runtime**, escolha **Docker**.
- Em **Plan**, escolha **Free**.
- Role até a seção **Environment Variables** e adicione as variáveis:
  - `APP_NAME`: `Sistema de Controle de Consumo de Agua`
  - `APP_ENV`: `production`
  - `APP_DEBUG`: `false`
  - `APP_KEY`: Gere uma chave ou cole uma (ex: `base64:...`)
  - `DB_CONNECTION`: `pgsql`
  - `DB_HOST`: Host do banco criado no passo anterior
  - `DB_PORT`: `5432`
  - `DB_DATABASE`: Nome do banco do passo anterior
  - `DB_USERNAME`: Usuário do banco
  - `DB_PASSWORD`: Senha do banco
- Clique em **`Create Web Service`**.

---

### ℹ️ Observações sobre o Plano Gratuito do Render:
- **Modo de Hibernação:** Se a aplicação ficar sem acessos por mais de 15 minutos, o Render suspende temporariamente o container para economizar recursos. O primeiro acesso após esse período pode levar cerca de 30 a 50 segundos para "acordar" o PHP.
- **Banco de Dados Gratuito:** O banco PostgreSQL no plano Free do Render expira após 90 dias, o que é ideal para testes, provas e apresentações de projetos acadêmicos.

---

## 💧 Regra de Cobrança do Sistema

| Consumo mensal no medidor | Forma de cobrança |
|---|---|
| **Até 10 m³ (10.000 L)** | **Taxa fixa básica** (padrão: R$ 25,00 — ajustável pelo Gestor). |
| **Acima de 10 m³** | Taxa fixa básica + **R$ 2,00** por cada 1.000 L (m³) excedentes. |

> **Exemplo:** Morador com consumo de **15 m³**:
> - Franquia (10 m³): R$ 25,00
> - Excedente (5 m³ × R$ 2,00): R$ 10,00
> - **Total da fatura:** R$ 35,00

---

## 📱 Funcionalidades

- **Consumidores:** Cadastro e edição de moradores (nome, endereço, número do medidor único e telefone).
- **Leituras:** Lançamento da leitura atual com validação (impede valor menor que o anterior e duplicação no mesmo mês). O consumo em m³ é calculado automaticamente.
- **Faturas:** Geração automática da fatura no ato da leitura, com filtro por mês/ano, botão de confirmação de pagamento e link direto para envio de cobrança via **WhatsApp**.
- **Dashboard:** Visão consolidada para o Gestor de total faturado, consumo global, faturas pagas e pendentes.
- **Configuração de Taxa:** Painel exclusivo para o Gestor alterar os parâmetros de cálculo (taxa fixa, limite franqueado e valor excedente).
