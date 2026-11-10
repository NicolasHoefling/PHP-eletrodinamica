<?php

declare(strict_types=1);

namespace Eletrodinamica\Operacoes;

use Eletrodinamica\ResultadoCalculo;
use InvalidArgumentException;

final class CalculoConsumo extends OperacaoEletrica
{
    public function __construct()
    {
        parent::__construct('consumo', '7- Consumo em kWh',
            'Estime o consumo de um aparelho com potência e tempo de uso diário constantes. Primeiro converta W para kW; depois multiplique pelas horas diárias e pelo número de dias.',
            'E = (P / 1000) × horas por dia × dias', [
                ['nome' => 'potencia', 'rotulo' => 'Potência do equipamento', 'unidade' => 'W', 'exemplo' => 500],
                ['nome' => 'horas', 'rotulo' => 'Tempo de uso por dia', 'unidade' => 'h/dia', 'exemplo' => 4, 'max' => 24],
                ['nome' => 'dias', 'rotulo' => 'Quantidade de dias', 'unidade' => 'dias', 'exemplo' => 30, 'step' => 1],
            ]);
    }

    public function calcular(array $dados): ResultadoCalculo
    {
        $potencia = $this->numero($dados, 'potencia', 'Potência');
        $horas = $this->numero($dados, 'horas', 'Horas por dia');
        $dias = $this->numero($dados, 'dias', 'Dias');
        if ($horas > 24) {
            throw new InvalidArgumentException('O tempo de uso diário deve ficar entre 0 e 24 horas.');
        }
        if (floor($dias) !== $dias) {
            throw new InvalidArgumentException('Informe uma quantidade inteira de dias.');
        }
        $potenciaKw = $potencia / 1000;
        $consumo = $potenciaKw * $horas * $dias;
        return new ResultadoCalculo($consumo, 'kWh', $this->getFormula(), [
            "Converter potência: {$potencia} W / 1000 = {$potenciaKw} kW.",
            "E = {$potenciaKw} × {$horas} × {$dias}",
            "Consumo estimado no período: {$consumo} kWh.",
        ]);
    }
}
