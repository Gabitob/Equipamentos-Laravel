# Tasks

## 1. Atualização do responsável no servidor

- [x] 1.1 Criar uma rota e ação `PATCH` dedicadas à atribuição de responsável, validando pessoa existente ou valor nulo; verificar com testes Feature que alteração, remoção, entrada inválida e acesso público preservam os demais dados do equipamento.

## 2. Edição na lista de equipamentos

- [x] 2.1 Tornar o responsável atual um controle acessível que abre a seleção de pessoas cadastradas, com salvar, cancelar, opção sem responsável e apresentação de erros; verificar interação por teclado e mouse, persistência após salvar, cancelamento sem alteração e sucesso de `npm run check` e `npm run types:check`.

## 3. Harmonização visual

- [x] 3.1 Aplicar tokens e acentos claros consistentes, escopados às páginas de pessoas e equipamentos; verificar contraste e hierarquia visual em tela estreita e ampla, inclusive com o tema global escuro ativo, sem alterar a página inicial.

## 4. Integração

- [x] 4.1 Executar os testes Feature pertinentes e `npm run build`; confirmar que a lista continua exibindo a atribuição atualizada e que pesquisa, cadastro e paginação existentes permanecem funcionais.
