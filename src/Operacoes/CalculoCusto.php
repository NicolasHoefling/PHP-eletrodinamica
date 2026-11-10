<?php

declare(strict_types=1);

namespace Eletrodinamica\Operacoes;

use Eletrodinamica\ResultadoCalculo;

final class CalculoCusto extends OperacaoEletrica
{
    public function __construct()
    {
        parent::__construct('custo', '8- Custo da energia',
            'Multiplique a energia consumida pela tarifa informada. Este resultado estima o custo do consumo; componentes adicionais da conta não estão incluídos. A tarifa de R$ 0,90 é apenas o exemplo do material.',
            'Custo = energia em kWh × tarifa', [
                ['nome' => 'energia', 'rotulo' => 'Energia consumida', 'exemplo' => 60],
                ['nome' => 'unidadeEnergia', 'rotulo' => 'Unidade da energia', 'opcoes' => ['kWh' => 'Quilowatt-hora (kWh)', 'J' => 'Joules (J)'], 'exemplo' => 'kWh'],
                ['nome' => 'tarifa', 'rotulo' => 'Tarifa de energia', 'unidade' => 'R$/kWh', 'exemplo' => 0.90],
            ]);
    }

    public function calcular(array $dados): ResultadoCalculo
    {
        $energiaInformada = $this->numero($dados, 'energia', 'Energia');
        $tarifa = $this->numero($dados, 'tarifa', 'Tarifa');
        $unidade = $this->escolha($dados, 'unidadeEnergia', ['kWh', 'J'], 'kWh');
        $energia = $this->conversor->energiaEmKwh($energiaInformada, $unidade);
        $custo = $energia * $tarifa;
        return new ResultadoCalculo($custo, 'R$', $this->getFormula(), [
            "Energia: {$energiaInformada} {$unidade} = {$energia} kWh; tarifa: {$tarifa} R$/kWh.",
            "Custo = {$energia} × {$tarifa}",
            'O valor apresentado foi arredondado para duas casas decimais.',
        ]);
    }
}
