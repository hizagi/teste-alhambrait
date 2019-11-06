function buscarEstados(select_estados){
    axios({
        method: 'get',
        url: `http://127.0.0.1:8000/api/estados/`,
        responseType: 'json',
    })
    .then((response) => {
        console.log('resposta', response);
        preencher_select_estados(select_estados, response.data);
        requisicaoFinalizada();
    }).catch((err) => {
        console.error(err);
    });
}

function preencher_select_estados(select_estados, estados) {
    if(!select_estados) {
        return;
    }
    select_estados.removeAll();
    select_estados.add({
        value: "",
        text: "Selecione um Estado",
        disabled: true,
        selected: true
    });
    const estados_formatadas = estados.map((estado) => {
        return {
            value: estado.id,
            text: estado.nome
        };
    });
    select_estados.add(estados_formatadas);
}
