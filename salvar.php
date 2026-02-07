<?php
$data = json_decode(file_get_contents("php://input"), true);
if(!$data) exit("Erro ao salvar");

if(!is_dir("dados")) mkdir("dados");

$arquivo = "dados/{$data['pessoa']}_{$data['mes']}_{$data['ano']}.json";
file_put_contents($arquivo, json_encode($data['dados'], JSON_PRETTY_PRINT));

echo "Salvo com sucesso!";
