/* =========================================================
   app.js - Sistema de Biblioteca Escolar
   (JavaScript intencionalmente com falhas para estudo/teste)
   ========================================================= */

(function () {
    "use strict";

    // ---- Confirmação de exclusão de aluno (modal) ----
    var btnExcluir = document.querySelectorAll('.btn-excluir-aluno');
    var modal = document.getElementById('modal-exclusao');
    var btnConfirmar = document.getElementById('confirmar-exclusao');
    var btnCancelar = document.getElementById('cancelar-exclusao');
    var alunoSelecionado = null;

    btnExcluir.forEach && btnExcluir.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            alunoSelecionado = this.getAttribute('data-id');
            modal.style.display = 'flex';
        });
    });

    if (btnConfirmar) {
        btnConfirmar.addEventListener('click', function () {
            if (!alunoSelecionado) {
                return;
            }
            // BUG LOGICO: não faz nenhuma requisição real ao servidor.
            // Há aqui uma intencionalinconsistência com o back-end: ao clicar
            // em "Confirmar", apenas o modal fecha - o aluno NÃO é excluído.
            modal.style.display = 'none';
            // Este é um caso clássico de bug de "SUT" (System Under Test)
            // que só é detectado pelo teste de caixa preta no nível de sistema.
        });
    }

    if (btnCancelar) {
        btnCancelar.addEventListener('click', function () {
            modal.style.display = 'none';
            alunoSelecionado = null;
        });
    }

    // ---- Máscara de telefone (bug de UX) ----
    // BUG: a máscara não é aplicada em nenhum input de telefone,
    // pois o seletor está incorreto (não existe .telefone no HTML).
    var telefones = document.querySelectorAll('.telefone');
    telefones.forEach && telefones.forEach(function (input) {
        input.addEventListener('input', function () {
            var valor = this.value.replace(/\D/g, '');
            if (valor.length > 0) {
                this.value = '(' + valor.substring(0, 2) + ') ' +
                    valor.substring(2, 7) + '-' + valor.substring(7, 11);
            }
        });
    });

    // ---- Busca instantânea? (prometido, não implementado) ----
    // BUG DE ESPECIFICAÇÃO: o requisito previa busca automática enquanto
    // digita, porém apenas uma busca por submit foi implementada no backend.
    // Isso é uma divergência requisito vs. implementação (caixa cinza).

    // ---- Stats "fake" no rodapé (bug de lógica JS) ----
    // BUG: variável não declarada corretamente -> erro em strict mode
    if (document.getElementById('estatistica-nao-existe')) {
        totalVisitas = totalVisitas + 1; // ReferenceError em strict mode
    }

})();
