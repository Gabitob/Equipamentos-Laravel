# Proposal

## Why

As listas de pessoas e equipamentos permitem consultar os registros, mas não corrigir seus dados diretamente. Exigir outro fluxo para alterar informações como nome, idade, e-mail ou marca torna a manutenção dos cadastros menos rápida e intuitiva.

## What Changes

- Permitir iniciar a edição ao clicar em qualquer valor exibido nas listas de pessoas e equipamentos.
- Editar um campo por vez com um controle adequado ao dado, ações explícitas para salvar ou cancelar e retorno claro de erros de validação.
- Persistir as alterações nos registros existentes, mantendo as regras de validação e o acesso público já estabelecido.
- Incluir os campos visíveis de pessoas (nome, telefone, e-mail e idade) e equipamentos (nome, marca, descrição do estado e responsável); não incluir identificadores ou dados que não são exibidos nas listas.

## Capabilities

### New Capabilities

- `registry-record-editing`: edição inline, campo a campo, dos dados exibidos nos cadastros de pessoas e equipamentos.

### Modified Capabilities

- Nenhuma. Não há specs principais em `openspec/specs/`; as capabilities dos cadastros e de edição do responsável estão em changes anteriores ainda não arquivadas.

## Impact

Afeta as páginas Vue/Inertia das listas, as rotas e controladores Laravel de pessoas e equipamentos, a validação e os testes Feature existentes. A atualização deve preservar os demais campos e relações do registro, apresentar erros sem substituir os valores salvos e manter a navegação, pesquisa e paginação atuais. Não altera cadastro, remoção, autenticação ou o tema visual.
