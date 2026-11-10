<?php

declare(strict_types=1);

namespace Eletrodinamica;

use InvalidArgumentException;

final class ConversorUnidades
{
    public function tempoEmSegundos(float $valor, string $unidade): float
    {
        return $this->converter($valor, $unidade, ['s' => 1, 'min' => 60, 'h' => 3600]);
    }

    public function potenciaEmWatts(float $valor, string $unidade): float
    {
        return $this->converter($valor, $unidade, ['W' => 1, 'kW' => 1000]);
    }

    public function energiaEmKwh(float $valor, string $unidade): float
    {
        return $this->converter($valor, $unidade, ['kWh' => 1, 'J' => 1 / 3600000]);
    }

    private function converter(float $valor, string $unidade, array $fatores): float
    {
        if (!isset($fatores[$unidade])) {
            throw new InvalidArgumentException('Selecione uma unidade disponível.');
        }
        $resultado = $valor * $fatores[$unidade];
        if (!is_finite($resultado)) {
            throw new InvalidArgumentException('O valor é grande demais para a conversão.');
        }
        return $resultado;
    }
}
