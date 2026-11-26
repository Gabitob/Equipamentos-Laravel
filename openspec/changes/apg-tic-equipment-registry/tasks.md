# Tarefas

## 1. Persistência e cadastro de pessoas

- [x] 1.1 Criar migration e modelo de pessoas com nome, telefone, e-mail e idade; verificar criação e reversão da tabela com as migrations do projeto.
- [x] 1.2 Implementar rotas públicas, validação, cadastro, listagem paginada e pesquisa de pessoas; verificar com testes de feature para acesso, dados inválidos, cadastro e filtros.
- [x] 1.3 Atualizar `.env.example` para documentar a conexão PostgreSQL sem incluir credenciais; verificar que as variáveis correspondem à configuração Laravel existente.

## 2. Cadastro e pesquisa de equipamentos

- [x] 2.1 Criar migration e modelo de equipamentos com relação opcional à pessoa responsável; verificar a integridade da chave estrangeira e o comportamento sem responsável nos testes de feature.
- [x] 2.2 Implementar rotas públicas, validação, cadastro, listagem paginada e pesquisa de equipamentos pelos campos especificados; verificar os cenários das especificações com testes de feature.
- [x] 2.3 Disponibilizar pessoas cadastradas para seleção no formulário de equipamento; verificar que uma pessoa existente pode ser atribuída e que o valor em branco continua permitido.

## 3. Páginas e experiência institucional

- [x] 3.1 Adaptar a página inicial pública para apresentar o APG/TIC e ligar às áreas de equipamentos e pessoas; verificar o conteúdo e o acesso anônimo em teste de feature.
- [x] 3.2 Implementar as páginas Vue/Inertia de pessoas e equipamentos com formulários, listas e pesquisas; verificar os estados de validação, resultados e ausência de resultados nos testes adequados.
- [x] 3.3 Aplicar a identidade visual institucional responsiva usando os estilos e componentes existentes; verificar `npm run types:check` e `npm run check`.

## 4. Integração e validação

- [x] 4.1 Executar as migrations e os testes de cadastro e pesquisa contra PostgreSQL configurado, além da suíte de feature aplicável; confirmar que a página inicial e os cadastros são acessíveis sem autenticação.
- [x] 4.2 Executar `composer lint:check`, `composer types:check` e `npm run build`; corrigir erros introduzidos pela mudança e confirmar a compilação final.
