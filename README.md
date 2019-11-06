# Teste AlhambraIT

## Sobre
- Aplicação consiste num formulário de cadastro de informações simples, com validações, utilizando LARAVEL 6.2 no back-end e JS no front.

## Instalação
- Criar um database num servidor mysql
- Acesse a pasta do projeto
- Configurar o arquivo .env, copiando o exemplo na pasta raiz do projeto, e o colocando também na pasta raiz, com os dados do banco criado:
```
DB_DATABASE=NOME_DO_BANCO_DE_DADOS
DB_USERNAME=NOME_DO_USUARIO_DO_BANCO
DB_PASSWORD=SENHA_DO_USUARIO_DO_BANCO
```

- Após isso recomendo executar os comandos abaixo, para limpar a cache do projeto:
```
composer dump-autoload
php artisan optimize
```

- Em seguida execute as migrações:
```
php artisan migrate
```

- Peço que seja a importação do arquivo sql (cidades_estados.sql) que se encontra na pasta raiz do projeto, contendo as cidades e estados do país, esses dados são utilizados no formulário:

- Agora só executar o server de desenvolvimento caso não esteja num diretório de servidor apache:
```
php artisan serve
```

