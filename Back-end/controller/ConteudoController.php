<?php
require_once __DIR__ . '/../config/config.php'; 

class ContentController
{
    private $pdo;

    /**
     * Construtor da classe ContentController.
     * 
     * @param PDO $pdo Instância do PDO para conexão com o banco de dados.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Adiciona um novo conteúdo.
     * 
     * Responde com um código 201 e uma mensagem de sucesso se o conteúdo for adicionado com sucesso,
     * ou um código 400 se os dados forem inválidos.
     */
    public function addAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nome']) || empty($data['link']) || empty($data['tipo'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos. Todos os campos são obrigatórios.']);
            return;
        }

        $type = $this->sanitizeType($data['tipo']);
        
        if (!$type) {
            http_response_code(400);
            echo json_encode(['message' => 'Tipo de conteúdo inválido']);
            return;
        }

        $stmt = $this->pdo->prepare("INSERT INTO conteudos (nome, link, tipo) VALUES (?, ?, ?)");
        $result = $stmt->execute([$data['nome'], $data['link'], $type]);

        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Conteúdo adicionado com sucesso']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Erro ao adicionar o conteúdo']);
        }
    }

    /**
     * Obtém um conteúdo pelo ID.
     * 
     * Responde com um código 200 e os dados do conteúdo se encontrado,
     * ou um código 404 se o conteúdo não for encontrado.
     */
    public function getAction(int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'GET') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM conteudos WHERE id = ?");
        $stmt->execute([$id]);
        $content = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($content) {
            http_response_code(200);
            echo json_encode($content);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Conteúdo não encontrado']);
        }
    }

    /**
     * Atualiza um conteúdo existente.
     * 
     * Responde com um código 200 e uma mensagem de sucesso se o conteúdo for atualizado com sucesso,
     * ou um código 400 se os dados forem inválidos.
     */
    public function updateAction(int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nome']) || empty($data['link']) || empty($data['tipo'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos. Todos os campos são obrigatórios.']);
            return;
        }

        $type = $this->sanitizeType($data['tipo']);
        
        if (!$type) {
            http_response_code(400);
            echo json_encode(['message' => 'Tipo de conteúdo inválido']);
            return;
        }

        $stmt = $this->pdo->prepare("UPDATE conteudos SET nome = ?, link = ?, tipo = ? WHERE id = ?");
        $result = $stmt->execute([$data['nome'], $data['link'], $type, $id]);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Conteúdo atualizado com sucesso']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Erro ao atualizar o conteúdo']);
        }
    }

    /**
     * Deleta um conteúdo pelo ID.
     * 
     * Responde com um código 200 e uma mensagem de sucesso se o conteúdo for deletado com sucesso,
     * ou um código 404 se o conteúdo não for encontrado.
     */
    public function deleteAction(int $id): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $stmt = $this->pdo->prepare("DELETE FROM conteudos WHERE id = ?");
        $result = $stmt->execute([$id]);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Conteúdo deletado com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Conteúdo não encontrado']);
        }
    }

    /**
     * Sanitiza o tipo de conteúdo para garantir que ele seja válido.
     * 
     * @param string $type Tipo de conteúdo.
     * @return string|null Retorna o tipo se for válido, ou null se não for.
     */
    private function sanitizeType(string $type): ?string
    {
        $validTypes = ['article', 'video', 'meditation'];
        return in_array($type, $validTypes) ? $type : null;
    }
}
?>
