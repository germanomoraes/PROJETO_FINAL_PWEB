# Deploy no Render

Este projeto usa PHP embutido (`php artisan serve`) dentro de um container
Docker, com PostgreSQL gerenciado pelo próprio Render (Render não oferece
MySQL nativo no plano gratuito).

## Passo a passo

### 1. Coloque estes 3 arquivos na raiz do repositório
- `Dockerfile`
- `docker-entrypoint.sh`
- `render.yaml` (opcional, só se for usar o modo Blueprint)

Confirme que o `.gitattributes`/Git não vai remover a permissão de
execução do `docker-entrypoint.sh`. Se necessário, rode:
```bash
git update-index --chmod=+x docker-entrypoint.sh
```

### 2. Ajuste o `config/database.php` (se ainda não tiver `pgsql`)
Um `config/database.php` padrão do Laravel já vem com a conexão `pgsql`
configurada — normalmente não precisa mexer em nada, só usar
`DB_CONNECTION=pgsql` nas variáveis de ambiente (passo 5).

### 3. Crie o banco no Render
`New +` → `PostgreSQL` → escolha o plano Free → dê o nome
`agua-evolucao-db` (ou outro, mas troque no `render.yaml` também) →
anote os dados de conexão (host, porta, database, usuário, senha) que
aparecem na página do banco.

### 4. Crie o Web Service
`New +` → `Web Service` → conecte o repositório
`germanomoraes/AVALIACAO-PRATICA-PWEB` → Runtime: **Docker** → Plan: Free.

Se preferir o modo automático, commite o `render.yaml` e use
`New +` → `Blueprint` em vez dos passos 3 e 4 separados — o Render lê o
arquivo e cria banco + serviço junto.

### 5. Configure as variáveis de ambiente do Web Service
| Variável | Valor |
|---|---|
| `APP_NAME` | Sistema de Controle de Consumo de Água |
| `APP_ENV` | production |
| `APP_DEBUG` | false |
| `APP_URL` | a URL que o Render vai gerar (ex: `https://agua-evolucao.onrender.com`) — pode deixar em branco no primeiro deploy e completar depois |
| `APP_KEY` | gere localmente com `php artisan key:generate --show` e cole o valor (ex: `base64:...`) |
| `DB_CONNECTION` | pgsql |
| `DB_HOST` / `DB_PORT` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | copie da página do banco criado no passo 3 |

### 6. Deploy
O Render builda a imagem a partir do `Dockerfile` automaticamente. O
`docker-entrypoint.sh` já roda `php artisan migrate --force` a cada
deploy, então as tabelas são criadas sozinhas no primeiro boot — não
precisa rodar migration manual via terminal.

### 7. Teste e atualize a documentação
Acesse a URL gerada, teste login, cadastro de consumidor, leitura,
fatura e o dashboard. Depois, atualize:
- `README.md` → seção "Deploy": troque a URL do Railway pela do Render;
- `relatorio-tecnico.docx/pdf` → seção 12 (Implantação e deploy): troque
  "Railway" por "Render" e cole a URL final e a evidência de
  funcionamento (print da aplicação publicada).

## Observação sobre o plano gratuito do Render
No plano Free, o Web Service "dorme" após ~15 minutos sem tráfego e o
banco Postgres Free expira após 90 dias — é normal para fins de
avaliação, mas vale mencionar essa limitação no relatório, se quiser,
como algo a melhorar em produção real.
