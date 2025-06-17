<?php


// Define o Content-Type padrão como JSON para a resposta
header('Content-Type: application/json; charset=utf-8');

// --- Define o caminho para o ficheiro de dados LOGO NO INÍCIO ---

$colab_data_file_path = __DIR__ . "/historicos/entrada_colaboradores.txt";

// Prepara uma estrutura de resposta padrão
$response = [
    'success' => false,
    'data' => null,
    'message' => null
];


// --- TRATAMENTO DO PEDIDO POST (Adicionar Nova Entrada) ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : null;
    $id_colaborador = isset($_POST['id']) ? trim($_POST['id']) : null;
    $autorizacao_post = isset($_POST['autorizacao']) ? trim($_POST['autorizacao']) : null;

    // Validação
    if (empty($nome) || empty($id_colaborador) || $autorizacao_post === null || ($autorizacao_post !== '1' && $autorizacao_post !== '0')) {
        $response['message'] = "Erro: Dados POST inválidos ou em falta (nome, id, autorizacao='1'ou'0').";
        http_response_code(400);
    } else {
        $data_picagem = date('Y/m/d H:i:s');
        $linha_log = $nome . "-" . $id_colaborador . "-" . $data_picagem . "-" . $autorizacao_post . "\n";

        // Tenta adicionar ao ficheiro (caminho definido no início do script)
        // Usa @ para suprimir warning de permissão, vamos tratar o retorno 'false'
        if (@file_put_contents($colab_data_file_path, $linha_log, FILE_APPEND | LOCK_EX) !== false) {
            $response['success'] = true;
            $response['message'] = "Nova entrada de colaborador adicionada com sucesso.";
            $response['data'] = ['nome' => $nome, 'id' => $id_colaborador, 'data_picagem' => $data_picagem, 'autorizado' => ($autorizacao_post == '1')];
            http_response_code(201); // Created
        } else {
            $error_details = error_get_last(); // Tenta obter mais detalhes do erro
            $response['message'] = "Erro do servidor ao guardar dados. Verifica permissões no ficheiro/pasta. Detalhe: " . ($error_details['message'] ?? 'N/A');
            http_response_code(500); // Internal Server Error
        }
    }


// --- TRATAMENTO DO PEDIDO GET (Ler Todas as Entradas) ---

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $colaboradores_data = [];

    // Verifica o ficheiro (caminho definido no início do script)
    if (file_exists($colab_data_file_path) && is_readable($colab_data_file_path)) {
        $colab_lines_raw = file($colab_data_file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($colab_lines_raw as $line) {
            $parts = explode('-', $line, 4);
            if (count($parts) === 4) {
                $colaboradores_data[] = [
                    'nome' => trim($parts[0]),
                    'id' => trim($parts[1]),
                    'data_picagem' => trim($parts[2]),
                    'autorizado' => (trim($parts[3]) == '1')
                ];
            }
        }
        $response['success'] = true;
        $response['data'] = array_reverse($colaboradores_data);

    } else {
        $response['message'] = "Ficheiro de dados (`" . basename($colab_data_file_path) . "`) não encontrado ou sem permissão.";
        http_response_code(500);
    }


// --- TRATAMENTO DE OUTROS MÉTODOS HTTP ---

} else {
    $response['message'] = "Método não permitido. Use GET ou POST.";
    http_response_code(405); // Method Not Allowed
}


// Codifica SEMPRE a estrutura $response para JSON e imprime-a antes de terminar


?>