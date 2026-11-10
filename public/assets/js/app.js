'use strict';

// Os formulários são enviados diretamente ao PHP. O JS melhora a navegação.
document.querySelectorAll('[data-operacao]').forEach((botao) => {
    botao.addEventListener('click', (evento) => {
        evento.preventDefault();
        document.querySelectorAll('[data-operacao]').forEach((aba) => {
            const selecionada = aba === botao;
            aba.classList.toggle('ativa', selecionada);
            aba.setAttribute('aria-pressed', String(selecionada));
            document.getElementById(aba.getAttribute('aria-controls')).hidden = !selecionada;
        });
        history.replaceState(null, '', botao.href);
    });
    botao.addEventListener('keydown', (evento) => {
        if (evento.key === ' ') {
            evento.preventDefault();
            botao.click();
        }
    });
});

// Em potência, cada fórmula precisa de apenas dois campos numéricos.
const formularioPotencia = document.querySelector('[data-calculo="potencia"]');
if (formularioPotencia) {
    const modo = formularioPotencia.querySelector('[name="modo"]');
    const atualizarCampos = () => {
        formularioPotencia.querySelectorAll('[data-modos]').forEach((campo) => {
            const visivel = campo.dataset.modos.split(' ').includes(modo.value);
            campo.hidden = !visivel;
            const entrada = campo.querySelector('input');
            entrada.disabled = !visivel;
            entrada.required = visivel;
        });
    };
    modo.addEventListener('change', atualizarCampos);
    atualizarCampos();
}
