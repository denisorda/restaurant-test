@extends('layout.index')
@section('content')
    <div class="back">
        <a href="/"><i class="fa fa-caret-left" aria-hidden="true"></i> Назад</a>
    </div>
    <h2>Управление пользователями</h2>
    <div class="text-center">
        <button type="button" class="btn btn-success"
                data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-plus-circle" aria-hidden="true"></i> <span>Добавить пользователя</span>
        </button>
    </div>

    <div class="users">
    </div>
    @include('admin.modal')
    <script id="template" type="text/template">
        <div class="row user-item">
            <div class="col-xs-4 col-sm-2">
                #@{{id}}
            </div>
            <div class="col-xs-8 col-sm-4">
                @{{name}}
            </div>
            <div class="col-xs-6 col-sm-3">
                @{{role}}
            </div>
            <div class="col-xs-6 col-sm-3 text-right">
                <button class="btn btn-success"
                        data-toggle="modal" data-target="#exampleModal"
                        style="margin-right: 10px;" onClick="modalEditShow(@{{number}})">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>
                <button class="btn btn-danger" onClick="userDelete(@{{id}}, @{{number}})">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </script>

    <script>
        var users = {};

        function usersGet() {
            $.get(
                "/users/get", {}
            ).done(function (usersNew) {
                users = usersNew;
                render(users);
            }).fail(function (error) {
                location.href = '/'
            })
        }

        function render(users) {
            var s = '';
            var i = 0;
            var template = _.template($('#template').html());
            if (users.length == 0){
                s = '<div class="text-center">Активных пользователей нет</div>'
            }
            for (key in users) {
                var row = template({
                    id: users[key].id,
                    name: users[key].name,
                    role: users[key].role_name.role,
                    number: i
                });
                s+=row;
                i++;
            }
            $('.users').html(s);
        }

        function modalEditShow(number){
            var user = users[number];
            $('#exampleModal').on('show.bs.modal', function () {
                var modal = $(this);
                modal.find('.modal-title').text('Редактирование пользователя #' + user.id);
                modal.find('button[type=submit]').text('Редактировать');
                modal.find('#edit-id').val(user.id);
                modal.find('#edit-name').val(user.name);
                modal.find('#edit-password').val(user.password);
                modal.find('#edit-role').val(user.role);
            })
        }

        function userEdit() {
            $.post(
                "/users/edit", {
                    id: $('#edit-id').val(),
                    name: $('#edit-name').val(),
                    password: $('#edit-password').val(),
                    role: $('#edit-role').val(),
                }
            ).done(function () {
                $('#exampleModal').modal('hide');
                usersGet();
                render(users);
            }).fail(function (error) {

            })
        }

        function userDelete(id, number) {
            bootbox.confirm({
                message: "Удалить пользователя?",
                buttons: {
                    confirm: {
                        label: 'Да',
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: 'Нет',
                        className: 'btn-success'
                    }
                },
                callback: function (result) {
                    result && $.post(
                        "/users/delete", {
                            id: id
                        }
                    ).done(function () {
                        users.splice(number, 1);
                        render(users);
                    }).fail(function (error) {

                    })
                }
            });
        }

        $(document).ready(function () {
            usersGet();
        });

    </script>
@endsection
