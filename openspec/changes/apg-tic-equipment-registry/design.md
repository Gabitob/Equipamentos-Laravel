# Design

## Contexto

Consulte `proposal.md` para a motivação e `specs/` para os comportamentos exigidos. O projeto é um starter kit Laravel 13 com Vue 3, Inertia e TypeScript. A rota inicial atual renderiza `Welcome`; `dashboard` usa `auth` e `verified`. Não há modelos ou migrations de pessoas e equipamentos. O `.env.example` configura SQLite, apesar do PostgreSQL ser o banco-alvo solicitado.

## Objetivos / Fora do escopo

**Objetivos:**

- Integrar as telas e a persistência ao padrão Laravel/Inertia existente sem acrescentar dependências de frontend.
- Manter a página inicial e os cadastros acessíveis sem autenticação para apoiar o uso de teste solicitado.
- Suportar PostgreSQL como banco de aplicação e preservar testes automatizados para os comportamentos introduzidos.

**Fora do escopo:**

- Edição, exclusão, importação ou exportação de registros.
- Gestão de permissões além dos middlewares de autenticação e verificação existentes.
- Coletar data de nascimento; o cadastro usa o campo idade solicitado.

## Decisões

1. **Usar Laravel para rotas, validação e persistência; Vue/Inertia para a experiência.** Isso segue o stack já instalado e evita introduzir uma API separada ou outro framework Node. A página inicial existente será adaptada, e as áreas de pessoas e equipamentos serão páginas Inertia públicas.
2. **Criar entidades relacionais próprias para pessoas e equipamentos.** Equipamentos referenciam a pessoa responsável por uma chave estrangeira opcional; a relação permite listar o nome da pessoa sem duplicar seus dados. Ao remover uma pessoa no futuro, a referência deve poder ficar nula, sem apagar o equipamento. Alternativa considerada: armazenar os dados da pessoa como texto em cada equipamento, rejeitada por permitir divergência.
3. **Executar pesquisa no servidor e apresentar resultados paginados.** Os filtros devem cobrir os campos definidos nas especificações e manter os termos na navegação Inertia. Isso evita carregar a lista inteira no navegador à medida que o inventário cresce. A lista inicia sem filtro; não é necessário um serviço externo de busca.
4. **Configurar PostgreSQL como alvo explícito de execução.** Atualizar o exemplo de ambiente para `pgsql` e os parâmetros correspondentes, sem alterar credenciais locais. Testes podem continuar usando uma base isolada, mas devem validar migrations e consultas contra PostgreSQL no ambiente de integração/deploy.
5. **Manter o estilo dentro dos recursos existentes.** Usar componentes Vue, ícones e utilitários Tailwind já presentes; definir cores institucionais como tokens/classes consistentes e assegurar contraste. Não adicionar biblioteca visual ou dependência de CSS.
6. **Manter o acesso público explicitamente limitado ao protótipo de teste.** As páginas públicas permitem os testes sem contas; validar os campos no servidor e retornar à tela somente os dados necessários. Não usar dados pessoais reais neste ambiente.

## Riscos / Trade-offs

- [O PostgreSQL pode não estar disponível em todos os ambientes de desenvolvimento] → Documentar as variáveis exigidas no `.env.example` e manter os testes locais isolados; validar migrations e consultas em PostgreSQL antes de publicar.
- [Telefone, e-mail e idade são dados pessoais e os registros serão públicos] → Usar somente dados sintéticos/de teste; antes de qualquer uso real, adicionar controle de acesso e revisar privacidade.
- [Paleta institucional com tons pastel pode reduzir contraste] → Validar contraste dos textos, estados e controles, usando cores mais escuras para elementos interativos quando necessário.
- [A associação opcional pode deixar equipamentos sem responsável] → Apresentar claramente o estado “sem responsável” na listagem; a seleção permanece disponível no cadastro.

## Plano de migração

Configurar o banco PostgreSQL no ambiente, aplicar as migrations aditivas de pessoas e equipamentos antes de publicar as páginas e então disponibilizar a navegação. As tabelas novas não exigem conversão de registros existentes. Para reversão da aplicação, restaurar a versão anterior e manter as tabelas; só reverter as migrations após backup e confirmação de que os dados novos podem ser descartados.
