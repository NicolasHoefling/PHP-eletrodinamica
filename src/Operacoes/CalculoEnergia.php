<?php

declare(strict_types=1);

namespace Eletrodinamica\Operacoes;

use Eletrodinamica\ResultadoCalculo;

final class CalculoEnergia extends OperacaoEletrica
{
    public function __construct()
    {
        parent::__construct('energia', '5- Energia',
            'A energia depende da potência e do tempo de funcionamento. Para potência constante, dobrar o tempo dobra a energia. 1 kWh corresponde a 3.600.000 J.',
            'E = P × t', [
                ['nome' => 'potencia', 'rotulo' => 'Potência', 'exemplo' => 1000],
                ['nome' => 'unidadePotencia', 'rotulo' => 'Unidade da potência', 'opcoes' => ['W' => 'Watts (W)', 'kW' => 'Quilowatts (kW)'], 'exemplo' => 'W'],
                ['nome' => 'tempo', 'rotulo' => 'Tempo de funcionamento', 'exemplo' => 3600],
                ['nome' => 'unidadeTempo', 'rotulo' => 'Unidade do tempo', 'opcoes' => ['s' => 'Segundos (s)', 'min' => 'Minutos (min)', 'h' => 'Horas (h)'], 'exemplo' => 's'],
            ]);
    }

    public function calcular(array $dados): ResultadoCalculo
    {
        $potenciaInformada = $this->numero($dados, 'potencia', 'Potência');
        $tempoInformado = $this->numero($dados, 'tempo', 'Tempo');
        $unidadePotencia = $this->escolha($dados, 'unidadePotencia', ['W', 'kW'], 'W');
        $unidadeTempo = $this->escolha($dados, 'unidadeTempo', ['s', 'min', 'h'], 's');
        $potencia = $this->conversor->potenciaEmWatts($potenciaInformada, $unidadePotencia);
        $tempo = $this->conversor->tempoEmSegundos($tempoInformado, $unidadeTempo);
        $energia = $potencia * $tempo;
        return new ResultadoCalculo($energia, 'J', $this->getFormula(), [
            "Potência: {$potenciaInformada} {$unidadePotencia} = {$potencia} W; tempo: {$tempoInformado} {$unidadeTempo} = {$tempo} s.",
            "E = {$potencia} × {$tempo}",
            "Energia calculada: {$energia} J. Para converter em kWh, dividimos por 3.600.000.",
        ], [['valor' => $energia / 3600000, 'unidade' => 'kWh']]);
    }
}
