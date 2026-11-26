# Proposta

## Por quê

O APG/TIC do Governo da Bahia precisa de um sistema claro e profissional para registrar equipamentos, acompanhar sua condição e identificar as pessoas responsáveis, além de manter um cadastro pesquisável de pessoas. A aplicação Laravel existente ainda é um starter kit e não oferece esses cadastros; a nova experiência deve comunicar o contexto institucional e facilitar a consulta dos registros.

## O que muda

- Criar uma página inicial institucional que apresente o sistema de registro e controle de equipamentos do APG/TIC.
- Permitir cadastrar e pesquisar equipamentos por nome, marca, descrição do estado atual e pessoa responsável.
- Permitir cadastrar e pesquisar pessoas por nome, telefone, e-mail e idade, para uso como responsáveis pelos equipamentos.
- Permitir que qualquer visitante acesse as páginas de cadastro e consulta, sem exigir login, conforme o uso do projeto para testes.
- Aplicar uma identidade visual profissional inspirada no Governo da Bahia, com azul claro e tons pastel de verde, rosa e amarelo, mantendo contraste e legibilidade.
- Persistir os cadastros no PostgreSQL. O exemplo de configuração atual do projeto usa SQLite, então a implementação deverá alinhar a configuração de ambiente e os testes ao banco-alvo.

## Capacidades

### Novas capacidades

- `institutional-home`: página inicial institucional e identidade visual do portal APG/TIC.
- `equipment-registry`: cadastro, consulta e pesquisa de equipamentos, incluindo a associação com uma pessoa responsável.
- `people-registry`: cadastro e pesquisa de pessoas que podem ser selecionadas como responsáveis.

### Capacidades modificadas

- Nenhuma.

## Impacto

Afeta a página inicial e as rotas públicas do starter kit Laravel 13 com Vue 3, Inertia e TypeScript; acrescenta telas, validação, persistência, migrations e testes para pessoas e equipamentos. O banco-alvo é PostgreSQL, embora `.env.example` atualmente declare SQLite. A página inicial e as áreas de cadastro e consulta serão públicas, sem exigência de autenticação, para permitir testes por qualquer visitante. Para esta primeira entrega, presume-se cadastro, listagem e pesquisa; edição e exclusão não foram solicitadas e ficam fora do escopo.
