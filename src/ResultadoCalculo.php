<?php

declare(strict_types=1);

namespace Eletrodinamica;

use InvalidArgumentException;

final class ResultadoCalculo
{
    private float $valor;
    private string $unidade;
    private string $formula;
    private array $etapas;
    private array $complementos;

    public function __construct(float $valor, string $unidade, string $formula, array $etapas, array $complementos = [])
    {
        if (!is_finite($valor)) {
            throw new InvalidArgumentException('Os valores informados geram um resultado fora do limite numérico.');
        }
        $this->valor = $valor;
        $this->unidade = $unidade;
        $this->formula = $formula;
        $this->etapas = $etapas;
        $this->complementos = $complementos;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function toArray(): array
    {
        return [
            'valor' => $this->valor,
            'unidade' => $this->unidade,
            'formula' => $this->formula,
            'etapas' => $this->etapas,
            'complementos' => $this->complementos,
        ];
    }
}
