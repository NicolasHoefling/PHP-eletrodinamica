<?php

declare(strict_types=1);

namespace Eletrodinamica\Operacoes;

use Eletrodinamica\ResultadoCalculo;

final class CalculoTensao extends OperacaoEletrica
{
    public function __construct()
    {
        parent::__construct(
            'tensao',
            '2- Tensão elétrica',
            'Para o resistor ôhmico do modelo, a Lei de Ohm relaciona tensão, resistência e corrente.',
            'U = R × I',
            [
                ['nome' => 'resistencia', 'rotulo' => 'Resistência', 'unidade' => 'Ω', 'exemplo' => 20],
                ['nome' => 'corrente', 'rotulo' => 'Corrente elétrica', 'unidade' => 'A', 'exemplo' => 3],
            ]
        );
    }

    public function calcular(array $dados): ResultadoCalculo
    {
        $resistencia = $this->numero($dados, 'resistencia', 'Resistência');
        $corrente = $this->numero($dados, 'corrente', 'Corrente');
        $tensao = $resistencia * $corrente;

        return new ResultadoCalculo($tensao, 'V', $this->getFormula(), [
            "Resistência: {$resistencia} Ω; corrente: {$corrente} A.",
            "U = {$resistencia} × {$corrente}",
            "Tensão calculada: {$tensao} V.",
        ]);
    }
}
