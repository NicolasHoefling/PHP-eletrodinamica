<?php

declare(strict_types=1);

namespace Eletrodinamica\Operacoes;

use Eletrodinamica\ResultadoCalculo;

final class CalculoPotencia extends OperacaoEletrica
{
    public function __construct()
    {
        parent::__construct('potencia', '4- Potência',
            'A potência mede a energia transformada por segundo: 1 W = 1 J/s. Escolha os dados conhecidos. As três relações se aplicam ao modelo resistivo do material.',
            'P = U × I   |   P = R × I²   |   P = U² / R', [
                ['nome' => 'modo', 'rotulo' => 'Quais dados você possui?', 'opcoes' => ['ui' => 'Tensão e corrente', 'ri' => 'Resistência e corrente', 'ur' => 'Tensão e resistência'], 'exemplo' => 'ui'],
                ['nome' => 'tensao', 'rotulo' => 'Tensão elétrica', 'unidade' => 'V', 'exemplo' => 220, 'modos' => ['ui', 'ur']],
                ['nome' => 'corrente', 'rotulo' => 'Corrente elétrica', 'unidade' => 'A', 'exemplo' => 5, 'modos' => ['ui', 'ri']],
                ['nome' => 'resistencia', 'rotulo' => 'Resistência', 'unidade' => 'Ω', 'exemplo' => 20, 'modos' => ['ri', 'ur']],
            ]);
    }

    public function calcular(array $dados): ResultadoCalculo
    {
        $modo = $this->escolha($dados, 'modo', ['ui', 'ri', 'ur'], 'ui');
        if ($modo === 'ui') {
            $tensao = $this->numero($dados, 'tensao', 'Tensão');
            $corrente = $this->numero($dados, 'corrente', 'Corrente');
            $valor = $tensao * $corrente;
            $formula = 'P = U × I';
            $substituicao = "P = {$tensao} V × {$corrente} A";
        } elseif ($modo === 'ri') {
            $resistencia = $this->numero($dados, 'resistencia', 'Resistência');
            $corrente = $this->numero($dados, 'corrente', 'Corrente');
            $valor = $resistencia * $corrente ** 2;
            $formula = 'P = R × I²';
            $substituicao = "P = {$resistencia} Ω × ({$corrente} A)²";
        } else {
            $tensao = $this->numero($dados, 'tensao', 'Tensão');
            $resistencia = $this->numero($dados, 'resistencia', 'Resistência', true);
            // Esta ordem reduz estouro intermediário desnecessário de U².
            $valor = ($tensao / $resistencia) * $tensao;
            $formula = 'P = U² / R';
            $substituicao = "P = ({$tensao} V)² / {$resistencia} Ω";
        }
        return new ResultadoCalculo($valor, 'W', $formula, [
            $substituicao,
            "Potência calculada: {$valor} W.",
            "Nesse regime, são transformados {$valor} J de energia por segundo.",
        ]);
    }
}
