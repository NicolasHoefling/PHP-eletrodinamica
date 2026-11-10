<?php

declare(strict_types=1);

namespace Eletrodinamica\Operacoes;

use Eletrodinamica\ResultadoCalculo;

final class CalculoResistencia extends OperacaoEletrica
{
    public function __construct()
    {
        parent::__construct('resistencia', '3- Resistência',
            'A resistência relaciona a tensão aplicada à corrente em um componente. Com tensão fixa, uma resistência maior reduz a corrente.',
            'R = U / I', [
                ['nome' => 'tensao', 'rotulo' => 'Tensão elétrica', 'unidade' => 'V', 'exemplo' => 100],
                ['nome' => 'corrente', 'rotulo' => 'Corrente elétrica', 'unidade' => 'A', 'exemplo' => 5],
            ]);
    }

    public function calcular(array $dados): ResultadoCalculo
    {
        $tensao = $this->numero($dados, 'tensao', 'Tensão');
        $corrente = $this->numero($dados, 'corrente', 'Corrente', true);
        $resistencia = $tensao / $corrente;
        return new ResultadoCalculo($resistencia, 'Ω', $this->getFormula(), [
            "Tensão: {$tensao} V; corrente: {$corrente} A.",
            "R = {$tensao} / {$corrente}",
            "Resistência calculada: {$resistencia} Ω.",
        ]);
    }
}
