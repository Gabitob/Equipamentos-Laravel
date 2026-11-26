# Spec Delta

## Purpose

Permite manter atualizada a atribuição de responsabilidade dos equipamentos cadastrados, com uma interação direta e clara na lista do inventário.

## ADDED Requirements

### Requirement: Alterar responsável de um equipamento

O sistema SHALL permitir que qualquer visitante inicie a edição clicando no nome do responsável exibido e escolha uma pessoa cadastrada para assumir a responsabilidade.

#### Scenario: Selecionar novo responsável

- **WHEN** um visitante clica no nome do responsável e salva a seleção de outra pessoa cadastrada
- **THEN** o sistema persiste a nova atribuição e exibe o nome da pessoa selecionada

#### Scenario: Cancelar a edição

- **WHEN** um visitante inicia a edição do responsável e cancela antes de salvar
- **THEN** o sistema mantém a atribuição anterior sem alterações

### Requirement: Remover responsável de um equipamento

O sistema SHALL permitir que um visitante remova a atribuição atual ao editar o responsável de um equipamento.

#### Scenario: Salvar equipamento sem responsável

- **WHEN** um visitante remove a atribuição e salva a alteração
- **THEN** o sistema persiste o equipamento sem responsável e indica esse estado na lista

### Requirement: Validar pessoa responsável selecionada

O sistema SHALL aceitar somente uma pessoa cadastrada como nova responsável e SHALL manter a atribuição anterior quando a seleção não for válida.

#### Scenario: Seleção de pessoa inexistente

- **WHEN** uma tentativa de alteração envia o identificador de uma pessoa que não está cadastrada
- **THEN** o sistema rejeita a alteração e mantém a atribuição anterior
