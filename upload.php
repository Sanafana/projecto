<?php
session_start(); 
// Verifica se está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit("Acesso Restrito.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fábrica Inteligente</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="style.css">

   
  
  </head>
  
<body>
  
<?php require_once 'navbar.php'; ?>

    <!-- Conteúdo principal -->
    <div class="main-content-area">
        <h1>Upload de Imagem</h1>
        <!-- depois teremos de alterar isto para ir buscar diretamente do arduino ou a câmara guarda numa pasta e fazemos upload -->
        <form action="api/api_receber_imagem.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="fotoInput" class="form-label">Selecionar Imagem:</label>
                <input class="form-control" type="file" id="fotoInput" name="foto_entrada" accept="image/jpeg, image/png" required>
            </div>

            <div class="mb-3">
                <label for="sensorIdInput" class="form-label">ID do Sensor:</label>
                <input type="text" class="form-control" id="sensorIdInput" name="sensor_id" value="porta_entrada"> 
            </div>

            <button type="submit" class="btn btn-primary">Enviar Imagem</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
