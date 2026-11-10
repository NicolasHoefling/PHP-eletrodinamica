<?php

declare(strict_types=1);

namespace Eletrodinamica\Operacoes;

use Eletrodinamica\ResultadoCalculo;

final class CalculoCorrente extends OperacaoEletrica
{
    public function __construct()
    {
        parent::__construct(
            'corrente',
            '1- Corrente elétrica',
            'A corrente média indica a quantidade de carga que atravessa o condutor por segundo.',
            'I = Q / t',
            [
                ['nome' => 'carga', 'rotulo' => 'Carga elétrica', 'unidade' => 'C', 'exemplo' => 120],
                ['nome' => 'tempo', 'rotulo' => 'Intervalo de tempo', 'exemplo' => 20],
                ['nome' => 'unidadeTempo', 'rotulo' => 'Unidade do tempo', 'opcoes' => ['s' => 'Segundos (s)', 'min' => 'Minutos (min)', 'h' => 'Horas (h)'], 'exemplo' => 's'],
            ]
        );
    }

    public function calcular(array $dados): ResultadoCalculo
    {
        $carga = $this->numero($dados, 'carga', 'Carga');
        $tempoInformado = $this->numero($dados, 'tempo', 'Tempo', true);
        $unidade = $this->escolha($dados, 'unidadeTempo', ['s', 'min', 'h'], 's');
        $tempo = $this->conversor->tempoEmSegundos($tempoInformado, $unidade);
        $corrente = $carga / $tempo;

        return new ResultadoCalculo($corrente, 'A', $this->getFormula(), [
            "Carga: {$carga} C; intervalo: {$tempoInformado} {$unidade} = {$tempo} s.",
            "I = {$carga} / {$tempo}",
            "Corrente média: {$corrente} A, isto é, {$corrente} C por segundo.",
        ]);
    }
}
