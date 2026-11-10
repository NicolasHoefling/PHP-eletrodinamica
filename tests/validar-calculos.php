<?php

declare(strict_types=1);

$calculadora = require dirname(__DIR__) . '/bootstrap.php';
$verificacoes = 0;

function conferir(bool $condicao, string $descricao): void
{
    global $verificacoes;
    if (!$condicao) {
        throw new RuntimeException('Falhou: ' . $descricao);
    }
    $verificacoes++;
}

$corrente = $calculadora->calcular('corrente', ['carga' => 120, 'tempo' => 20]);
conferir(abs($corrente->getValor() - 6.0) < 1e-10, 'Exemplo do PDF: 120 C / 20 s = 6 A.');
conferir($corrente->toArray()['unidade'] === 'A', 'Unidade da corrente.');

$tensao = $calculadora->calcular('tensao', ['resistencia' => 20, 'corrente' => 3]);
conferir(abs($tensao->getValor() - 60.0) < 1e-10, 'Exemplo do PDF: 20 Ω × 3 A = 60 V.');
conferir($tensao->toArray()['unidade'] === 'V', 'Unidade da tensão.');

conferir($calculadora->calcular('corrente', ['carga' => 0, 'tempo' => 20])->getValor() === 0.0, 'Carga zero válida.');
conferir($calculadora->calcular('tensao', ['resistencia' => '2.5', 'corrente' => '0.4'])->getValor() === 1.0, 'Entradas decimais vindas do formulário.');

// Resultados de referência do material e equivalência das unidades.
$casos = [
    ['corrente', ['carga' => 100, 'tempo' => 20], 5, 'A'],
    ['corrente', ['carga' => 120, 'tempo' => 1, 'unidadeTempo' => 'min'], 2, 'A'],
    ['corrente', ['carga' => 3600, 'tempo' => 1, 'unidadeTempo' => 'h'], 1, 'A'],
    ['resistencia', ['tensao' => 100, 'corrente' => 5], 20, 'Ω'],
    ['potencia', ['modo' => 'ui', 'tensao' => 220, 'corrente' => 5], 1100, 'W'],
    ['potencia', ['modo' => 'ui', 'tensao' => 100, 'corrente' => 5], 500, 'W'],
    ['potencia', ['modo' => 'ri', 'resistencia' => 20, 'corrente' => 5], 500, 'W'],
    ['potencia', ['modo' => 'ur', 'tensao' => 100, 'resistencia' => 20], 500, 'W'],
    ['energia', ['potencia' => 1000, 'tempo' => 3600], 3600000, 'J'],
    ['energia', ['potencia' => 1, 'unidadePotencia' => 'kW', 'tempo' => 1, 'unidadeTempo' => 'h'], 3600000, 'J'],
    ['energia', ['potencia' => 1000, 'tempo' => 60, 'unidadeTempo' => 'min'], 3600000, 'J'],
    ['energia', ['potencia' => 100, 'tempo' => 5, 'unidadeTempo' => 'h'], 1800000, 'J'],
    ['energia', ['potencia' => 1000, 'tempo' => 0], 0, 'J'],
    ['trabalho', ['tensao' => 220, 'carga' => 10], 2200, 'J'],
    ['consumo', ['potencia' => 150, 'horas' => 4, 'dias' => 30], 18, 'kWh'],
    ['consumo', ['potencia' => 500, 'horas' => 4, 'dias' => 30], 60, 'kWh'],
    ['consumo', ['potencia' => 100, 'horas' => 5, 'dias' => 1], 0.5, 'kWh'],
    ['custo', ['energia' => 18, 'tarifa' => 0.90], 16.20, 'R$'],
    ['custo', ['energia' => 60, 'tarifa' => 0.90], 54, 'R$'],
    ['custo', ['energia' => 3600000, 'unidadeEnergia' => 'J', 'tarifa' => 0.90], 0.90, 'R$'],
];
foreach ($casos as [$id, $dados, $esperado, $unidade]) {
    $resultado = $calculadora->calcular($id, $dados)->toArray();
    conferir(abs($resultado['valor'] - $esperado) <= max(1e-10, abs($esperado) * 1e-12), 'Resultado de referência: ' . $id);
    conferir($resultado['unidade'] === $unidade, 'Unidade de referência: ' . $id);
}
$energia = $calculadora->calcular('energia', ['potencia' => 1000, 'tempo' => 3600])->toArray();
conferir($energia['complementos'][0]['valor'] === 1.0, '3.600.000 J equivalem a 1 kWh.');

$invalidos = [
    ['corrente', ['carga' => 120, 'tempo' => 0]],
    ['corrente', ['carga' => 'texto', 'tempo' => 20]],
    ['corrente', ['carga' => 120]],
    ['corrente', ['carga' => 120, 'tempo' => -1]],
    ['corrente', ['carga' => true, 'tempo' => 20]],
    ['tensao', ['resistencia' => -20, 'corrente' => 3]],
    ['tensao', ['resistencia' => '1e308', 'corrente' => '1e308']],
    ['inexistente', []],
    ['corrente', ['carga' => 120, 'tempo' => 20, 'unidadeTempo' => 'dias']],
    ['resistencia', ['tensao' => 100, 'corrente' => 0]],
    ['potencia', ['modo' => 'ur', 'tensao' => 100, 'resistencia' => 0]],
    ['potencia', ['modo' => 'inexistente']],
    ['potencia', ['modo' => ['ui']]],
    ['potencia', ['modo' => 'ri', 'resistencia' => 20]],
    ['energia', ['potencia' => 1, 'tempo' => 1, 'unidadePotencia' => 'MW']],
    ['energia', ['potencia' => 1, 'tempo' => 1, 'unidadeTempo' => 'dias']],
    ['energia', ['potencia' => '1e308', 'unidadePotencia' => 'kW', 'tempo' => 1]],
    ['consumo', ['potencia' => 500, 'horas' => 25, 'dias' => 30]],
    ['consumo', ['potencia' => 500, 'horas' => 4, 'dias' => 1.5]],
    ['consumo', ['potencia' => 500, 'horas' => 4, 'dias' => -1]],
    ['custo', ['energia' => 60, 'tarifa' => -0.90]],
    ['custo', ['energia' => 60, 'tarifa' => 0.90, 'unidadeEnergia' => 'W']],
];
foreach ($invalidos as [$operacao, $dados]) {
    $rejeitado = false;
    try {
        $calculadora->calcular($operacao, $dados);
    } catch (InvalidArgumentException $erro) {
        $rejeitado = true;
    }
    conferir($rejeitado, 'Rejeitar dados inválidos em ' . $operacao . ': ' . json_encode($dados));
}

echo "OK: {$verificacoes} verificações concluídas.\n";
