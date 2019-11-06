@extends('layout')
@section('head')
<link rel="stylesheet" type="text/css" href="css/selectr.min.css">
<script src="js/vanilla-masker.min.js"></script>
<script src="js/axios.min.js"></script>
<script src="js/selectr.min.js"></script>
<script src="js/animate_css.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('conteudo')
<div id="wrapper">
    <div id="page" class="container">
        <div class="row">
            <h1 class="titulo">Formulário de Cadastro</h1>
            <div class="container_progressbar">
                <ul class="progressbar">
                    <li {!! ($passo_atual>= 1) ? 'class="active"':'' !!}>1ª Etapa</li>
                    <li {!! ($passo_atual>= 2) ? 'class="active"':'' !!}>2ª Etapa</li>
                    <li {!! ($passo_atual>= 3) ? 'class="active"':'' !!}>3ª Etapa</li>
                </ul>
            </div>
            <div class="col-12">
                <form action="" method="post">
                    @if ($passo_atual == 1)
                    <div class="form-group">
                        <label for="input_nome">Nome:</label>
                        <input type="text" name="nome" class="form-control" id="input_nome" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label for="input_data_nascimento">Data Nascimento:</label>
                        <input type="text" name="data_nascimento" class="form-control" id="input_data_nascimento"
                            maxlength="10">
                    </div>
                    @endif
                    @if ($passo_atual == 2)
                    <div class="form-row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="input_estado">Estado:</label>
                                <select name="id_estado" class="form-control" id="input_estado">
                                        <option selected disabled>Selecione um estado</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="input_cidade">Cidade:</label>
                                <select name="id_cidade" class="form-control" id="input_cidade">
                                        <option selected disabled>Selecione uma cidade</option>
                                </select>
                                <small class="text-muted">
                                    Selecione o estado antes de selecionar a cidade.
                                </small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="input_rua">Rua:</label>
                                <input type="text" name="rua" class="form-control" id="input_rua" maxlength="255">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="input_numero">Número:</label>
                                <input type="text" name="numero" class="form-control" id="input_numero" maxlength="10">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="input_cep">CEP:</label>
                                <input type="text" name="cep" class="form-control" id="input_cep" maxlength="9">
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ($passo_atual != 1)
                    <button class="btn btn-secondary" id="anterior">Anterior</button>
                    @endif
                    <button class="btn btn-primary btn-direita" id="proximo">
                        @if ($passo_atual != 3)
                            Próximo
                        @endif
                        @if ($passo_atual == 3)
                            Finalizar
                        @endif
                    </button>
                    <button disabled class="btn btn-primary btn-direita" id="aguardando"><i
                            class="fa fa-spinner fa-spin"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
        axios.interceptors.request.use((config) => {
            aguardandoResposta();
            return config;
        });

        const messagem_select_traduzida = {
            noResults: "Nenhum resultado Encontrado.",
            noOptions: "Nenhuma opção disponível"
        };

        if(document.getElementById('input_data_nascimento')){
            VMasker(document.getElementById('input_data_nascimento')).maskPattern('99/99/9999');
        }
        if(document.getElementById('input_cep')){
            VMasker(document.getElementById('input_cep')).maskPattern('99999-999');
        }
        if(document.getElementById('input_estado')){
            var input_estado = new Selectr(document.getElementById('input_estado'), {
                messages: messagem_select_traduzida
            });
            input_estado.on('selectr.select', function(option) {
                buscarCidades(option.value);
            });
        }
        if(document.getElementById('input_cidade')){
            var input_cidade = new Selectr(document.getElementById('input_cidade'), {
                messages: messagem_select_traduzida
            });
        }


        document.getElementById('proximo').addEventListener('click', (evt) => {
            evt.preventDefault();
            inputs_validos = validarInputs(document.querySelectorAll('input, select'));
            const json_input = {};
            inputs_validos.forEach((input) => {
                console.log('INPUT ITERACAO', input, input.tagName === 'SELECT', input[0].value);
                if(input.name === 'cep') {
                    json_input[input.name] = VMasker.toPattern(input.value, '99999999');
                } else if (input.tagName === 'SELECT' && input[0] && input.value !== input[0].value) {
                    json_input[input.name] = '';
                } else {
                    json_input[input.name] = input.value;
                }
            });
            console.log(inputs_validos, json_input);
            enviarDados(json_input);
        });

        function validarInputs(inputs){
            return [...inputs].filter((input) => input.name && input.name !== '_method' && !input.disabled && input.type !== 'reset' && input.type !== 'submit' && input.type !== 'button')
        }

        function enviarDados(json_input){
            axios({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                method: 'post',
                url: `http://127.0.0.1:8000/api/clientes`,
                responseType: 'json',
                data: json_input
            })
            .then((response) => {
                validarErros([]);
                requisicaoFinalizada();
                console.log('resposta', response);
                animateCSS('#page', 'fadeInRight');
            }).catch((err) => {
                requisicaoFinalizada();
                console.error(err.response.data);
                const erros = err.response.data.errors;
                validarErros(erros);
            });
        }

        function buscarCidades(id_estado) {
            axios({
                method: 'get',
                url: `http://127.0.0.1:8000/api/estados/${id_estado}/cidades`,
                responseType: 'json',
            })
            .then((response) => {
                console.log('resposta', response);
                preencher_select_cidades(response.data);
                requisicaoFinalizada();
            }).catch((err) => {
                console.error(err);
            });
        }

        function preencher_select_cidades(cidades){
            input_cidade.removeAll();
            input_cidade.add({
                value: "",
                text: "Selecione uma cidade",
                disabled: true,
                selected: true
            });
            const cidades_formatadas = cidades.map((cidade) => {
                return {
                    value: cidade.id,
                    text: cidade.nome
                };
            });
            input_cidade.add(cidades_formatadas);
        }

        function validarErros(erros){
            limparErros();
            const nomes_erros = Object.keys(erros);
            const inputs = validarInputs(document.querySelectorAll('input, select'));
            console.log('validar erros', erros, inputs);
            inputs.forEach((input) => {
                const contem_erro = nomes_erros.find((erro) => erro === input.name);
                if(contem_erro) {
                    input.classList.add('is-invalid');
                    const avisoHTML = document.createElement('div');
                    erros[contem_erro].forEach((msg_erro) => {
                        avisoHTML.innerHTML = `${msg_erro}`;
                        avisoHTML.classList.add('invalid-feedback');
                        input.parentNode.appendChild(avisoHTML);
                    });
                } else {
                    input.classList.remove('is-invalid');
                }
            });
        }

        function limparErros(){
            document.querySelectorAll('[class$="feedback"]').forEach(el => el.remove());
        }

        function aguardandoResposta() {
            document.getElementById("proximo").style.display = "none";
            document.getElementById("aguardando").style.display = "block";
        }

        function requisicaoFinalizada() {
            document.getElementById("proximo").style.display = "block";
            document.getElementById("aguardando").style.display = "none";
        }

</script>
@endsection
