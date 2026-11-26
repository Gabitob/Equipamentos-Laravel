# Spec Delta

## Purpose

Mantém um cadastro consultável de pessoas que podem ser associadas como responsáveis por equipamentos do APG/TIC.

## ADDED Requirements

### Requirement: Cadastro de pessoas

O sistema SHALL permitir que qualquer visitante registre uma pessoa com nome, contato telefônico, e-mail e idade.

#### Scenario: Registrar pessoa

- **WHEN** um visitante envia nome, contato telefônico, e-mail e idade válidos
- **THEN** o sistema armazena a pessoa e confirma o cadastro

#### Scenario: Dados inválidos

- **WHEN** um visitante envia dados incompletos ou inválidos para uma pessoa
- **THEN** o sistema rejeita o cadastro e informa os campos que precisam ser corrigidos

### Requirement: Consulta da lista de pessoas

O sistema SHALL exibir as pessoas cadastradas com nome, contato telefônico, e-mail e idade.

#### Scenario: Consultar pessoas cadastradas

- **WHEN** um visitante abre a área de pessoas
- **THEN** o sistema exibe os dados das pessoas registradas

### Requirement: Pesquisa de pessoas

O sistema SHALL permitir pesquisar pessoas cadastradas por nome, contato telefônico ou e-mail.

#### Scenario: Encontrar pessoas correspondentes

- **WHEN** um visitante pesquisa um termo que corresponde a um campo pesquisável
- **THEN** a lista exibe as pessoas correspondentes ao termo

#### Scenario: Pesquisa sem resultados

- **WHEN** um visitante pesquisa um termo sem correspondências
- **THEN** a lista informa que nenhuma pessoa foi encontrada
