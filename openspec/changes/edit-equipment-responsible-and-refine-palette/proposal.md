# Proposal

## Why

Atualmente, a lista de equipamentos apenas exibe o responsável e não permite atualizar essa atribuição. Além disso, as páginas de pessoas e equipamentos precisam de uma apresentação mais clara e de uma paleta institucional mais harmoniosa.

## What Changes

- Permitir iniciar a alteração do responsável clicando no nome exibido no equipamento, escolher outra pessoa cadastrada e salvar ou cancelar a alteração.
- Permitir deixar um equipamento sem responsável durante essa edição.
- Clarear e harmonizar as cores das páginas de pessoas e equipamentos, mantendo uma aparência profissional inspirada no Governo da Bahia, com azul claro e acentos pastel discretos.
- Preservar contraste, legibilidade, responsividade e o acesso público já estabelecido.

## Capabilities

### New Capabilities

- `equipment-responsibility`: alteração e remoção da atribuição de responsável de um equipamento existente.
- `portal-visual-theme`: paleta clara e coesa aplicada às páginas de pessoas e equipamentos.

### Modified Capabilities

- Nenhuma. Não há especificações principais em `openspec/specs/`; as capacidades anteriores estão em uma change concluída, mas ainda não arquivada.

## Impact

Afeta a listagem de equipamentos em Vue/Inertia, o controlador e as rotas públicas Laravel, a validação e os testes da atribuição de responsável, além dos estilos das páginas de pessoas e equipamentos. A edição deve persistir no relacionamento existente com pessoas; não inclui alteração dos demais dados do equipamento. Como detalhe de interação, a seleção será apresentada junto ao nome, com ações explícitas para salvar ou cancelar e uma opção para remover a atribuição. A paleta priorizará fundos claros e azul como cor principal, usando verde, rosa e amarelo pastel com moderação; a página inicial fica fora do escopo.
