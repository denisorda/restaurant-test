@extends('layout.index')
@section('content')
    <div class="errors"></div>
    <div class="login">
        <form id="login-form" name="login-form">
            <fieldset>
                <div class="form-group">
                    <label for="name">Имя</label>
                    <select class="form-control" id="select">
                        <option>Выберите пользователя</option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}} ({{$user->roleName->role}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Пароль</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <div class="text-center">
                    <button type="button" class="btn btn-default btn-lg" onClick="login()">Войти</button>
                </div>
            </fieldset>
        </form>
    </div>

    <script>
        function login() {
            $.post(
                "/login/check", {
                    id: $('select').val(),
                    password: $('input[name=password]').val()
                }
            ).done(function (data) {
                localStorage.setItem('auth_token', data.auth_token);

                location.href = '/';
            }).fail(function (error) {
                $('.errors').html(error.responseJSON.error);
            })
        }


    </script>
@endsection
