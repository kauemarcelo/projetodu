<?php
$pessoa = $_GET['pessoa'] ?? '';
$mes    = $_GET['mes'] ?? '';
$ano    = $_GET['ano'] ?? '';

$arquivo = "dados/{$pessoa}_{$mes}_{$ano}.json";

if(file_exists($arquivo)){
  echo file_get_contents($arquivo);
}else{
  echo json_encode([]);
}
