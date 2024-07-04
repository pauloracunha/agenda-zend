jQuery(function () {
    //TODO: Implementar mascaras com pontuações e manipular no backend
    $('.cpf').mask('00000000000', {reverse: true});
    $('.cnpj').mask('00000000000000', {reverse: true});
});
