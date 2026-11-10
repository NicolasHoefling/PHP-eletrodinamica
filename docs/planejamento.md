# Planejamento

## Escopo atual

Fluxo concluído: formulário POST → PHP → objeto de operação → página com resultado explicado.
O JavaScript melhora a navegação e a seleção dos campos de potência; os cálculos também funcionam sem ele.

## Expansão do menu do trabalho

| Operação | Situação |
| --- | --- |
| Corrente | Implementada: I = Q/t, com seleção da unidade de tempo |
| Tensão | Implementada: U = RI |
| Resistência | Implementada: R = U/I, com validação do divisor |
| Potência | Implementada: UI, RI² ou U²/R |
| Energia | Implementada: E = Pt, com conversões e resultado em J/kWh |
| Trabalho | Implementada: W = UQ |
| Consumo | Implementada: potência, horas diárias e dias |
| Custo | Implementada: consumo em kWh e tarifa |
| Sair | Implementada: encerramento e retorno à calculadora |

## Conversões e situações educativas

`ConversorUnidades` centraliza s/min/h, W/kW e J/kWh. As fórmulas e as validações estão no PHP. Os exemplos preenchidos podem ser alterados para explorar cada situação.

Uma expansão opcional futura é construir demonstrações gráficas das relações entre tensão, resistência, corrente, potência e consumo.

## Critérios para cada nova operação

1. Explicar o significado físico e as unidades.
2. Definir dados de entrada e restrições da fórmula.
3. Implementar a classe e registrá-la na calculadora.
4. Conferir exemplos do material e dados inválidos.
5. Exibir o resultado e a resolução na interface.

## Anotação adicional encontrada na pasta

O arquivo `fazer sistema em poo.txt` menciona uma lanterna e resistores em série/paralelo. Essa ideia pode ser estudada como uma extensão futura, com valores de resistência e configuração do circuito definidos. Ela não faz parte do menu de grandezas elétricas concluído neste projeto.
