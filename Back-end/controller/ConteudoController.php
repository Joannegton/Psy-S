<?php
require_once __DIR__ . '/../config/config.php'; 
require_once __DIR__ . '/../service/Conteudo.php';

class ContentController
{
    private Content $contentModel;

    /**
     * Construtor da classe ContentController.
     * 
     * @param Content $contentModel Instância do Content para injeção de dados
     */
    public function __construct(Content $contentModel)
    {
        $this->contentModel = $contentModel;
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

        $result = $this->contentModel->createContent($data['nome'], $data['link'], $type);

        if ($result === 'Conteúdo adicionado com sucesso!') {
            http_response_code(201);
            echo json_encode(['message' => $result]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => $result]);
        }
    }

    /**
     * Obtém um conteúdo pelo ID.
     * 
     * Responde com um código 200 e os dados do conteúdo se encontrado,
     * ou um código 404 se o conteúdo não for encontrado.
     */
    public function getAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'GET') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        // Captura os parâmetros da URL
        $id_conteudo = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if ($id_conteudo === null) {
            http_response_code(400);
            echo json_encode(['message' => 'ID do conteúdo não informado']);
            return;
        }

        $content = $this->contentModel->getContent($id_conteudo);

        if (empty($content)) {
            http_response_code(404);
            echo json_encode(['message' => $content['error']]);
        }
        
        http_response_code(200);
        echo json_encode($content);
        
    }

    /**
     * Atualiza um conteúdo existente.
     * 
     * Responde com um código 200 e uma mensagem de sucesso se o conteúdo for atualizado com sucesso,
     * ou um código 400 se os dados forem inválidos.
     */
    public function updateAction(): void
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

        // Captura os parâmetros da URL
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        $result = $this->contentModel->updateContent($id, $data['nome'], $data['link'], $type);

        if ($result === 'Conteúdo atualizado com sucesso!') {
            http_response_code(200);
            echo json_encode(['message' => $result]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => $result]);
        }
    }

    /**
     * Deleta um conteúdo pelo ID.
     * 
     * Responde com um código 200 e uma mensagem de sucesso se o conteúdo for deletado com sucesso,
     * ou um código 404 se o conteúdo não for encontrado.
     */
    public function deleteAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        // Captura os parâmetros da URL
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        $result = $this->contentModel->deleteContent($id);

        if ($result === 'Conteúdo deletado com sucesso!') {
            http_response_code(200);
            echo json_encode(['message' => $result]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => $result]);
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
