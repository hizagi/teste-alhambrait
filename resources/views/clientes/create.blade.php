@extends('layout')
@section('head')
<link rel="stylesheet" type="text/css" href="css/selectr.min.css">
<link rel="stylesheet" type="text/css" href="css/vanillatoasts.min.css">
<script src="js/vanilla-masker.min.js"></script>
<script src="js/axios.min.js"></script>
<script src="js/selectr.min.js"></script>
<script src="js/animate_css.js"></script>
<script src="js/vanillatoasts.min.js"></script>
<script src="js/servico.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('conteudo')
<div id="wrapper">
    <div id="page" class="container">
        <div class="row">
            <h1 class="titulo">Formulário de Cadastro</h1>
            <div class="container_progressbar">
                <ul class="progressbar">
                    <li class="passo_1 {!! ($passo_atual>= 1) ? 'active':'' !!}">1ª Passo</li>
                    <li class="passo_2 {!! ($passo_atual>= 2) ? 'active':'' !!}">2ª Passo</li>
                    <li class="passo_3 {!! ($passo_atual>= 3) ? 'active':'' !!}">3ª Passo</li>
                </ul>
            </div>
            <div class="col-12">
                <form action="" method="post">
                    @if ($passo_atual == 1)
                    <div class="form-row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="input_nome">Nome:</label>
                                <input type="text" name="nome" class="form-control" id="input_nome" maxlength="255">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="input_data_nascimento">Data Nascimento:</label>
                                <input type="text" name="data_nascimento" class="form-control"
                                    id="input_data_nascimento" maxlength="10">
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ($passo_atual == 2)
                    <div class="form-row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="input_estado">Estado:</label>
                                <select name="estado" class="form-control" id="input_estado">
                                    <option selected disabled value="">Selecione um estado</option>
                                    @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}">{{ $estado->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="input_cidade">Cidade:</label>
                                <select name="cidade" class="form-control" id="input_cidade">
                                    <option selected disabled value="">Selecione uma cidade</option>
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
                                <input type="number" name="numero" class="form-control" id="input_numero">
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
                    @if ($passo_atual == 3)
                    <div class="form-row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="input_telefone_fixo">Telefone Fixo:</label>
                                <input type="text" name="telefone_fixo" class="form-control" id="input_telefone_fixo"
                                    maxlength="14">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="input_telefone_celular">Telefone Celular:</label>
                                <input type="text" name="telefone_celular" class="form-control"
                                    id="input_telefone_celular" maxlength="15">
                            </div>
                        </div>
                    </div>
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
        //VARIAVEIS GLOBAIS
        var input_estado;
        var input_cidade;
        function registrarEventos () {

            if(document.getElementById('input_data_nascimento')){
                VMasker(document.getElementById('input_data_nascimento')).maskPattern('99/99/9999');
            }
            if(document.getElementById('input_cep')){
                VMasker(document.getElementById('input_cep')).maskPattern('99999-999');
            }
            if(document.getElementById('input_telefone_fixo')){
                VMasker(document.getElementById('input_telefone_fixo')).maskPattern('(99) 9999-9999');
            }
            if(document.getElementById('input_telefone_celular')){
                VMasker(document.getElementById('input_telefone_celular')).maskPattern('(99) 99999-9999');
            }
            if(document.getElementById('input_estado')){
                input_estado = new Selectr(document.getElementById('input_estado'), {
                    messages: messagem_select_traduzida
                });
                input_estado.on('selectr.select', function(option) {
                    if(option && option.value) {
                        buscarCidades(option.value);
                    }
                });
            }
            if(document.getElementById('input_cidade')){
                input_cidade = new Selectr(document.getElementById('input_cidade'), {
                    messages: messagem_select_traduzida
                });
            }


            document.getElementById('proximo').addEventListener('click', (evt) => {
                evt.preventDefault();
                inputs_validos = validarInputs(document.querySelectorAll('input, select'));
                const json_input = {};
                inputs_validos.forEach((input) => {
                    if(input.name === 'cep' || input.name === 'telefone_fixo' || input.name === 'telefone_celular') {
                        json_input[input.name] = VMasker.toPattern(input.value, '99999999999999999999');
                    } else {
                        json_input[input.name] = input.value;
                    }
                });
                enviarDados(json_input);
            });
        }

        registrarEventos();

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
                VanillaToasts.create({
                    title: 'Dados cadastrados com sucesso!',
                    type: 'success',
                    timeout: 2500,
                });
                irParaPasso(response.data.passo_atual);
            }).catch((err) => {
                VanillaToasts.create({
                    title: 'Ocorreu um erro!',
                    type: 'error',
                    timeout: 2500,
                });
                requisicaoFinalizada();
                console.error(err);
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
            inputs.forEach((input) => {
                const contem_erro = nomes_erros.find((erro) => erro === input.name);
                if(contem_erro) {
                    if(input.tagName == "SELECT"){
                        input.previousSibling.previousSibling.classList.add('is-invalid');
                    }
                    input.classList.add('is-invalid');
                    const avisoHTML = document.createElement('div');
                    erros[contem_erro].forEach((msg_erro) => {
                        avisoHTML.innerHTML = `${msg_erro}`;
                        avisoHTML.classList.add('invalid-feedback');
                        input.parentNode.appendChild(avisoHTML);
                    });
                } else {
                    if(input.tagName == "SELECT"){
                        input.previousSibling.previousSibling.classList.remove('is-invalid');
                    }
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

        function irParaPasso(passo_atual) {

            passo_html = '';

            switch (passo_atual) {
                case 1:
                    passo_html = `
                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="input_nome">Nome:</label>
                                    <input type="text" name="nome" class="form-control" id="input_nome" maxlength="255">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="input_data_nascimento">Data Nascimento:</label>
                                    <input type="text" name="data_nascimento" class="form-control"
                                        id="input_data_nascimento" maxlength="10">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-direita" id="proximo">
                            Próximo
                        </button>
                        <button disabled class="btn btn-primary btn-direita" id="aguardando"><i
                            class="fa fa-spinner fa-spin"></i></button>
                    `;
                    break;
                case 2:
                    passo_html = `
                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="input_estado">Estado:</label>
                                    <select name="estado" class="form-control" id="input_estado">
                                        <option selected disabled value="">Selecione um estado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="input_cidade">Cidade:</label>
                                    <select name="cidade" class="form-control" id="input_cidade">
                                        <option selected disabled value="">Selecione uma cidade</option>
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
                                    <input type="number" name="numero" class="form-control" id="input_numero">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="input_cep">CEP:</label>
                                    <input type="text" name="cep" class="form-control" id="input_cep" maxlength="9">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-direita" id="proximo">
                            Próximo
                        </button>
                        <button disabled class="btn btn-primary btn-direita" id="aguardando"><i
                                class="fa fa-spinner fa-spin"></i></button>
                    `;
                    break;
                case 3:
                    passo_html = `
                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="input_telefone_fixo">Telefone Fixo:</label>
                                    <input type="text" name="telefone_fixo" class="form-control" id="input_telefone_fixo"
                                        maxlength="14">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="input_telefone_celular">Telefone Celular:</label>
                                    <input type="text" name="telefone_celular" class="form-control"
                                        id="input_telefone_celular" maxlength="15">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-direita" id="proximo">
                            Finalizar
                        </button>
                        <button disabled class="btn btn-primary btn-direita" id="aguardando"><i
                                class="fa fa-spinner fa-spin"></i></button>
                    `;
                    break;
                default:
                    passo_html = `
                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="input_nome">Nome:</label>
                                    <input type="text" name="nome" class="form-control" id="input_nome" maxlength="255">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="input_data_nascimento">Data Nascimento:</label>
                                    <input type="text" name="data_nascimento" class="form-control"
                                        id="input_data_nascimento" maxlength="10">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-direita" id="proximo">
                                Próximo
                        </button>
                        <button disabled class="btn btn-primary btn-direita" id="aguardando"><i
                            class="fa fa-spinner fa-spin"></i></button>
                    `;

            }

            if(!passo_atual) {
                document.querySelector(`.passo_2`).classList.remove('active');
                document.querySelector(`.passo_3`).classList.remove('active');
            }

            for(let i = 1; i <= passo_atual; i++){
                document.querySelector(`.passo_${i}`).classList.add('active');
            }

            document.querySelector('form').innerHTML = passo_html;
            registrarEventos(passo_atual);
            if(passo_atual == 2){
                buscarEstados(input_estado);
            }
            animateCSS('#page', 'fadeInRight');
        }

</script>
@endsection
