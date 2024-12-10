# API Psy: Seu terapeuta virtual

Este documento descreve os endpoints disponíveis para gerenciar as entradas e saidas de dados da API.

### Estrutura Base da API

Todos os endpoints estão disponíveis sob o caminho `/api/v1/`.

## EndPoints Usuários

### 1. Criar Usuário

- **Endpoint:** `
`
- **Método:** `POST`
- **Descrição:** Cria um novo usuário.
- **Parâmetros do Corpo da Requisição:**
    - `nome`: Nome do usuário (obrigatório)
    - `email`: Email do usuário (obrigatório)
    - `senha`: Senha do usuário (obrigatório)
- **Respostas:**
    ```json
    // 201 - Created
    {
      "message": "Usuário criado com sucesso!"
    }
    // 400 - Bad Request
    {
      "message": "Dados inválidos. Todos os campos são obrigatórios."
    }
    // 400 - Bad Request
    {
      "message": "Email inválido."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "error": "Erro ao criar usuário."
    }
    ```

### 2. Listar Usuários

- **Endpoint:** `/api/v1/users/list`
- **Método:** `GET`
- **Descrição:** Lista todos os usuários.
- **Respostas:**
    ```json
    // 200 - OK
    [
      {
        "id": 1,
        "nome": "Nome do Usuário",
        "email": "email@usuario.com"
      }
    ]
    // 404 - Not Found
    {
      "error": "Nenhum usuário encontrado."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```

### 3. Deletar Usuário

- **Endpoint:** `/api/v1/users/delete`
- **Método:** `DELETE`
- **Descrição:** Deleta um usuário.
- **Parâmetros do Corpo da Requisição:**
    - `id_usuario`: ID do usuário (obrigatório)
    - `email`: Email do usuário (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "message": "Usuário deletado com sucesso!"
    }
    // 400 - Bad Request
    {
      "message": "Dados inválidos"
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "message": "Falha ao deletar o usuário"
    }
    ```

### 4. Login de Usuário

- **Endpoint:** `/api/v1/users/login`
- **Método:** `POST`
- **Descrição:** Faz login de um usuário.
- **Parâmetros do Corpo da Requisição:**
    - `email`: Email do usuário (obrigatório)
    - `senha`: Senha do usuário (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "id": 1,
      "nome": "Nome do Usuário",
      "email": "email@usuario.com"
    }
    // 400 - Bad Request
    {
      "message": "Dados inválidos. Todos os campos são obrigatórios."
    }
    // 404 - Not Found
    {
      "error": "Usuário não encontrado."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```


## EndPoints Profissionais

### 1. Criar Profissional

- **Endpoint:** `/api/v1/profissionais/create`
- **Método:** `POST`
- **Descrição:** Cria um novo profissional.
- **Parâmetros do Corpo da Requisição:**
    - `nome`: Nome do profissional (obrigatório)
    - `email`: Email do profissional (obrigatório)
    - `senha`: Senha do profissional (obrigatório)
    - `cpf`: CPF do profissional (obrigatório)
    - `crth`: CRTH do profissional (obrigatório)
- **Respostas:**
    ```json
    // 201 - Created
    {
      "message": "Profissional criado com sucesso!"
    }
    // 400 - Bad Request
    {
      "error": "Todos os campos são obrigatórios."
    }
    // 400 - Bad Request
    {
      "message": "Email inválido."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "error": "Erro ao criar profissional."
    }
    ```

### 2. Listar Profissionais

- **Endpoint:** `/api/v1/profissionais/list`
- **Método:** `GET`
- **Descrição:** Lista todos os profissionais.
- **Respostas:**
    ```json
    // 200 - OK
    [
      {
        "id": 1,
        "nome": "Nome do Profissional",
        "email": "email@profissional.com",
        "cpf": "123.456.789-00",
        "crth": "123456"
      }
    ]
    // 404 - Not Found
    {
      "error": "Nenhum profissional encontrado."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```

### 3. Buscar Profissional

- **Endpoint:** `/api/v1/profissionais/find`
- **Método:** `GET`
- **Descrição:** Busca um profissional pelo ID.
- **Parâmetros de Consulta:**
    - `id`: ID do profissional (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "id": 1,
      "nome": "Nome do Profissional",
      "email": "email@profissional.com",
      "cpf": "123.456.789-00",
      "crth": "123456"
    }
    // 404 - Not Found
    {
      "error": "Profissional não encontrado."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```

### 4. Atualizar Profissional

- **Endpoint:** `/api/v1/profissionais/update`
- **Método:** `PUT`
- **Descrição:** Atualiza um profissional existente.
- **Parâmetros do Corpo da Requisição:**
    - `id_profissional`: ID do profissional (obrigatório)
    - `nome`: Nome do profissional (obrigatório)
    - `senha`: Senha do profissional (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "message": "Profissional atualizado com sucesso!"
    }
    // 400 - Bad Request
    {
      "error": "Todos os campos são obrigatórios."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "error": "Erro ao atualizar profissional."
    }
    ```

### 5. Excluir Profissional

- **Endpoint:** `/api/v1/profissionais/delete`
- **Método:** `DELETE`
- **Descrição:** Exclui um profissional pelo ID.
- **Parâmetros de Consulta:**
    - `id`: ID do profissional (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "message": "Profissional excluído com sucesso!"
    }
    // 404 - Not Found
    {
      "error": "Profissional não encontrado."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "error": "Erro ao excluir profissional."
    }
    ```

### 6. Login de Profissional

- **Endpoint:** `/api/v1/profissionais/login`
- **Método:** `POST`
- **Descrição:** Faz login de um profissional.
- **Parâmetros do Corpo da Requisição:**
    - `email`: Email do profissional (obrigatório)
    - `senha`: Senha do profissional (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "id": 1,
      "nome": "Nome do Profissional",
      "email": "email@profissional.com",
      "cpf": "123.456.789-00",
      "crth": "123456"
    }
    // 400 - Bad Request
    {
      "message": "Dados inválidos. Todos os campos são obrigatórios."
    }
    // 404 - Not Found
    {
      "error": "Profissional não encontrado."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```

## EndPoints Conteúdos

### 1. Adicionar Conteúdo

- **Endpoint:** `/api/v1/conteudos/add`
- **Método:** `POST`
- **Descrição:** Adiciona um novo conteúdo.
- **Parâmetros do Corpo da Requisição:**
    - `nome`: Nome do conteúdo (obrigatório)
    - `link`: Link do conteúdo (obrigatório)
    - `tipo`: Tipo do conteúdo (`article`, `video`, `meditation`) (obrigatório)
- **Respostas:**
    ```json
    // 201 - Created
    {
      "message": "Conteúdo adicionado com sucesso!"
    }
    // 400 - Bad Request
    {
      "message": "Dados inválidos. Todos os campos são obrigatórios."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "message": "Erro ao adicionar conteúdo"
    }
    ```

### 2. Obter Conteúdo

- **Endpoint:** `/api/v1/conteudos/get`
- **Método:** `GET`
- **Descrição:** Obtém um conteúdo pelo ID.
- **Parâmetros de Consulta:**
    - `id`: ID do conteúdo (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "id": 1,
      "nome": "Nome do Conteúdo",
      "link": "http://linkdoconteudo.com",
      "tipo": "article"
    }
    // 400 - Bad Request
    {
      "message": "ID do conteúdo não informado"
    }
    // 404 - Not Found
    {
      "message": "Conteúdo não encontrado"
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```

### 3. Atualizar Conteúdo

- **Endpoint:** `/api/v1/conteudos/update`
- **Método:** `PUT`
- **Descrição:** Atualiza um conteúdo existente.
- **Parâmetros do Corpo da Requisição:**
    - `nome`: Nome do conteúdo (obrigatório)
    - `link`: Link do conteúdo (obrigatório)
    - `tipo`: Tipo do conteúdo (`article`, `video`, `meditation`) (obrigatório)
- **Parâmetros de Consulta:**
    - `id`: ID do conteúdo (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "message": "Conteúdo atualizado com sucesso!"
    }
    // 400 - Bad Request
    {
      "message": "Dados inválidos. Todos os campos são obrigatórios."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "message": "Erro ao atualizar conteúdo"
    }
    ```

### 4. Deletar Conteúdo

- **Endpoint:** `/api/v1/conteudos/delete`
- **Método:** `DELETE`
- **Descrição:** Deleta um conteúdo pelo ID.
- **Parâmetros de Consulta:**
    - `id`: ID do conteúdo (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "message": "Conteúdo deletado com sucesso!"
    }
    // 404 - Not Found
    {
      "message": "Conteúdo não encontrado"
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```

### Tipos de Conteúdo Válidos
- `article`
- `video`
- `meditation`


## EndPoints Interações

### 1. Enviar Mensagem

- **Endpoint:** `/api/v1/interacoes/send`
- **Método:** `POST`
- **Descrição:** Envia uma mensagem para o ChatGPT.
- **Parâmetros do Corpo da Requisição:**
    - `id_usuario`: ID do usuário (obrigatório)
    - `id_terapeuta`: ID do terapeuta (obrigatório)
    - `mensagem`: Texto da mensagem (obrigatório)
- **Respostas:**
    ```json
    // 201 - Created
    {
      "message": "Mensagem enviada com sucesso",
      "resposta": "Resposta do ChatGPT"
    }
    // 400 - Bad Request
    {
      "message": "Dados inválidos"
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "message": "Falha ao enviar a mensagem"
    }
    ```

### 2. Listar Interações

- **Endpoint:** `/api/v1/interacoes/list`
- **Método:** `GET`
- **Descrição:** Lista todas as interações entre um usuário e um terapeuta.
- **Parâmetros de Consulta:**
    - `id_usuario`: ID do usuário (obrigatório)
    - `id_terapeuta`: ID do terapeuta (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    [
      {
        "id": 1,
        "id_usuario": 123,
        "id_terapeuta": 456,
        "mensagem": "Texto da mensagem",
        "data_hora": "2023-10-01T12:00:00Z"
      }
    ]
    // 400 - Bad Request
    {
      "message": "Parâmetros de consulta faltando"
    }
    // 404 - Not Found
    {
      "message": "Nenhuma interação encontrada"
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```

### 3. Deletar Interações

- **Endpoint:** `/api/v1/interacoes/delete`
- **Método:** `DELETE`
- **Descrição:** Deleta todas as interações entre um usuário e um terapeuta.
- **Parâmetros do Corpo da Requisição:**
    - `id_usuario`: ID do usuário (obrigatório)
    - `id_terapeuta`: ID do terapeuta (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "message": "Interações deletadas com sucesso"
    }
    // 400 - Bad Request
    {
      "message": "Dados inválidos"
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "message": "Falha ao deletar as interações"
    }
    ```

## EndPoints Sugestões

### 1. Criar Sugestão

- **Endpoint:** `/sugestao/create`
- **Método:** `POST`
- **Descrição:** Cria uma nova sugestão.
- **Parâmetros do Corpo da Requisição:**
    - `id_usuario`: ID do usuário (obrigatório)
    - `id_profissional`: ID do profissional (obrigatório)
- **Respostas:**
    ```json
    // 201 - Created
    {
      "message": "Sugestão criada com sucesso!"
    }
    // 400 - Bad Request
    {
      "message": "Dados inválidos. Todos os campos são obrigatórios."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "message": "Erro ao criar sugestão"
    }
    ```

### 2. Listar Sugestões

- **Endpoint:** `/sugestao/list`
- **Método:** `GET`
- **Descrição:** Lista todas as sugestões de um usuário.
- **Parâmetros de Consulta:**
    - `id_usuario`: ID do usuário (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    [
      {
        "id": 1,
        "id_usuario": 123,
        "id_profissional": 456,
        "data_sugestao": "01-10-2023"
      }
    ]
    // 400 - Bad Request
    {
      "message": "ID do usuário não informado"
    }
    // 404 - Not Found
    {
      "message": "Nenhuma sugestão encontrada."
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```

### 3. Sugerir Profissionais

- **Endpoint:** `/sugestao/suggest`
- **Método:** `POST`
- **Descrição:** Sugere profissionais para um usuário.
- **Parâmetros do Corpo da Requisição:**
    - `id_usuario`: ID do usuário (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    [
      {
        "id_profissional": 456,
        "nome": "Profissional 1"
      }
    ]
    // 400 - Bad Request
    {
      "message": "ID do usuário não informado"
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    ```

### 4. Deletar Sugestões

- **Endpoint:** `/sugestao/delete`
- **Método:** `DELETE`
- **Descrição:** Deleta todas as sugestões de um usuário.
- **Parâmetros de Consulta:**
    - `id_usuario`: ID do usuário (obrigatório)
- **Respostas:**
    ```json
    // 200 - OK
    {
      "message": "Sugestões deletadas com sucesso"
    }
    // 400 - Bad Request
    {
      "message": "ID do usuário não informado"
    }
    // 405 - Method Not Allowed
    {
      "message": "Método não permitido"
    }
    // 500 - Internal Server Error
    {
      "message": "Erro ao deletar sugestões"
    }
    ```