

# MeAjudaPed

MeAjudaPed é uma plataforma web que conecta usuários comuns a pedreiros, facilitando o contato, contratação e acompanhamento de serviços de construção e reforma.

<img width="551" height="920" alt="image" src="https://github.com/user-attachments/assets/d6b92a6e-faf1-43d9-9ac4-651a9d0c84be" />
<img width="542" height="918" alt="image" src="https://github.com/user-attachments/assets/d5f70252-253a-4525-a56c-e53102a93d5d" />
<img width="540" height="919" alt="image" src="https://github.com/user-attachments/assets/2d679b8f-8e1f-4cf9-b02b-bd6922e3d8c1" />


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
