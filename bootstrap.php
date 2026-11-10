<?php

declare(strict_types=1);

use Eletrodinamica\Calculadora;
use Eletrodinamica\Operacoes\CalculoCorrente;
use Eletrodinamica\Operacoes\CalculoTensao;
use Eletrodinamica\Operacoes\CalculoResistencia;
use Eletrodinamica\Operacoes\CalculoPotencia;
use Eletrodinamica\Operacoes\CalculoEnergia;
use Eletrodinamica\Operacoes\CalculoTrabalho;
use Eletrodinamica\Operacoes\CalculoConsumo;
use Eletrodinamica\Operacoes\CalculoCusto;

spl_autoload_register(function (string $classe): void {
    $prefixo = 'Eletrodinamica\\';
    if (strpos($classe, $prefixo) !== 0) {
        return;
    }

    $arquivo = __DIR__ . '/src/'
        . str_replace('\\', '/', substr($classe, strlen($prefixo))) . '.php';

    if (is_file($arquivo)) {
        require $arquivo;
    }
});

// A calculadora recebe objetos pelo contrato comum OperacaoEletrica.
return new Calculadora(
    new CalculoCorrente(),
    new CalculoTensao(),
    new CalculoResistencia(),
    new CalculoPotencia(),
    new CalculoEnergia(),
    new CalculoTrabalho(),
    new CalculoConsumo(),
    new CalculoCusto()
);
