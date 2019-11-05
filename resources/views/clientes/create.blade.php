@extends('layout')
@section('head')
    <script src="js/vanilla-masker.min.js"></script>
    <script src="js/axios.min.js"></script>
@endsection
@section('conteudo')
    <div id="wrapper">
        <div id="page" class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Formulário de Cadastro</h1>
                    <form action="" method="post">
                        @csrf
                        @if ($passo_atual == 1)
                        <div class="form-group">
                            <label for="input_nome">Nome:</label>
                            <input type="text" name="nome" class="form-control" id="input_nome" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="input_data_nascimento">Data Nascimento:</label>
                            <input type="text" name="data_nascimento" class="form-control" id="input_data_nascimento" maxlength="10">
                        </div>
                        @endif
                        @if ($passo_atual != 1)
                            <button class="btn btn-primary" id="anterior">Anterior</button>
                        @endif
                        <button class="btn btn-primary" id="proximo">Próximo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        VMasker(document.getElementById('input_data_nascimento')).maskPattern('99/99/9999');

        document.getElementById('proximo').addEventListener('click', (evt) => {
            evt.preventDefault();
            inputs_validos = validarInputs(document.querySelectorAll('input'));
            const json_input = {};
            inputs_validos.forEach((input) => {
                json_input[input.name] = input.value;
            });
            console.log(json_input);
            enviarDados(json_input);
        });

        function validarInputs(inputs){
            return [...inputs].filter((input) => input.name && input.name !== '_method' && input.name !== '_token' && !input.disabled && input.type !== 'reset' && input.type !== 'submit' && input.type !== 'button')
        }

        function enviarDados(json_input){
            axios({
                method: 'post',
                url: 'http://127.0.0.1:8000/clientes/store',
                responseType: 'json',
                data: json_input
            })
            .then((response) => {
                console.log(response);
            }).catch((err) => {
                console.error(err.response.data);
                const erros = err.response.data.errors;
                mostrarErros(erros);
            });
        }

        function mostrarErros(erros){
            const nomes_erros = Object.keys(erros);
            const inputs = validarInputs(document.querySelectorAll('input'));
            inputs.forEach((input) => {
                const contem_erro = nomes_erros.find((erro) => erro === input.name);
                if(contem_erro) {
                    input.classList.add('is-invalid');
                }
            });
        }
    </script>
@endsection
