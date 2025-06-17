<?php

header('Content-Type: text/html; charset=utf-8');

// --- Configuração ---
$pasta_destino_base = "uploads/"; // Pasta principal para uploads
$subpasta_sensor = "fotos_entradas/"; // Subpasta específica para estas fotos
$pasta_destino_completa = $pasta_destino_base . $subpasta_sensor;

// --- Verificação do Pedido ---
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    die("Erro: Método não permitido.");
}

// --- Processamento do Upload ---

// Verifica se o campo do ficheiro foi enviado e se não houve erros de upload PHP
// Substitui 'foto_entrada' pelo 'name' do input type="file"
if (isset($_FILES['foto_entrada']) && $_FILES['foto_entrada']['error'] === UPLOAD_ERR_OK) {

    $ficheiro_temporario = $_FILES['foto_entrada']['tmp_name']; // Caminho temporário do ficheiro no servidor
    $nome_original = $_FILES['foto_entrada']['name']; // Nome original
    $tamanho_ficheiro = $_FILES['foto_entrada']['size']; // Tamanho em bytes


    // --- Criar Nome Único e Seguro ---
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION)); // Pega a extensão original em minúsculas
    if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'gif'])) { // Verifica se é uma extensão de imagem comum
        http_response_code(400);
        die("Erro: Tipo de ficheiro inválido.");
    }
    // Gera um nome único baseado no tempo e dados aleatórios, mantendo a extensão
    $nome_ficheiro_novo = "entrada_" . date('Ymd_His') . "_" . uniqid() . "." . $extensao;

    // --- Construir Caminho de Destino ---
    $caminho_destino_completo = $pasta_destino_completa . $nome_ficheiro_novo;

    // --- Verificar/Criar Pasta de Destino ---
    // Verifica se a pasta $pasta_destino_completa existe
    if (!is_dir($pasta_destino_completa)) {
        // Se não existe, tenta criar recursivamente (cria 'uploads' e 'fotos_entradas' se necessário)
        // 0777 para servidor web ter permissão para escrever.
        if (!mkdir($pasta_destino_completa, 0777, true)) {
             http_response_code(500);
             die("Erro: Falha ao criar a pasta de uploads no servidor.");
        }
    }

    // --- Mover o Ficheiro ---
    // Tenta mover o ficheiro do local temporário para o destino final
    if (move_uploaded_file($ficheiro_temporario, $caminho_destino_completo)) {
        // SUCESSO! O ficheiro foi guardado.

        http_response_code(200); // OK
        echo "Imagem recebida e guardada como: " . $nome_ficheiro_novo;
        // Dentro de api_receber_imagem.php
// ... após move_uploaded_file ter sucesso ...

    // SUCESSO! O ficheiro foi guardado como $nome_ficheiro_novo

    // --- INÍCIO: Adicionar Registo ao Log ---
    $log_file = "historicos/log_fotos.txt"; // Nome do ficheiro de log
    // Guarda a data/hora atual e o nome do ficheiro da imagem, separados por ;
    $log_entry = date('Y/m/d H:i:s') . ";" . $nome_ficheiro_novo . "\n";

    // Adiciona a entrada ao fim do ficheiro de log
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    // --- FIM: Adicionar Registo ao Log ---

    http_response_code(200); // OK
    echo "Imagem recebida e guardada como: " . $nome_ficheiro_novo;


    } else {
        // ERRO ao mover o ficheiro (provavelmente permissões!)
        http_response_code(500); // Internal Server Error
    
        die("Erro: Falha ao guardar o ficheiro da imagem no servidor. Verifique as permissões da pasta '$pasta_destino_completa'.");
    }

} else {
    // --- Erro no Upload ou Ficheiro Não Enviado ---
    $php_upload_errors = []; 
    http_response_code(400); // Bad Request
    $error_code = $_FILES['foto_entrada']['error'] ?? 'Campo não encontrado';
    die("Erro no upload do ficheiro. Código: " . $error_code );
}

?>

<?php

// Redireciona imediatamente o browser para a página de login
header("Location: ../camera.php");


exit;
?>