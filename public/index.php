<?php

declare(strict_types=1);

$calculadora = require dirname(__DIR__) . '/bootstrap.php';
$operacoes = $calculadora->listarOperacoes();
$ids = array_column($operacoes, 'id');
$selecionada = $_POST['operacao'] ?? $_GET['operacao'] ?? $ids[0];
if (!is_string($selecionada) || !in_array($selecionada, $ids, true)) {
    $selecionada = $ids[0];
}
$encerrada = ($_GET['encerrar'] ?? '') === '1';
$dados = [];
$resultado = null;
$erro = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $dados = $_POST;
    try {
        if (!is_string($_POST['operacao'] ?? null)) {
            throw new InvalidArgumentException('Selecione uma operação disponível.');
        }
        $resultado = $calculadora->calcular($_POST['operacao'], $dados)->toArray();
    } catch (InvalidArgumentException $excecao) {
        $erro = $excecao->getMessage();
    } catch (Throwable $excecao) {
        error_log((string) $excecao);
        $erro = 'Não foi possível concluir o cálculo. Confira os valores informados.';
    }
}

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function formatarNumero(float $valor, string $unidade): string
{
    if ($unidade === 'R$') {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
    if ($valor != 0 && (abs($valor) < 0.000001 || abs($valor) >= 1e12)) {
        $texto = str_replace('.', ',', sprintf('%.6g', $valor));
    } else {
        $texto = rtrim(rtrim(number_format($valor, 6, ',', '.'), '0'), ',');
    }
    return $texto . ' ' . $unidade;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eletrodinâmica | Calculadora</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/app.js" defer></script>
</head>

<body>
    <header class="cabecalho">
        <span class="nome-etapa">Nícolas Hoefling de C</span>
        <span class="identidade">Eletrodinâmica</span>
        <span class="nome-etapa">Trabalho I · Tecnicas De Programação</span>
    </header>
    <main>
        <section class="introducao">
            <h1>Conceitos e Resoluçoes de eletrodinamica<br></h1>
        </section>
        <?php if ($encerrada): ?>
            <section class="encerramento">
                <h2>Calculadora encerrada</h2>
                <p>Você pode iniciar novos cálculos quando quiser.</p>
                <a class="aba" href="./">Voltar à calculadora</a>
            </section>
        <?php else: ?>
        <nav class="menu" aria-label="Operações da calculadora">
            <?php foreach ($operacoes as $indice => $operacao): ?>
                <a href="?operacao=<?= escapar($operacao['id']) ?>" role="button" class="aba <?= $selecionada === $operacao['id'] ? 'ativa' : '' ?>"
                    data-operacao="<?= escapar($operacao['id']) ?>"
                    aria-controls="painel-<?= escapar($operacao['id']) ?>"
                    aria-pressed="<?= $selecionada === $operacao['id'] ? 'true' : 'false' ?>">
                    <?= escapar($operacao['titulo']) ?>
                </a>
            <?php endforeach; ?>
            <a class="aba" href="?encerrar=1">Sair</a>
        </nav>
        <noscript>
            <p>Os cálculos funcionam sem JavaScript. Em potência, preencha os dois valores correspondentes à fórmula escolhida.</p>
        </noscript>
        <?php foreach ($operacoes as $indice => $operacao): ?>
            <?php $ativa = $selecionada === $operacao['id']; ?>
            <section class="painel" id="painel-<?= escapar($operacao['id']) ?>" <?= !$ativa ? 'hidden' : '' ?>>
                <div class="conceito">
                    <p class="categoria">O CONCEITO</p>
                    <h2><?= escapar($operacao['titulo']) ?></h2>
                    <p><?= escapar($operacao['descricao']) ?></p>
                    <p class="formula"><?= escapar($operacao['formula']) ?></p>
                    <p class="nota">Os valores abaixo vêm dos exemplos do material. Altere-os para explorar outras situações.</p>
                </div>
                <form class="calculadora" method="post" action="./" data-calculo="<?= escapar($operacao['id']) ?>">
                    <input type="hidden" name="operacao" value="<?= escapar($operacao['id']) ?>">
                    <?php foreach ($operacao['campos'] as $campo): ?>
                        <?php
                        $valor = $ativa && array_key_exists($campo['nome'], $dados)
                            ? $dados[$campo['nome']] : $campo['exemplo'];
                        $valor = is_scalar($valor) ? (string) $valor : '';
                        ?>
                        <div class="campo" <?= isset($campo['modos']) ? 'data-modos="' . escapar(implode(' ', $campo['modos'])) . '"' : '' ?>>
                        <label for="<?= escapar($operacao['id'] . '-' . $campo['nome']) ?>">
                            <?= escapar($campo['rotulo']) ?>
                            <?php if (isset($campo['unidade'])): ?><span>(<?= escapar($campo['unidade']) ?>)</span><?php endif; ?>
                        </label>
                        <?php if (isset($campo['opcoes'])): ?>
                        <select id="<?= escapar($operacao['id'] . '-' . $campo['nome']) ?>" name="<?= escapar($campo['nome']) ?>">
                            <?php foreach ($campo['opcoes'] as $opcao => $rotulo): ?>
                            <option value="<?= escapar($opcao) ?>" <?= $valor === $opcao ? 'selected' : '' ?>><?= escapar($rotulo) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php else: ?>
                        <input id="<?= escapar($operacao['id'] . '-' . $campo['nome']) ?>"
                            name="<?= escapar($campo['nome']) ?>" type="number" step="<?= escapar((string) ($campo['step'] ?? 'any')) ?>" min="0"
                            <?= isset($campo['max']) ? 'max="' . escapar((string) $campo['max']) . '"' : '' ?>
                            value="<?= escapar($valor) ?>" <?= !isset($campo['modos']) ? 'required' : '' ?>>
                        <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="calcular">Calcular e entender</button>
                    <div class="resposta" aria-live="polite" aria-atomic="true">
                        <?php if ($ativa && $erro !== null): ?>
                        <p class="erro" role="alert"><?= escapar($erro) ?></p>
                        <?php elseif ($ativa && $resultado !== null): ?>
                        <p class="valor"><?= escapar(formatarNumero($resultado['valor'], $resultado['unidade'])) ?></p>
                        <?php foreach ($resultado['complementos'] as $complemento): ?>
                        <p>Equivalente a <?= escapar(formatarNumero($complemento['valor'], $complemento['unidade'])) ?>.</p>
                        <?php endforeach; ?>
                        <p>Fórmula: <?= escapar($resultado['formula']) ?></p>
                        <ol>
                            <?php foreach ($resultado['etapas'] as $etapa): ?>
                            <li><?= escapar($etapa) ?></li>
                            <?php endforeach; ?>
                        </ol>
                        <?php else: ?>
                        <p class="nota">O resultado e sua resolução aparecerão aqui.</p>
                        <?php endif; ?>
                    </div>
                </form>
            </section>
        <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>

</html>
