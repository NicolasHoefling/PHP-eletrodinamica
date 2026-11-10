<?php

declare(strict_types=1);

namespace Eletrodinamica\Operacoes;

use Eletrodinamica\ResultadoCalculo;

final class CalculoTrabalho extends OperacaoEletrica
{
    public function __construct()
    {
        parent::__construct('trabalho', '6- Trabalho elétrico',
            'O trabalho representa a energia transferida ao transportar uma carga através de uma diferença de potencial. Aqui W é o símbolo do trabalho; sua unidade é o joule (J).',
            'W = U × Q', [
                ['nome' => 'tensao', 'rotulo' => 'Tensão elétrica', 'unidade' => 'V', 'exemplo' => 220],
                ['nome' => 'carga', 'rotulo' => 'Carga elétrica', 'unidade' => 'C', 'exemplo' => 10],
            ]);
    }

    public function calcular(array $dados): ResultadoCalculo
    {
        $tensao = $this->numero($dados, 'tensao', 'Tensão');
        $carga = $this->numero($dados, 'carga', 'Carga');
        $trabalho = $tensao * $carga;
        return new ResultadoCalculo($trabalho, 'J', $this->getFormula(), [
            "Tensão: {$tensao} V; carga: {$carga} C.",
            "W = {$tensao} × {$carga}",
            "Trabalho elétrico: {$trabalho} J de energia transferida.",
        ]);
    }
}
