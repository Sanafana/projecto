<?php

header('Content-Type: text/plain; charset=utf-8');
header("refresh: 1;");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    
    // Verifica se os dados essenciais foram enviados
    if (isset($_POST['valor']) && isset($_POST['hora']) && isset($_POST['nome'])) {
        $nome = trim($_POST['nome']);
        $valor = trim($_POST['valor']); // Pode ser número, "aberto", "fechado", "Ligado", etc.
        $hora = trim($_POST['hora']);

        $caminho_pasta = "files/" . $nome . "/"; 

        // Tenta criar a pasta do sensor se não existir 
        if (!is_dir($caminho_pasta)) {
            // Tenta criar recursivamente, define permissões
            if (!mkdir($caminho_pasta, 0755, true)) {
                 http_response_code(500); // Erro do servidor
                 die("Erro: Não foi possível criar a pasta do sensor no servidor para '$nome'. Verifique permissões.");
            }
        }

        // Guarda o valor recebido (seja número ou texto) em valor.txt
        $ok_valor = file_put_contents($caminho_pasta . "valor.txt", $valor, LOCK_EX);

        // Guarda a hora recebida em hora.txt
        $ok_hora = file_put_contents($caminho_pasta . "hora.txt", $hora, LOCK_EX);
        
        $ficheiro_log = $caminho_pasta . $nome . "log.txt";
        // Guarda a hora e o valor no log.txt
        $ok_log = file_put_contents($ficheiro_log, $hora . ";" . $valor . PHP_EOL, FILE_APPEND | LOCK_EX);
       



        // Resposta simples de sucesso ou erro
        if ($ok_valor !== false && $ok_hora !== false && $ok_log !== false) {
             echo "Dados para '$nome' atualizados."; // Resposta 200 OK
        } else {
             http_response_code(500);
             echo "Erro ao escrever ficheiros para '$nome'.";
        }

    } else {
         // Dados em falta no POST
         http_response_code(400); // Bad Request
         echo "Erro: Dados POST em falta (nome, valor, hora).";
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['nome'])) {
        $nome = trim($_GET['nome']);
        $ficheiro_valor = "files/" . $nome . "/valor.txt";

        // Adiciona a validação file_exists antes de ler
        if (file_exists($ficheiro_valor)) {
            $conteudo = file_get_contents($ficheiro_valor);
            if ($conteudo !== false) {
                echo $conteudo; // Devolve o conteúdo (número, "aberto", "fechado", etc.)
            } else {
                http_response_code(500);
                echo "Erro ao ler ficheiro para '$nome'.";
            }
        } else {
            http_response_code(404); // Not Found
            echo "Erro: Ficheiro de valor não encontrado para '$nome'.";
        }
    } else {
        http_response_code(400); // Bad Request
        echo "Erro: Parâmetro 'nome' em falta no pedido GET.";
    }
} else {
    // Método não permitido (POST ou GET)
    http_response_code(405); // Method Not Allowed (mais correto que 501)
    echo ("Erro: Método não permitido.");
}

?>