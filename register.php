<?php

session_start();

// Inicializar variáveis de feedback
$error_message = "";


// Processar APENAS se for um pedido POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obter dados do formulário
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

    $filename = "users.txt";

    // --- Validações ---
    if (empty($username) || empty($password) || empty($password_confirm)) {
        $error_message = "Todos os campos são obrigatórios.";
    } elseif ($password !== $password_confirm) {
        $error_message = "As passwords não coincidem.";
    } elseif (strlen($password) < 6) { // Exemplo: Validação de tamanho mínimo
        $error_message = "A password deve ter pelo menos 6 caracteres.";
    } else {
        // Verificar se o username já existe
        $username_exists = false;
        if (file_exists($filename)) {
            $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Verifica se a linha contém ':' antes de explodir
                if (strpos($line, ':') !== false) {
                    $data = explode(":", $line);
                    // Compara o username (primeira parte)
                    if (isset($data[0]) && $data[0] === $username) {
                        $username_exists = true;
                        break;
                    }
                }
            }
        }

        if ($username_exists) {
            $error_message = "Este username já está em uso. Escolha outro.";
        }
    }

    // --- Processamento (Se não houver erros de validação) ---
    if (empty($error_message)) {
        // Gerar o HASH seguro da password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Preparar a linha para guardar (username:hash)
        $data_to_save = $username . ":" . $password_hash . "\n";

        // Tentar adicionar a linha ao ficheiro
        if (file_put_contents($filename, $data_to_save, FILE_APPEND | LOCK_EX) !== false) {
            // Sucesso! Redirecionar para o login com mensagem de sucesso
            $success_msg = "Conta criada com sucesso! Pode fazer login.";
            header("Location: login.php?success=" . urlencode($success_msg));
            exit; 
        } else {
            // Erro ao escrever no ficheiro
            $error_message = "Erro do servidor ao guardar os dados. Tente novamente mais tarde.";
            
        }
    }
  
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo - Fábrica Inteligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos básicos para centralizar */
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f8f9fa; }
        .register-container { max-width: 450px; width: 100%; padding: 2rem; background-color: white; border-radius: 0.5rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1); }
    </style>
</head>
<body class="background-page">
    <div class="register-container">
        <h1 class="text-center mb-4">Criar Conta</h1>

        <?php
        // Mostrar mensagem de erro, se existir
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>';
        }
        ?>

        <form action="register.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirmar Password</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registar</button>
             <p class="mt-3 text-center">
                 Já tem conta? <a href="login.php">Faça Login</a>
             </p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>