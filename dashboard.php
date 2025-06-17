<?php

session_start(); 

// Verifica se o utilizador está autenticado. Se não estiver, redireciona para login.php
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

<?php 
// --- valores Sensor Temperatura
$valor_temperatura_torno = @trim(file_get_contents("api/files/temperatura_torno/valor.txt")); 
$hora_temperatura_torno = @trim(file_get_contents("api/files/temperatura_torno/hora.txt"));
$nome_temperatura_torno = @trim(file_get_contents("api/files/temperatura_torno/nome.txt"));
$log_temperatura_torno  = array_slice(file("api/files/temperatura_torno/temperaturalog.txt", FILE_IGNORE_NEW_LINES), -5);
$valor_temperatura_torno_num = floatval($valor_temperatura_torno);
//valores Sensor Peso
$nome_peso = @trim(file_get_contents("api/files/peso/nome.txt"));
$log_peso  = array_slice(file("api/files/peso/pesolog.txt", FILE_IGNORE_NEW_LINES), -5);
$peso = @trim(file_get_contents("api/files/peso/valor.txt"));
$hora_peso = @trim(file_get_contents("api/files/peso/hora.txt"));
//valores Sensor Luminosidade
$nome_luminosidade = @trim(file_get_contents("api/files/luminosidade/nome.txt"));
$log_luminosidade  = array_slice(file("api/files/luminosidade/luminosidadelog.txt", FILE_IGNORE_NEW_LINES), -5);
$hora_luminosidade = @trim(file_get_contents("api/files/luminosidade/hora.txt"));
$valor_luminosidade = @trim(file_get_contents("api/files/luminosidade/valor.txt"));
//valores Sensor Porta
$nome_porta = @trim(file_get_contents("api/files/sensor_porta/nome.txt"));
$log_porta  = array_slice(file("api/files/sensor_porta/sensor_portalog.txt", FILE_IGNORE_NEW_LINES), -5);
$hora_porta = @trim(file_get_contents("api/files/sensor_porta/hora.txt"));
$porta_status = @trim(file_get_contents("api/files/sensor_porta/valor.txt"));


//valores temperatura maquina
$valor_temperatura_maquina = @trim(file_get_contents("api/files/temperatura_maquina/valor.txt"));
$hora_temperatura_maquina = @trim(file_get_contents("api/files/temperatura_maquina/hora.txt"));
$valor_temperatura_maquina_num = floatval($valor_temperatura_maquina);
$nome_temperatura_maquina = @trim(file_get_contents("api/files/temperatura_maquina/nome.txt"));

 // Define qual imagem usar com base no valor
 if ($valor_temperatura_maquina_num >= 50) {
    // Se for >= 100, usa a imagem high (Certifica-te que o caminho está correto!)
    $temp_maq_icon_src = 'api/imagens/high-temperature.png';
} else {
    // Se for < 100, usa a imagem low (Certifica-te que o caminho está correto!)
    $temp_maq_icon_src = 'api/imagens/low-temperature.png';
}


 // Define qual imagem usar com base no valor
 if ($valor_temperatura_torno_num >= 100) {
     // Se for >= 100, usa a imagem high 
     $temp_icon_src = 'api/imagens/high-temperature.png';
 } else {
     // Se for < 100, usa a imagem low 
     $temp_icon_src = 'api/imagens/low-temperature.png';
 }

 if ($peso >= 50) {
   
    $peso_icon_src = 'api/imagens/peso_alto.png';
} else {
    // Se for < 100, usa a imagem low 
    $peso_icon_src = 'api/imagens/peso_baixo.png';
}

if ($valor_luminosidade >= 50) {
   
    $luminosidade_icon_src = 'api/imagens/light-off.png';
} else {
    // Se for < 100, usa a imagem low 
    $luminosidade_icon_src = 'api/imagens/light-on.png';
}


//tabela sensores BADGE
// Avalia o Sensor de temperatura verifica o intervalo do peso para classificar o estado:
if($valor_temperatura_torno>150){
    $estado_temperatura_torno ="Erro!";
    $badge_temperatura_torno = "warning";
    $valor_temperatura_torno = "Erro! A máquina queimou";
}else{
    if ($valor_temperatura_torno >= 100) {
        $estado_temperatura_torno = $nome_temperatura_torno." elevada";
        $badge_temperatura_torno = "warning";
    }  
    else {
        $estado_temperatura_torno = $nome_temperatura_torno." normal";
        $badge_temperatura_torno = "success";
    }
}
// Avalia o Sensor de Peso verifica o intervalo do peso para classificar o estado:
if($peso>60){
    $estado_peso ="Erro!";
    $badge_peso = "danger";
    $valor_peso = "Erro! Peso excessivo. Rebentou com a balança";
}elseif ($peso >=50 && $peso <=60) {
        $estado_peso = $nome_peso." perto do limite";
        $badge_peso = "warning";
} 
else {
        $estado_peso = $nome_peso." normal";
        $badge_peso = "success";
} 
// Avalia o Sensor de Luminosidade verifica o nivel da luminosidade e classifica o estado:
if($valor_luminosidade>=50){
    $estado_luminosidade ="Luz desligada!";
    $badge_luminosidade = "info";
}
else {
    $estado_luminosidade ="Luz ligada!";
    $badge_luminosidade = "info";
} 
// Avalia o Sensor da Porta verifica se esta ou não aberta e classifica o estado:
if($porta_status == 'aberta'){
    $estado_porta ="Porta Aberta!";
    $badge_porta = "info";
}
else {
    $estado_porta ="Porta fechada!";
    $badge_porta = "info";
} 



 ?>


  <!-- Conteúdo principal onde são apresentados os cartoes dos sensores-->
  <div class="main-content-area">
    <h1>Sensores</h1>
    <div class="row text-center g-4 mb-3">
          <!-- Sensor : Temepratura do Torno -->
        <div class="col-md-3 col-sm-7">
            <div class="card h-100">
                <!-- Cabeçalho do cartão com cor informativa -->
                <div class="card-header bg-info-subtle text-emphasis-info">
                     <!-- Mostra o valor atual da temperatura (convertido para ºC) -->
                    Temperatura Torno: <?php echo $valor_temperatura_torno_num . "º"; ?>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-2">
                    <div class="ratio ratio-1x1" style="max-width: 100px;">
                         <!-- Mostra imagem correspondente (alta ou baixa temperatura) -->
                        <img src="<?php echo htmlspecialchars($temp_icon_src); ?>" height="80" alt="Temperatura">
                    </div>
                </div>
                <!-- Rodapé com hora da última atualização e link para modal de histórico -->
                <div class="card-footer text-muted">
                    <?= "Atualização: ".$hora_temperatura_torno."-" ?>
                    <b><a href=# data-bs-toggle="modal" data-bs-target="#historico_temperatura_torno">Histórico</a> </b>
                </div>
            </div>
        </div>
         <!-- Sensor : Peso -->
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <!-- Cabeçalho com valor do peso atual -->
                <div class="card-header bg-primary-subtle text-emphasis-primary">
                    Peso: <?php echo $peso . "KG" ?>
                </div>
                <!-- Ícone de balança (peso alto ou baixo) -->
                <div class="card-body d-flex justify-content-center align-items-center p-2">
                    <img src="<?php echo htmlspecialchars($peso_icon_src); ?>" height="80" alt="Peso">
                </div>
                <!-- Rodapé com hora e link para histórico -->
                <div class="card-footer text-muted">
                    <?= "Atualização: ".$hora_peso."-" ?>
                    <b><a href=# data-bs-toggle="modal" data-bs-target="#historico_peso">Histórico</a> </b>
                </div>
            </div>
        </div>
            <!-- Sensor : Luminosidade -->
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <!-- Mostra o valor atual de luminosidade em lumens -->
                <div class="card-header bg-success text-emphasis-warning">
                    Luminosidade: <?php echo $valor_luminosidade." lumens"; ?>
                </div>
                <!-- Mostra ícone de luz ligada/desligada -->
                <div class="card-body d-flex justify-content-center align-items-center p-2">
                    <img src="<?php echo htmlspecialchars($luminosidade_icon_src); ?>" height="80" alt="Peso">
                </div>
                <!-- Hora de atualização e acesso ao histórico -->
                <div class="card-footer text-muted">
                    <?= "Atualização: ".$hora_luminosidade."-" ?>
                    <b><a href=# data-bs-toggle="modal" data-bs-target="#historico_luminosidade">Histórico</a> </b>
                </div>
            </div>
        </div>
            <!-- Sensor : Porta -->
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <!-- Estado da porta (aberta ou fechada) -->
                <div class="card-header bg-warning-subtle text-emphasis-warning">
                    Porta: <?php echo $porta_status ?>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-2">
                    <img src="<?php echo htmlspecialchars((strtolower(trim($porta_status ?? ''))) === 'aberta' ? 'api/imagens/open-door.png' : 'api/imagens/door.png'); ?>" class="door-icon" height="70" alt="Estado Porta: <?php echo htmlspecialchars($porta_status); ?>">
                </div>
                <!-- Atualização e botão de histórico -->
                <div class="card-footer text-muted">
                    <?= "Atualização: ".$hora_porta."-" ?>
                    <b><a href=# data-bs-toggle="modal" data-bs-target="#historico_porta">Histórico</a></b>
                    <button id="toggleDoor" class="btn btn-sm btn-primary ms-2">
                        <?php echo (strtolower(trim($porta_status ?? '')) === 'aberta') ? 'Fechar' : 'Abrir'; ?>
                    </button>
                </div>
            </div>
        </div><!-- fecha a row dos cartões de sensores -->
    </div> <!-- Fecha a secção main-content-area -->

    <!-- <span style="color:blue;">TABELA RESUMO DOS SENSORES</span> -->
<!-- <span style="color:blue;">Apresenta todos os sensores num único quadro com o nome, valor, data e estado visual</span> -->
    <div class="container" style="padding-top: 10px; padding-bottom: 10px">
            <div class="card">
                <div class="card-header fw-bold">Tabela de Sensores</div>
                <div class="card-body">
                    <table class="table table">
                        <thead>
                            <tr>
                                <th>Tipo de Dispositivos IoT</th>
                                <th>Valor</th>
                                <th>Data de Atualização</th>
                                <th>Estado Alertas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?=$nome_temperatura_torno ?></td>
                                <td><?=$valor_temperatura_torno." ºC"?></td>
                                <td><?=$hora_temperatura_torno?></td>
                                <td><span class="badge rounded-pill text-bg-<?= $badge_temperatura_torno ?>"><?= $estado_temperatura_torno ?></span></td>
                            </tr>
                            <tr>
                                <td><?=$nome_peso?></td>
                                <td><?=$peso." kg"?></td>
                                <td><?=$hora_peso?></td>
                                <td><span class="badge rounded-pill text-bg-<?= $badge_peso ?>"><?= $estado_peso ?></span></td>
                            </tr>
                            <tr>
                                <td><?=$nome_luminosidade?></td>
                                <td><?=$valor_luminosidade." lumens"?></td>
                                <td><?=$hora_luminosidade?></td>
                                <td><span class="badge rounded-pill text-bg-<?= $badge_luminosidade ?>"><?= $estado_luminosidade ?></span></td>
                            </tr>
                            <tr>
                                <td><?=$nome_porta?></td>
                                <td><?=$porta_status?></td>
                                <td><?=$hora_porta?></td>
                                <td><span class="badge rounded-pill text-bg-<?= $badge_porta ?>"><?= "Porta ".$porta_status."!" ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



             


  <div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Entrada de Colaboradores</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered table-fixed-layout"> 
              <colgroup> 
                    <col style="width: 30%;">
                    <col style="width: 15%;">
                    <col style="width: 30%;">
                    <col style="width: 25%;">
                </colgroup>

                <thead class="table-light">
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">ID</th>
                        <th scope="col">Data de picagem</th>
                        <th scope="col" class="text-center">Autorização</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    
    require_once 'api/api_picagem_colaboradores.php'; // inclui a api para carregar os dados dos colaboradores


    // Verifica se houve erro ao carregar os dados (definido no include)
    if (isset($colaboradores_error) && $colaboradores_error !== null) {
        echo '<tr><td colspan="4" class="text-center text-danger">Erro: ' . htmlspecialchars($colaboradores_error) . '</td></tr>';
    }
    // Verifica se o array de dados está vazio (ou se houve erro)
    elseif (empty($colaboradores_data)) {
        echo '<tr><td colspan="4" class="text-center text-muted">Nenhum registo de colaborador encontrado.</td></tr>';
    }
    // Se há dados, faz loop e gera as linhas da tabela
    else {
        $max_entries_to_show = 15; // apresenta 15
        $entries_shown = 0;
        // Inverte aqui se quiser mostrar os mais recentes primeiro 
        $reversed_data = array_reverse($colaboradores_data);

        foreach ($reversed_data as $colaborador) {
            if ($entries_shown >= $max_entries_to_show) {
                break;
            }

            // Define badge class/texto com base no booleano 'autorizado'
            if ($colaborador['autorizado']) {
                $badge_class = 'text-bg-success'; $status_text = 'Válida';
            } else {
                $badge_class = 'text-bg-danger'; $status_text = 'Não Válida';
            }
    ?>
            <tr>
                <td><?php echo htmlspecialchars($colaborador['nome']); ?></td>
                <td><?php echo htmlspecialchars($colaborador['id']); ?></td>
                <td><?php echo htmlspecialchars($colaborador['data_picagem']); ?></td>
                <td class="text-center">
                    <span class="badge rounded-pill <?php echo $badge_class; ?>"><?php echo $status_text; ?></span>
                </td>
            </tr>
    <?php
            $entries_shown++;
        } // Fim foreach
    } // Fim else 
    ?>
</tbody>
            </table>
      </div> 
    </div>
    </div> <!-- Fim do card de colaboradores --> 


    </div> <!-- Fecha .main-content-area -->
  
    <!-- HISTORICO TEMPERATURA -->
     <!-- MODAL BOOTSTRAP: Histórico de Temperatura do Torno -->
    <div class="modal fade" id="historico_temperatura_torno" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Histórico |
                        <?= $nome_temperatura_torno ?>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!--  Corpo do modal -->
                  <!-- Apresenta uma tabela com as últimas entradas de temperatura -->
                <div class="modal-body">
                    <table class="table table">
                        <thead>
                            <tr>
                                <th><b>Últimas entradas</b></th>
                                <th><b>Valor</b></th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                //Loop que percorre as últimas 5 linhas do log da temperatura do torno
                                    foreach($log_temperatura_torno as $data_temperatura_torno){
                                        $temp = explode(';', $data_temperatura_torno); // Divide linha em data e valor
                                        echo '
                                        <tr>
                                        <td>' . $temp[0] . '</td>
                                        <td>' . $temp[1] . 'ºC</td>                                    
                                        </tr>';
                                    }
                                ?>
                        </tbody>
                    </table>
                </div>
                 <!-- Botão para fechar e link para ver o histórico completo -->
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-5" data-bs-dismiss="modal">Sair</button>
                    <a role="button" class="btn btn-primary rounded-5" href="historico.php?historico=temperatura_torno">Ver histórico completo</a>
                </div>
            </div>
        </div>
    </div>

    <!-- HISTORICO PESO -->
     <!--MODAL BOOTSTRAP: Histórico de PESO -->
    <div class="modal fade" id="historico_peso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Histórico |
                        <?= $nome_peso ?>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Apresenta as últimas leituras em formato de tabela -->
                <div class="modal-body">
                    <table class="table table">
                    <thead>
                            <tr>
                                <th><b>Últimas entradas</b></th>
                                <th><b>Valor</b></th>
                            </tr>
                        </thead>    
                    <tbody>
                                <?php
                                   //  Loop que percorre as últimas entradas do log de peso
                                    foreach($log_peso as $data_peso){
                                        $temp = explode(';', $data_peso); // Separa data e valor
                                        echo '
                                        <tr>
                                        <td>' . $temp[0] . '</td>
                                        <td>' . $temp[1] . ' kg</td>                                    
                                        </tr>';
                                    }
                                ?>
                        </tbody>
                    </table>
                </div>
                 <!-- Botão para fechar + link para histórico completo -->
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-5" data-bs-dismiss="modal">Sair</button>
                    <a role="button" class="btn btn-primary rounded-5" href="historico.php?historico=peso">Ver histórico completo</a>
                </div>
            </div>
        </div>
    </div>

    <!-- HISTORICO LUMINOSIDADE -->
     <!--MODAL BOOTSTRAP: Histórico de PESO -->
    <div class="modal fade" id="historico_luminosidade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Histórico |
                        <?= $nome_luminosidade ?>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table">
                    <thead>
                            <tr>
                                <th><b>Últimas entradas</b></th>
                                <th><b>Valor</b></th>
                            </tr>
                        </thead>    
                    <tbody>
                        
                                <?php
                                // Loop que percorre as últimas entradas do log de luminosidade
                                    foreach($log_luminosidade as $data_luminosidade){ 
                                        $temp = explode(';', $data_luminosidade); // Separa data e valor
                                        echo '
                                        <tr>
                                        <td>' . $temp[0] . '</td>
                                        <td>' . $temp[1] . ' lumens</td>                                    
                                        </tr>';
                                    }
                                ?>
                        </tbody>
                    </table>
                </div>
                 <!-- Botão para fechar + link para histórico completo -->
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-5" data-bs-dismiss="modal">Sair</button>
                    <a role="button" class="btn btn-primary rounded-5" href="historico.php?historico=luminosidade">Ver histórico completo</a>
                </div>
            </div>
        </div>
    </div>

    <!-- HISTORICO PORTA -->
    <div class="modal fade" id="historico_porta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Histórico |
                        <?= $nome_porta ?>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table">
                    <thead>
                            <tr>
                                <th><b>Últimas entradas</b></th>
                                <th><b>Valor</b></th>
                            </tr>
                        </thead>    
                    <tbody>
                                <?php
                                 // Loop que percorre as últimas entradas do log da Porta
                                    foreach($log_porta as $data_porta){
                                        $temp = explode(';', $data_porta); // Separa data e valor
                                        echo '
                                        <tr>
                                        <td>' . $temp[0] . '</td>
                                        <td>' . $temp[1] . '</td>                                    
                                        </tr>';
                                    }
                                ?>
                        </tbody>
                    </table>
                </div>
                 <!-- Botão para fechar + link para histórico completo -->
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-5" data-bs-dismiss="modal">Sair</button>
                    <a role="button" class="btn btn-primary rounded-5" href="historico.php?historico=sensor_porta">Ver histórico completo</a>
                </div>
            </div>
        </div>
    </div>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
        $('#toggleDoor').click(function() {
            $.post('api/api_porta.php', function(response) {
                if (response.success) {
                    // Atualiza a página para mostrar o novo estado
                    location.reload();
                }
            });
        });
    });
  </script>
</body>
</html>
