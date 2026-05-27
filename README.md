

# MeAjudaPed

MeAjudaPed é uma plataforma web que conecta usuários comuns a pedreiros, facilitando o contato, contratação e acompanhamento de serviços de construção e reforma.

## Funcionalidades

- **Cadastro e Login**: Usuários e pedreiros podem criar contas e acessar a plataforma.
- **Feed de Trabalhos**: Visualize trabalhos realizados, portfólios e avaliações de pedreiros.
- **Sistema de Seguidores**: Siga pedreiros para acompanhar novidades e novos trabalhos.
- **Busca de Profissionais**: Encontre pedreiros por especialidade, localização ou avaliações.
- **Contato Direto**: Converse e negocie diretamente com os profissionais pela plataforma.
- **Dashboard**: Área exclusiva para usuários e pedreiros acompanharem suas atividades.

## Tecnologias Utilizadas

- **Backend**: Laravel (PHP)
- **Frontend**: Blade, Vite, JavaScript, CSS
- **Banco de Dados**: MySQL ou compatível
- **Docker**: Para ambiente de desenvolvimento
- **Redis**: Para cache e filas

## Como rodar o projeto

1. Clone o repositório:
	```bash
	git clone <repo-url>
	cd meajudaped
	```
2. Instale as dependências PHP e Node.js:
	```bash
	composer install
	npm install
	```
3. Configure o arquivo `.env` com suas variáveis de ambiente.
4. Gere a chave da aplicação:
	```bash
	php artisan key:generate
	```
5. Execute as migrations:
	```bash
	php artisan migrate
	```
6. (Opcional) Suba o ambiente com Docker:
	```bash
	docker-compose up -d
	```
7. Inicie o servidor de desenvolvimento:
	```bash
	php artisan serve
	```
8. Acesse em [http://localhost:8000](http://localhost:8000)

## Estrutura do Projeto

- `app/` - Código principal da aplicação (Controllers, Models, Services)
- `resources/views/` - Templates Blade (frontend)
- `routes/` - Rotas web e console
- `database/` - Migrations, seeders e factories
- `public/` - Arquivos públicos (index.php, assets)
- `config/` - Arquivos de configuração

## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou pull requests.

## Licença

Este projeto está sob a licença MIT.
