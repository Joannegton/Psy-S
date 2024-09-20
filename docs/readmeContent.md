
# Documentação das Rotas da API

## Base URL
A base para todas as rotas da API é:

```
/conteudo
```

---

### 1. Adicionar Conteúdo

**Rota:** `POST /conteudo`

**Descrição:** Adiciona um novo conteúdo na plataforma.

**Request Body:**
```json
{
  "nome": "Nome do Conteúdo",
  "link": "URL do Conteúdo",
  "tipo": "Tipo do Conteúdo (article, video, meditation)"
}
```

**Response:**
- **201 Created:** Conteúdo adicionado com sucesso.
  ```json
  {
    "message": "Conteúdo adicionado com sucesso!"
  }
  ```
- **400 Bad Request:** Caso algum campo obrigatório esteja faltando ou o tipo seja inválido.
  ```json
  {
    "message": "Dados inválidos. Todos os campos são obrigatórios."
  }
  ```

---

### 2. Obter Conteúdo por ID

**Rota:** `GET /conteudo?id={id}`

**Descrição:** Obtém um conteúdo específico pelo seu ID.

**Parâmetros de Query:**
- `id`: O ID do conteúdo que deseja recuperar.

**Response:**
- **200 OK:** Conteúdo encontrado.
  ```json
  {
    "id": 1,
    "nome": "Nome do Conteúdo",
    "link": "URL do Conteúdo",
    "tipo": "Tipo do Conteúdo"
  }
  ```
- **404 Not Found:** Caso o conteúdo não seja encontrado.
  ```json
  {
    "message": "Conteúdo não encontrado"
  }
  ```
- **400 Bad Request:** Caso o ID não seja informado.
  ```json
  {
    "message": "ID do conteúdo não informado"
  }
  ```

---

### 3. Atualizar Conteúdo

**Rota:** `PUT /conteudo?id={id}`

**Descrição:** Atualiza um conteúdo existente pelo seu ID.

**Parâmetros de Query:**
- `id`: O ID do conteúdo que deseja atualizar.

**Request Body:**
```json
{
  "nome": "Nome do Conteúdo Atualizado",
  "link": "URL do Conteúdo Atualizado",
  "tipo": "Tipo do Conteúdo Atualizado (article, video, meditation)"
}
```

**Response:**
- **200 OK:** Conteúdo atualizado com sucesso.
  ```json
  {
    "message": "Conteúdo atualizado com sucesso!"
  }
  ```
- **400 Bad Request:** Caso algum campo esteja faltando ou o tipo seja inválido.
  ```json
  {
    "message": "Dados inválidos. Todos os campos são obrigatórios."
  }
  ```
- **404 Not Found:** Caso o conteúdo não seja encontrado para atualização.
  ```json
  {
    "message": "Conteúdo não encontrado"
  }
  ```

---

### 4. Deletar Conteúdo

**Rota:** `DELETE /conteudo?id={id}`

**Descrição:** Remove um conteúdo pelo seu ID.

**Parâmetros de Query:**
- `id`: O ID do conteúdo que deseja remover.

**Response:**
- **200 OK:** Conteúdo removido com sucesso.
  ```json
  {
    "message": "Conteúdo deletado com sucesso!"
  }
  ```
- **404 Not Found:** Caso o conteúdo não seja encontrado.
  ```json
  {
    "message": "Conteúdo não encontrado"
  }
  ```

---
