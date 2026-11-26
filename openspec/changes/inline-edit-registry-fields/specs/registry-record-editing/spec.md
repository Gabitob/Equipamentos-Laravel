# Spec Delta

## Purpose

Permite corrigir os dados dos cadastros diretamente nas listas, sem abandonar a consulta dos registros e mantendo as validações existentes.

## ADDED Requirements

### Requirement: Editar campos exibidos de uma pessoa

O sistema SHALL permitir que qualquer visitante clique em um valor exibido de uma pessoa e edite somente esse campo usando um controle apropriado.

#### Scenario: Salvar um campo da pessoa

- **WHEN** um visitante edita e salva o nome, telefone, e-mail ou idade de uma pessoa
- **THEN** o sistema persiste o novo valor e exibe a alteração na lista sem modificar os demais campos

#### Scenario: Cancelar a edição de pessoa

- **WHEN** um visitante inicia a edição de um campo de pessoa e cancela
- **THEN** o sistema descarta o valor editado e mantém os dados salvos

### Requirement: Editar campos exibidos de um equipamento

O sistema SHALL permitir que qualquer visitante clique em um valor exibido do equipamento e edite somente esse campo, preservando as demais informações.

#### Scenario: Salvar um campo do equipamento

- **WHEN** um visitante edita e salva o nome, a marca ou a descrição do estado atual
- **THEN** o sistema persiste o novo valor e o exibe na lista

#### Scenario: Cancelar a edição de equipamento

- **WHEN** um visitante inicia a edição de um desses campos e cancela
- **THEN** o sistema descarta o valor editado e mantém os dados salvos

### Requirement: Editar o responsável pela lista de equipamentos

O sistema SHALL permitir iniciar a edição da atribuição clicando no nome atual ou no indicador de ausência de responsável e escolher uma pessoa cadastrada ou a opção sem responsável.

#### Scenario: Atribuir pessoa a equipamento sem responsável

- **WHEN** um visitante clica em “Sem responsável”, seleciona uma pessoa cadastrada e salva
- **THEN** o sistema persiste a atribuição e exibe o nome selecionado

#### Scenario: Trocar ou remover o responsável

- **WHEN** um visitante edita o nome do responsável e salva outra pessoa ou a opção sem responsável
- **THEN** o sistema persiste a nova atribuição e atualiza o valor exibido

### Requirement: Validar alterações inline

O sistema SHALL aplicar as regras de validação existentes e informar erros sem substituir o valor previamente salvo.

#### Scenario: Valor editado inválido

- **WHEN** um visitante salva um campo com valor inválido
- **THEN** o sistema rejeita a alteração, informa o erro no campo e mantém o dado salvo inalterado
