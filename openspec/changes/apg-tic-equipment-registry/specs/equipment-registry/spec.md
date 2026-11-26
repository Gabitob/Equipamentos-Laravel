# Spec Delta

## Purpose

Permite manter um inventário consultável dos equipamentos do APG/TIC e identificar a pessoa responsável por cada item quando houver uma atribuição.

## ADDED Requirements

### Requirement: Cadastro de equipamentos

O sistema SHALL permitir que qualquer visitante registre um equipamento com nome, marca e descrição de seu estado atual, podendo selecionar uma pessoa cadastrada como responsável.

#### Scenario: Registrar equipamento com responsável

- **WHEN** um visitante envia nome, marca, descrição do estado atual e uma pessoa responsável existente
- **THEN** o sistema armazena o equipamento e confirma o cadastro

#### Scenario: Registrar equipamento sem responsável

- **WHEN** um visitante envia os dados do equipamento sem selecionar uma pessoa responsável
- **THEN** o sistema armazena o equipamento sem atribuição de responsável

### Requirement: Consulta da lista de equipamentos

O sistema SHALL exibir os equipamentos cadastrados com nome, marca, descrição do estado atual e pessoa responsável, quando atribuída.

#### Scenario: Consultar equipamentos cadastrados

- **WHEN** um visitante abre a área de equipamentos
- **THEN** o sistema exibe os equipamentos registrados e identifica como sem responsável aqueles que não possuem atribuição

### Requirement: Pesquisa de equipamentos

O sistema SHALL permitir pesquisar equipamentos cadastrados por nome, marca, descrição do estado atual ou nome da pessoa responsável.

#### Scenario: Encontrar equipamentos correspondentes

- **WHEN** um visitante pesquisa um termo que corresponde a um campo pesquisável
- **THEN** a lista exibe os equipamentos correspondentes ao termo

#### Scenario: Pesquisa sem resultados

- **WHEN** um visitante pesquisa um termo sem correspondências
- **THEN** a lista informa que nenhum equipamento foi encontrado
