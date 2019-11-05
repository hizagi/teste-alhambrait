@extends('layout')
@section('head')
    <script src="js/vanilla-masker.min.js"></script>
@endsection
@section('conteudo')
    <div id="wrapper">
        <div id="page" class="container">
            <h1>Formulário de Cadastro</h1>


        @if ($passo_atual == 1)
            <input class="teste-input" type="text" maxlength="9">
        @endif
        </div>
    </div>

    <script>
        VMasker(document.querySelector("input")).maskPattern("99999-999");
    </script>
@endsection
