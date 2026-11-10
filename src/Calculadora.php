<?php

declare(strict_types=1);

namespace Eletrodinamica;

use Eletrodinamica\Operacoes\OperacaoEletrica;
use InvalidArgumentException;

final class Calculadora
{
    private array $operacoes = [];

    public function __construct(OperacaoEletrica ...$operacoes)
    {
        foreach ($operacoes as $operacao) {
            $this->operacoes[$operacao->getId()] = $operacao;
        }
    }

    public function listarOperacoes(): array
    {
        return array_values(array_map(
            fn (OperacaoEletrica $operacao): array => $operacao->descrever(),
            $this->operacoes
        ));
    }

    public function calcular(string $id, array $dados): ResultadoCalculo
    {
        if (!isset($this->operacoes[$id])) {
            throw new InvalidArgumentException('Selecione uma operação disponível.');
        }

        // Polimorfismo: cada objeto implementa o mesmo método de sua maneira.
        return $this->operacoes[$id]->calcular($dados);
    }
}
