<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Новый пользователь</h4>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" class="form-control" id="edit-id">
                    <div class="form-group">
                        <label for="edit-name" class="control-label">Имя</label>
                        <input type="text" class="form-control" id="edit-name">
                    </div>
                    <div class="form-group">
                        <label for="edit-password" class="control-label">Пароль</label>
                        <input type="password" class="form-control" id="edit-password">
                    </div>
                    <div class="form-group">
                        <label for="edit-role" class="control-label">Роль</label>
                        <select class="form-control" id="edit-role">
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="submit" class="btn btn-success" onClick="userEdit()">Добавить</button>
            </div>
        </div>
    </div>
</div>

<script id="template-user-roles" type="text/template">
    <option value="@{{id}}">@{{role}}</option>
</script>

<script>
    function userRoles() {
        $.get(
            "/users/roles", {}
        ).done(function (roles) {
            renderRoles(roles);
        }).fail(function (error) {
            location.href = '/'
        })
    }

    function renderRoles(roles) {
        var renderRoles = '';
        var templateRole = _.template($('#template-user-roles').html());
        for (var i = 0; i < roles.length; i++) {
            renderRoles += templateRole({
                id: roles[i].id,
                role: roles[i].role
            });
        }
        $('#edit-role').html(renderRoles);
    }

    $(document).ready(function () {
        userRoles();
    });
</script>