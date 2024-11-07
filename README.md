# Administrador de Metas Pessoais

## Descrição
O projeto **Administrador de Metas Pessoais** é uma aplicação que permite aos usuários organizar e acompanhar suas metas, promovendo maior produtividade e foco em seus objetivos. Com uma interface simples e intuitiva, o sistema possibilita o gerenciamento eficaz de metas, desde a definição até o acompanhamento do progresso.

## Proposta
A proposta é fornecer uma ferramenta prática e de fácil acesso para pessoas que desejam monitorar suas metas, sejam elas profissionais, acadêmicas ou pessoais. Ao facilitar o acompanhamento das metas, o aplicativo ajuda o usuário a manter o foco e a alcançar seus objetivos com mais eficiência.

## Recursos Principais
- **Criação de Metas:** Adicione e edite metas pessoais de acordo com suas necessidades.
- **Acompanhamento de Progresso:** Marque metas como "em progresso" ou "concluídas" para acompanhar seu desenvolvimento.
- **Filtros de Visualização:** Visualize metas por status (concluídas, em andamento, etc.) para uma melhor organização.

## Público-Alvo
O público-alvo do projeto são pessoas que desejam manter controle sobre suas metas e que buscam melhorar sua organização pessoal. Isso inclui estudantes, profissionais e qualquer pessoa interessada em gerenciar suas tarefas e objetivos de forma prática.

## Tecnologias Utilizadas
- **Backend:** PHP, PostgreSQL
- **Frontend:** HTML, CSS, JavaScript 

php -S localhost:8000 -t public

![image](https://github.com/user-attachments/assets/95b7d0b7-f8e7-4eba-912a-b2355982c648)


##
  "GET" => [
    "/metas" => fn() => load("RelatorioDeMetasDoUsuario", "listarMetas"),
  ],
  ##
  "POST" => [
    "/usuarios" => fn() => load("CadastroDeUsuario", "CadastroUsuario"),
    "/metas" => fn() => load("CadastroDeMetas", "CadastroMeta"),
    "/usuarioLogin" => fn() => load("LoginUsuario", "validaLogin")
  ],
  "PUT" => [
##
  ],
  "DELETE" => [
    "/metas" => fn() => load("DeletarMeta", "deletar"),
  ]
];
