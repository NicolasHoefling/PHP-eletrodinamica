# Calculadora de Eletrodinâmica

Projeto acadêmico do Trabalho I, com PHP orientado a objetos e interface em HTML, CSS e JavaScript.

## Funcionalidades

- Corrente média: `I = Q / t`, com carga em C e tempo em s.
- Tensão: `U = R × I`, com resistência em Ω e corrente em A.
- Resistência: `R = U / I`.
- Potência: `P = UI`, `P = RI²` ou `P = U²/R`.
- Energia: `E = Pt`, apresentada em J e kWh.
- Trabalho: `W = UQ`, em J.
- Consumo: potência em kW × horas diárias × dias.
- Custo: energia em kWh × tarifa informada em R$/kWh.
- Sair: encerra a calculadora e oferece a opção de voltar.
- Resultado com unidade, fórmula e resolução.
- Validação no PHP e navegação entre as oito operações.
- Conversões entre s/min/h, W/kW e J/kWh, nas operações correspondentes.

O modelo utiliza magnitudes não negativas e as relações resistivas do material. Divisores devem ser positivos. O consumo diário aceita de 0 a 24 horas e uma quantidade inteira de dias. O custo é uma estimativa do consumo, com tarifa definida pelo usuário.

## Como funciona

O formulário usa `method="post"` e envia seus campos diretamente para `public/index.php`. O PHP lê `$_POST`, seleciona a operação, valida os dados e renderiza o resultado. Não há API separada, JSON ou `fetch`.

O JavaScript apenas troca as abas e mostra os campos adequados à fórmula de potência. Sem JavaScript, os links do menu e os formulários continuam funcionando; em potência, preencha os dois campos da modalidade selecionada.

## Estrutura

```text
eletrodinamica/
├── INICIAR.bat                # Inicia no Windows com dois cliques
├── iniciar.php                # Localiza porta livre e abre o navegador
├── LEIA-ME.txt                # Instrucoes para o professor
├── bootstrap.php              # Carregamento das classes e registro das operações
├── public/                    # Pasta servida pelo servidor web
│   ├── index.php              # Interface e menu
│   └── assets/
│       ├── css/style.css
│       └── js/app.js
├── src/
│   ├── Calculadora.php
│   ├── ResultadoCalculo.php
│   ├── ConversorUnidades.php
│   └── Operacoes/
│       ├── OperacaoEletrica.php
│       ├── CalculoCorrente.php
│       └── CalculoTensao.php
├── tests/validar-calculos.php
└── docs/planejamento.md
```

`src/Operacoes/` também contém as classes de resistência, potência, energia, trabalho, consumo e custo. Cada classe define suas entradas, explicação e regra de cálculo.

## Rodar localmente

Requer PHP 8.0 ou superior. Não precisa de Composer nem de banco de dados nesta etapa.

No Windows, dê dois cliques em **INICIAR.bat**. O iniciador localiza o projeto pela própria pasta, detecta o PHP, escolhe uma porta livre entre 8000 e 8020 e abre o navegador. Mantenha a janela aberta; para encerrar o servidor, pressione Ctrl+C ou feche a janela.

O ZIP de entrega inclui PHP portátil para Windows 10/11 x64. Basta extrair o pacote inteiro antes de iniciar. Os requisitos e a instrução para computadores sem o runtime Visual C++ estão em `LEIA-ME.txt`. Na pasta de desenvolvimento, o iniciador também detecta PHP no PATH ou no XAMPP padrão.

Execução manual opcional, em um terminal na pasta do projeto, com PHP no PATH:

```powershell
php -S 127.0.0.1:8000 -t public
```

Abra http://127.0.0.1:8000 no navegador. Use Ctrl+C no terminal para encerrar o servidor.

O servidor embutido é destinado ao desenvolvimento local. O botão Sair encerra a interface da calculadora; o iniciador permanece aberto para permitir o retorno.

## Conferir os cálculos

Na pasta do projeto:

```powershell
& 'C:\xampp\php\php.exe' tests/validar-calculos.php
```

## POO na estrutura

- Abstração: `OperacaoEletrica` define o contrato `calcular()`.
- Herança: as oito operações derivam da classe abstrata.
- Polimorfismo: `Calculadora` chama `calcular()` na operação registrada.
- Encapsulamento: dados internos e resultado usam propriedades privadas.
- Composição: `Calculadora` reúne objetos de operação, e as operações utilizam um conversor de unidades.

Para adicionar uma operação, crie uma classe em `src/Operacoes/` que implemente o contrato e registre uma instância no `bootstrap.php`. A interface constrói o menu e os campos a partir das descrições das operações.
