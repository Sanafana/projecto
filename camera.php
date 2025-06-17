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
  <div class="content">
    <h1>Registo de Entradas (Fotos)</h1>
  


<div class="row"> <?php // Usa o sistema de grelha Bootstrap para organizar as fotos ?>
    <?php
    $log_file = "api/historicos/log_fotos.txt"; // Caminho para o teu ficheiro de log
    $fotos_dir = "api/uploads/fotos_entradas/"; // Caminho (URL) para a pasta onde as imagens estão guardadas

    // Verifica se o ficheiro de log existe
    if (file_exists($log_file)) {
        // Lê todas as linhas do log, ignorando linhas vazias ou só com quebras de linha
        $log_entries_raw = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Inverte o array para mostrar as entradas mais recentes primeiro
        $log_entries = array_reverse($log_entries_raw);

        if (empty($log_entries)) {
            echo '<p class="col-12">Nenhum registo de entrada encontrado.</p>';
        } else {
            // Itera sobre cada entrada do log
            foreach ($log_entries as $entry) {
                // Separa a linha no ';' para obter data/hora e nome do ficheiro
                // Limita a 2 partes para o caso de haver ';' no nome do ficheiro
                $parts = explode(';', $entry, 2);

                // Garante que temos as duas partes esperadas
                if (count($parts) === 2) {
                    $timestamp = trim($parts[0]);
                    $image_filename = trim($parts[1]);
                    $image_path = $fotos_dir . $image_filename; // Constrói o caminho completo para a imagem

                    // Verifica se o ficheiro da imagem realmente existe antes de tentar mostrá-lo
         
                    if (@file_exists($image_path)) {
    ?>
                     <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="ratio ratio-16x9">
                                    <img src="<?php echo htmlspecialchars($image_path); ?>"
                                         class="card-img-top"
                                         style="object-fit: cover; width: 100%; height: 100%;"
                                         alt="Foto Entrada <?php echo htmlspecialchars($timestamp); ?>">
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><small>Data: <?php echo htmlspecialchars($timestamp); ?></small></p>
                                    <p class="card-text"><small>Ficheiro: <?php echo htmlspecialchars($image_filename); ?></small></p>
                                </div>
                            </div>
                        </div>
    <?php
                    } 
                } 
            } 
        } 
    } else {
      
        echo '<p class="col-12">Ficheiro de log não encontrado.</p>';
    }
    ?>
</div>
  
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
