<?php
session_start();
// Garante que apenas utilizadores autenticados podem aceder
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit("Acesso Restrito.");
}
// Lê o valor da query string para saber que histórico carregar
$historico = $_GET['historico'] ?? 'entrada_colaboradores';
$dados = [];// Array onde vão ser armazenados os dados do histórico
// verificação de tipo de histórico selecionado e le os respectivos ficheiros .txt formatando os dados para apresentar
if ($historico === 'entrada_colaboradores') {
    $ficheiro = 'api/historicos/entrada_colaboradores.txt';
    if (file_exists($ficheiro)) {
        $linhas = file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($linhas as $linha) {
            $partes = explode('-', $linha);
            if (count($partes) === 4) {
                $dados[] = [
                    'nome' => $partes[0],
                    'id' => $partes[1],
                    'data' => $partes[2],
                    'status' => trim($partes[3])
                ];
            }
        }
    }
}
// verificação de tipo de histórico selecionado e le os respectivos ficheiros .txt formatando os dados para apresentar
if ($historico === 'log_fotos') {
    $ficheiro = 'api/historicos/log_fotos.txt';
    $diretorio_imagens = 'api/uploads/fotos_entradas/';
    if (file_exists($ficheiro)) {
        $linhas = file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($linhas as $linha) {
            $partes = explode(';', $linha);
            if (count($partes) === 2) {
                $dados[] = [
                    'data' => $partes[0],
                    'ficheiro' => $partes[1],
                    'caminho' => $diretorio_imagens . $partes[1]
                ];
            }
        }
    }
}
// verificação de tipo de histórico selecionado e le os respectivos ficheiros .txt formatando os dados para apresentar
if ($historico === 'temperatura_torno') {
    $ficheiro = 'api/files/temperatura_torno/temperaturalog.txt';
    if (file_exists($ficheiro)) {
        $linhas = file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($linhas as $linha) {
            $partes = explode(';', $linha);
            if (count($partes) === 2) {
                $dados[] = [
                    'data' => $partes[0],
                    'valor' => $partes[1],
                ];
            }
        }
    }
}
// verificação de tipo de histórico selecionado e le os respectivos ficheiros .txt formatando os dados para apresentar
if ($historico === 'peso') {
    $ficheiro = 'api/files/peso/pesolog.txt';
    if (file_exists($ficheiro)) {
        $linhas = file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($linhas as $linha) {
            $partes = explode(';', $linha);
            if (count($partes) === 2) {
                $dados[] = [
                    'data' => $partes[0],
                    'valor' => $partes[1],
                ];
            }
        }
    }
}
// verificação de tipo de histórico selecionado e le os respectivos ficheiros .txt formatando os dados para apresentar
if ($historico === 'luminosidade') {
    $ficheiro = 'api/files/luminosidade/luminosidadelog.txt';
    if (file_exists($ficheiro)) {
        $linhas = file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($linhas as $linha) {
            $partes = explode(';', $linha);
            if (count($partes) === 2) {
                $dados[] = [
                    'data' => $partes[0],
                    'valor' => $partes[1],
                ];
            }
        }
    }
}
// verificação de tipo de histórico selecionado e le os respectivos ficheiros .txt formatando os dados para apresentar
if ($historico === 'sensor_porta') {
    $ficheiro = 'api/files/sensor_porta/sensor_portalog.txt';
    if (file_exists($ficheiro)) {
        $linhas = file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($linhas as $linha) {
            $partes = explode(';', $linha);
            if (count($partes) === 2) {
                $estado = trim($partes[1]);
                $dados[] = [
                    'data' => $partes[0],
                    'estado' => $partes[1],
                    'valor' => $estado,
                ];

                // Store for chart
                $dates[] = $partes[0];
                $doorStates[] = ($estado === 'Aberta') ? 1 : 0; // Open = 1, Closed = 0
            }
        }
    }
}



?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Histórico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- o teu CSS personalizado -->
</head>
<body>

    <?php require_once 'navbar.php'; ?> <!-- A tua sidebar fixa -->

    <div class="main-content-area">
        <div class="container-fluid">

            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h3>Selecionar Histórico</h3>
                </div>
                <div class="col-md-6 text-end">
               <!-- Permite ao utilizador selecionar que histórico quer visualizar. -->
                    <form method="get">
                        <select class="form-select w-auto d-inline-block" name="historico" onchange="this.form.submit()">
                            <option value="entrada_colaboradores" <?= $historico === 'entrada_colaboradores' ? 'selected' : '' ?>>Entradas de Colaboradores</option>
                            <option value="log_fotos" <?= $historico === 'log_fotos' ? 'selected' : '' ?>>Histórico de Fotos</option>
                            <option value="temperatura_torno" <?= $historico === 'temperatura_torno' ? 'selected' : '' ?>>Temperatura</option>
                            <option value="peso" <?= $historico === 'peso' ? 'selected' : '' ?>>Peso</option>
                            <option value="luminosidade" <?= $historico === 'luminosidade' ? 'selected' : '' ?>>Luminosidade</option>
                            <option value="sensor_porta" <?= $historico === 'sensor_porta' ? 'selected' : '' ?>>Porta</option>
                        </select>
                    </form>
                </div>
            </div>

            <h4 class="mb-3">
                <?= $historico === 'entrada_colaboradores' ? 'Histórico de Entradas de Colaboradores' : 'Histórico '; ?>
            </h4>
            <!-- Tabela ou cards dependendo do histórico selecionado -->
             <!-- Entradas de colaboradores -->
            <?php if ($historico === 'entrada_colaboradores'): ?>
                <table class="table table-bordered table-hover table-fixed-layout">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>ID</th>
                            <th>Data e Hora</th>
                            <th>Autorização</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $col): ?>
                            <tr>
                                <td><?= htmlspecialchars($col['nome']) ?></td>
                                <td><?= htmlspecialchars($col['id']) ?></td>
                                <td><?= htmlspecialchars($col['data']) ?></td>
                                <td>
                                    <span class="badge <?= $col['status'] == '1' ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $col['status'] == '1' ? 'Válida' : 'Não Válida' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                   <!-- Entrada de Fotos -->             
            <?php elseif ($historico === 'log_fotos'): ?>
                <div class="row">
                    <?php foreach ($dados as $foto): ?>
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <img src="<?= htmlspecialchars($foto['caminho']) ?>" class="card-img-top" alt="Foto sensor">
                                <div class="card-body text-center">
                                    <small><?= htmlspecialchars($foto['data']) ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            
            <!-- Entrada de temperatura -->
            <?php elseif ($historico === 'temperatura_torno'): ?>
                <table class="table">
                        <thead>
                            <tr>
                                <th>Data de Atualização</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                                    //informação para os gráficos
                                        $dates = [];
                                        $temperatures = [];


                                    foreach($dados as $data_temp){
                                        $valor_formatado = $data_temp['valor'] . 'ºC';
                                        $dates[] = $data_temp['data'];
                                        $temperatures[] = (float)$data_temp['valor'];
                        
                                        echo '
                                        <tr>
                                            <td>' . htmlspecialchars($data_temp['data']) . '</td>
                                            <td>' . htmlspecialchars($valor_formatado) . '</td>
                                        </tr>';
                                    }
                            ?>
                        </tbody>
                </table>
                <!-- Temperature Chart -->
                <div class="card mt-4">
                    <div class="card-body">
                        <canvas id="temperatureChart" width="800" height="400"></canvas>
                    </div>
                </div>

            <?php elseif ($historico === 'peso'): ?>
                <table class="table">
                        <thead>
                            <tr>
                                <th>Data de Atualização</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                                    //informação para os gráficos
                                        $dates = [];
                                        $pesos = [];


                                    foreach($dados as $data_temp){
                                        $valor_formatado = $data_temp['valor'] . ' kg';
                                        $dates[] = $data_temp['data'];
                                        $pesos[] = (float)$data_temp['valor'];
                        
                                        echo '
                                        <tr>
                                            <td>' . htmlspecialchars($data_temp['data']) . '</td>
                                            <td>' . htmlspecialchars($valor_formatado) . '</td>
                                        </tr>';
                                    }
                            ?>
                        </tbody>
                </table>
                <!-- Weight Chart -->
                <div class="card mt-4">
                    <div class="card-body">
                        <canvas id="weightChart" width="800" height="400"></canvas>
                    </div>
                </div>

            <?php elseif ($historico === 'luminosidade'): ?>
                <table class="table">
                        <thead>
                            <tr>
                                <th>Data de Atualização</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                                    //informação para os gráficos
                                        $dates = [];
                                        $luminosidades = [];


                                    foreach($dados as $data_temp){
                                        $valor_formatado = $data_temp['valor'] . ' kg';
                                        $dates[] = $data_temp['data'];
                                        $luminosidades[] = (float)$data_temp['valor'];
                        
                                        echo '
                                        <tr>
                                            <td>' . htmlspecialchars($data_temp['data']) . '</td>
                                            <td>' . htmlspecialchars($valor_formatado) . '</td>
                                        </tr>';
                                    }
                            ?>
                        </tbody>
                </table>
                <!-- Luminosidade Chart -->
                <div class="card mt-4">
                    <div class="card-body">
                        <canvas id="luminosidadeChart" width="800" height="400"></canvas>
                    </div>
                </div>

            <?php elseif ($historico === 'sensor_porta'): ?>
                <table class="table">
                        <thead>
                            <tr>
                                <th>Data de Atualização</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        <?php foreach ($dados as $linha): ?>
                            <tr>
                                <td><?= htmlspecialchars($linha['data']) ?></td>
                                <td><?= htmlspecialchars($linha['valor']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                </table>
                <!-- Porta Chart -->
                <div class="card mt-4">
                    <div class="card-body">
                        <canvas id="portaChart" width="800" height="400"></canvas>
                    </div>
                </div>
            <?php endif; ?>

        </div> <!-- .container-fluid -->
    </div> <!-- .main-content-area -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if ($historico === 'temperatura_torno'): ?>
<script>
const labels = <?php echo json_encode($dates); ?>;
const data = <?php echo json_encode($temperatures); ?>;

const ctx = document.getElementById('temperatureChart').getContext('2d');
const temperatureChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Temperatura (ºC)',
            data: data,
            fill: false,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                title: { display: true, text: 'Data' }
            },
            y: {
                title: { display: true, text: 'Temperatura (ºC)' }
            }
        }
    }
 });
</script>
<?php endif; ?>

<?php if ($historico === 'peso'): ?>
<script>
const labels = <?php echo json_encode($dates); ?>;
const data = <?php echo json_encode($pesos); ?>;

const ctx = document.getElementById('weightChart').getContext('2d');
const pesoChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Peso (kg)',
            data: data,
            fill: false,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                title: { display: true, text: 'Data' }
            },
            y: {
                title: { display: true, text: 'Peso (kg)' }
            }
        }
    }
 });
</script>
<?php endif; ?>

<?php if ($historico === 'luminosidade'): ?>
<script>
const labels = <?php echo json_encode($dates); ?>;
const data = <?php echo json_encode($luminosidades); ?>;

const ctx = document.getElementById('luminosidadeChart').getContext('2d');
const pesoChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Luminosidade (lumens)',
            data: data,
            fill: false,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                title: { display: true, text: 'Data' }
            },
            y: {
                title: { display: true, text: 'Luminosidade (lumens)' }
            }
        }
    }
 });
</script>
<?php endif; ?>

<?php if ($historico === 'sensor_porta'): ?>
<script>
const labels = <?php echo json_encode($dates); ?>;
const data = <?php echo json_encode($doorStates); ?>;

const ctx = document.getElementById('portaChart').getContext('2d');
const pesoChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Estado da Porta (1 = Aberta, 0 = Fechada)',
            data: data,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            fill: false,
            tension: 0.1,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return value === 1 ? 'Aberta' : 'Fechada';
                            },
                            stepSize: 1,
                            min: 0,
                            max: 1
                        },
                        title: {
                            display: true,
                            text: 'Estado da Porta'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Data e Hora'
                        }
                    }
                }
    }
 });
</script>
<?php endif; ?>

</body>
</html>
