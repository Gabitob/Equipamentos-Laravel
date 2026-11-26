# Tasks

## 1. Atualizações parciais no servidor

- [x] 1.1 Adicionar atualização pública de um único campo de pessoa, com allowlist e validações equivalentes ao cadastro; cobrir em `PeopleRegistryTest` os quatro campos, preservação dos demais valores e rejeição de e-mail/idade inválidos.
- [x] 1.2 Adicionar atualização pública de nome, marca e descrição de equipamento com validação restrita aos campos permitidos; cobrir em `EquipmentRegistryTest` valores válidos, rejeição de valores inválidos e preservação dos demais campos e da relação de responsável.

## 2. Edição inline de pessoas

- [x] 2.1 Tornar nome, telefone, e-mail e idade acionáveis na lista e permitir editar um campo por vez com controle apropriado, salvar/cancelar e mensagens de erro; verificar uso por teclado e mouse, preservação da pesquisa/paginação e sucesso de `npm run check` e `npm run types:check`.

## 3. Edição inline de equipamentos

- [x] 3.1 Tornar nome, marca e descrição acionáveis na lista e manter a edição do responsável, permitindo também clicar em “Sem responsável” para atribuir uma pessoa; verificar salvar/cancelar, validação, acessibilidade e sucesso de `npm run check` e `npm run types:check`.

## 4. Integração

- [x] 4.1 Executar `php artisan test` e `npm run build`; confirmar que edição, cadastro, pesquisa e paginação de pessoas e equipamentos continuam funcionando.
