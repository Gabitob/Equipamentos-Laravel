# Design

## Context

See `proposal.md` for motivation and `specs/registry-record-editing/spec.md` for behavior. As listas Inertia já recebem os dados paginados e os controladores validam os campos no cadastro. A atribuição de responsável já tem uma rota `PATCH` própria; as demais informações não possuem atualização.

## Goals / Non-Goals

**Goals:**

- Atualizar um único campo por ação, sem reenviar nem sobrescrever os outros campos do registro.
- Reutilizar os formatos e limites de validação já praticados no cadastro.
- Integrar a edição do estado “Sem responsável” à seleção de responsável já existente.

**Non-Goals:**

- Criar telas de edição completas, cadastro ou remoção de registros.
- Alterar banco de dados, autenticação ou o tema visual.
- Tornar editáveis campos que não aparecem nas listas.

## Decisions

- **Um campo por submissão:** cada valor de tabela acionará um editor inline próprio, com controle adequado ao dado e ações explícitas para salvar ou cancelar. A edição por campo reduz o risco de uma alteração apagar valores não relacionados; a alternativa de colocar a linha inteira em modo de edição foi descartada por ser menos direta para o pedido de clicar em um valor específico.
- **PATCH restrito ao campo permitido:** adicionar atualização parcial para atributos editáveis de pessoas e para nome, marca e descrição do estado do equipamento. Validar o nome do campo contra uma allowlist e validar o valor com as mesmas regras do cadastro. Manter a rota exclusiva `equipment.responsible.update` para a relação responsável, incluindo o caso nullable; isso evita misturar uma relação com os campos simples.
- **Atualização local com Inertia:** enviar a alteração sem perder posição, pesquisa ou página atual, e atualizar a linha após sucesso. Em validação rejeitada, manter o editor aberto com o valor digitado e a mensagem de erro; cancelar descarta o valor local não salvo.
- **Sem migração:** os dados e relações existentes suportam os valores solicitados; nenhuma mudança de esquema é necessária.

## Risks / Trade-offs

- **Atualização pública →** visitantes podem editar dados dos registros, pois as páginas já são públicas para testes. Preservar essa decisão e restringir cada endpoint aos campos previstos, sem ampliar permissões nem permitir mass assignment.
- **Edição simultânea do mesmo campo →** a última submissão válida prevalece. O sistema não possui controle de concorrência por versão; acrescentá-lo está fora do escopo.
- **Validação divergente entre cadastro e edição →** manter regras equivalentes nos dois fluxos e cobrir formatos inválidos em testes Feature.

## Migration Plan

Não há migração de dados. A reversão consiste em retirar os endpoints e os controles inline, preservando todos os registros existentes.
