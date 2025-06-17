<?php
// Iniciar ou retomar a sessão existente

session_start();


// Define o array $_SESSION como um array vazio, removendo todas as variáveis guardadas.
$_SESSION = array();


// Esta função remove todos os dados associados à sessão atual.
session_destroy();

$logout_message = urlencode("Logout efetuado com sucesso!"); // Codifica a mensagem para a URL
header("Location: login.php?success=" . $logout_message); // Redireciona para login.php com a mensagem
exit; 
?>