# Design

## Context

See `proposal.md` for motivation and `specs/equipment-responsibility/spec.md` and `specs/portal-visual-theme/spec.md` for required behavior. A listagem de equipamentos já recebe pessoas cadastradas e o responsável atual, mas não possui ação de atualização. As páginas de pessoas e equipamentos usam tokens globais de tema, com um bloco `.dark`, e atualmente combinam fundos de cartão com acentos de cores diferentes.

## Goals / Non-Goals

**Goals:**

- Permitir alterar somente a atribuição de responsável sem criar uma edição geral do equipamento.
- Aplicar aparência clara e consistente às páginas de pessoas e equipamentos, inclusive quando o tema global estiver escuro.

**Non-Goals:**

- Alterar cadastro, remoção ou outros campos de equipamentos e pessoas.
- Modificar a página inicial ou o comportamento global do tema.
- Introduzir migrations ou dependências.

## Decisions

- **Editor acionado na linha do equipamento:** tornar o nome do responsável um controle acessível por teclado que abre uma seleção junto à linha, com salvar e cancelar. Reutilizar a lista ordenada de pessoas já enviada à página e manter um estado de edição independente do formulário de cadastro. Uma opção sem responsável permite remover a atribuição.
- **Endpoint dedicado à atribuição:** acrescentar uma rota pública `PATCH` para atualizar apenas `responsible_person_id` do equipamento identificado. Validar o valor como nulo ou como identificador de pessoa existente; em caso de sucesso, redirecionar de volta à lista para refletir o novo relacionamento. Isso evita expor uma edição mais ampla e mantém a API coerente com o acesso público estabelecido pelo projeto.
- **Tema restrito às páginas de registros:** aplicar tokens claros no escopo dos elementos raiz das páginas, em vez de mudar o bloco `.dark` global. Usar superfícies brancas e neutras, texto escuro, azul claro/institucional para navegação e ações, e acentos pastel suaves sem atribuir uma cor forte diferente a cada página. Assim, a mudança não afeta a home nem outras áreas.
- **Sem migração de dados:** reutilizar a relação nullable já existente entre equipamento e pessoa; a atualização consiste em validar e persistir o identificador selecionado.

## Risks / Trade-offs

- **Acesso público à alteração →** qualquer visitante pode mudar a atribuição, assim como pode usar os cadastros existentes. Manter esse comportamento para o projeto de testes; autenticação não faz parte deste escopo.
- **Seleção de pessoa inválida ou removida →** validar no servidor contra a tabela de pessoas e manter a atribuição existente se a validação falhar.
- **Contraste insuficiente em acentos pastel →** reservar as cores pastel a superfícies e detalhes decorativos; manter textos, foco e ações em tons escuros ou azuis com contraste forte.

## Migration Plan

Não há migração: os dados existentes permanecem intactos. Se necessário, reverter removendo a rota e o fluxo de atualização e restaurando o tema anterior das duas páginas.
