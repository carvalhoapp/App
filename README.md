# Sistema de Inscrições em Cursos (PHP + MySQL + Tailwind CSS)

Sistema completo para cadastro de cursos e inscrições por formulário, com painel administrativo estilo Google Forms (campos extras por curso).

## Requisitos

- PHP 8.1+
- MySQL 8+
- Servidor web (Apache/Nginx) ou `php -S`

## Instalação rápida

1. Configure banco e credenciais no arquivo `config.php`.
2. Execute o SQL inicial:

```bash
mysql -u root -p < init.sql
```

3. Suba a aplicação:

```bash
php -S localhost:8000
```

4. Acesse:
- Site público: `http://localhost:8000`
- Painel: `http://localhost:8000/admin/login.php`

## Login do painel

- Usuário: `admin`
- Senha: `admin123`

Altere em `config.php` antes de usar em produção.

## Funcionalidades

- Cadastro de cursos com: título, cidade, descrição, valor.
- Ativar/desativar cursos.
- Cadastro de inputs extras por curso (texto, número, data, seleção, textarea).
- Formulário padrão por curso com:
  - Nome
  - Data de Nascimento
  - Sexo
  - RG
  - CPF
  - WhatsApp
  - Formação
  - Endereço
  - Cidade
  - Tamanho da camisa (P/M/G/GG)
  - Foto 3x4 com recorte (CropperJS)
  - Pagamento (pix, boleto, cartão, dinheiro)
  - Declaração de aceite
- Redirecionamento para WhatsApp configurado no painel ao finalizar inscrição.
- Visualização de inscritos por curso.

## Estrutura principal

- `index.php` lista cursos ativos
- `curso.php` formulário do curso
- `submit.php` grava inscrição e redireciona ao WhatsApp
- `admin/` painel administrativo
- `init.sql` estrutura do banco

