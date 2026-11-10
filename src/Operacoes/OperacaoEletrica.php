<?php

declare(strict_types=1);

namespace Eletrodinamica\Operacoes;

use Eletrodinamica\ResultadoCalculo;
use Eletrodinamica\ConversorUnidades;
use InvalidArgumentException;

abstract class OperacaoEletrica
{
    private string $id;
    private string $titulo;
    private string $descricao;
    private string $formula;
    private array $campos;
    protected ConversorUnidades $conversor;

    protected function __construct(
        string $id,
        string $titulo,
        string $descricao,
        string $formula,
        array $campos
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->formula = $formula;
        $this->campos = $campos;
        $this->conversor = new ConversorUnidades();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFormula(): string
    {
        return $this->formula;
    }

    public function descrever(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'formula' => $this->formula,
            'campos' => $this->campos,
        ];
    }

    abstract public function calcular(array $dados): ResultadoCalculo;

    protected function escolha(array $dados, string $campo, array $opcoes, string $padrao): string
    {
        $valor = $dados[$campo] ?? $padrao;
        if (!is_string($valor) || !in_array($valor, $opcoes, true)) {
            throw new InvalidArgumentException('Selecione uma opção válida para ' . $campo . '.');
        }
        return $valor;
    }

    // Esta versão didática trabalha com valores de magnitude não negativos.
    protected function numero(
        array $dados,
        string $campo,
        string $rotulo,
        bool $exigirPositivo = false
    ): float {
        $valor = $dados[$campo] ?? null;
        if (!is_int($valor) && !is_float($valor) && !is_string($valor)) {
            throw new InvalidArgumentException($rotulo . ': informe um número.');
        }
        if (!is_numeric($valor)) {
            throw new InvalidArgumentException($rotulo . ': informe um número válido.');
        }

        $numero = (float) $valor;
        if (!is_finite($numero)) {
            throw new InvalidArgumentException($rotulo . ': o número deve ser finito.');
        }
        if ($exigirPositivo ? $numero <= 0 : $numero < 0) {
            $regra = $exigirPositivo ? 'maior que zero' : 'maior ou igual a zero';
            throw new InvalidArgumentException($rotulo . ': informe um valor ' . $regra . '.');
        }

        return $numero;
    }
}
