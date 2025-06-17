<?php
$currentPage = basename($_SERVER['PHP_SELF']);

?>
  <!-- Sidebar -->

 
   <div class="sidebar d-flex flex-column p-3">
 
 <ul class="nav nav-pills flex-column mb-auto">
   <li class="nav-item">
     <a href="dashboard.php" class="nav-link active" aria-current="page">
       <i class="fas fa-tachometer-alt me-2"></i> Dashboard
     </a>
   </li>
   <li>
     <a href="camera.php" class="nav-link text-white">
       <i class="fas fa-layer-group me-2"></i> Camera
     </a>
   </li>
   <li>
     <a href="historico.php" class="nav-link text-white">
       <i class="fas fa-file-alt me-2"></i> Histórico
     </a>
   </li>
   <li>
     <a href="graficos.php" class="nav-link text-white">
       <i class="fas fa-chart-bar me-2"></i> Gráficos
     </a>
   </li>
   <li>
     <a href="upload.php" class="nav-link text-white">
       <i class="fas fa-table me-2"></i> Upload
     </a>
   </li>
   <li>
     <a href="logout.php" class="nav-link text-white">
       <i class="fas fa-chart-bar me-2"></i> Logout
     </a>
   </li>
 </ul>
</div>
 