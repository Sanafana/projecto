<?php
session_start();

// Inicializar a variável de erro
$error_message = "";

// Processar APENAS se o formulário foi submetido (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obter dados do formulário
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validar se os campos não estão vazios
    if (empty($username) || empty($password)) {
        $error_message = "Por favor, preencha o username e a password.";
    } else {
        // Tentar autenticar
        $filename = "users.txt"; // Nome do ficheiro de utilizadores

        if (file_exists($filename)) {
            $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $autenticado = false; // Flag de autenticação

            foreach ($lines as $line) {
                // Verifica se a linha contém ':' antes de explodir
                if (strpos($line, ':') !== false) {
                    $data = explode(":", $line, 2); // Divide apenas em 2 partes: username e o resto (hash)

                    // Verifica se temos as duas partes esperadas
                    if (count($data) === 2) {
                        $usernameDoFicheiro = $data[0];
                        $hashGuardado = $data[1];

                        // ---- VERIFICAÇÃO SEGURA USANDO password_verify ----
                        // Compara o username fornecido com o do ficheiro
                        // E verifica se a password fornecida corresponde ao HASH guardado
                        if ($usernameDoFicheiro === $username && password_verify($password, $hashGuardado)) {
                            $autenticado = true; // Login bem-sucedido!
                            break; 
                        }
                       
                    }
                }
            } 

            // Verifica o resultado da autenticação
            if ($autenticado) {
                // Login bem-sucedido: Definir variáveis de sessão
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username; // Guarda o username na sessão

                // Redirecionar para dashboard.php
                header("Location: dashboard.php"); 
                exit; 

            } else {
                // Se percorreu o ficheiro e não autenticou
                $error_message = "Username ou password inválidos.";
            }

        } else {
            // Se o ficheiro users.txt não existe
            $error_message = "Erro: O ficheiro de utilizadores não foi encontrado.";
        }
    }
}
// Se não for POST ou se houver erro, continua para mostrar o HTML


?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fábrica Inteligente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilos básicos para centralizar */
        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            background-color: #f8f9fa; 
        }

        .login-container { 
            max-width: 450px; 
            width: 100%; 
            padding: 2rem; 
            background-color: white; 
            border-radius: 0.5rem; 
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1); }
    </style>
</head>
<body class="background-page">
    <div class="login-container">
        <h1 class="text-center mb-4">Fábrica Inteligente</h1>

        <?php
        //Mostrar a mensagem de erro (igual ao register)
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>';
        }

        // Mostrar mensagem de sucesso vinda do registo
         if (isset($_GET['success'])) {
             echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
         }
        ?>

        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
             <p class="mt-3 text-center">
                 Não tem conta? <a href="register.php">Registe-se</a>
             </p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>