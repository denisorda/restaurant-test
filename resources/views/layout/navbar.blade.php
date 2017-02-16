<div class="navbar">
    <div class="navbar-content">
        <div class="col-xs-8 userInfo">
        </div>
        <div class="col-xs-4 text-right btn-logout">
        </div>
    </div>
</div>

<script>
    function logout(){
        $.post(
            "/logout", {}
        ).done(function (data) {
            localStorage.setItem('auth_token', '');
            localStorage.setItem('user', '');
            location.href = '/login';
        }).fail(function (error) {

        })
    }
    function logoutBtn(){
        if(localStorage.getItem('user')){
            var user = JSON.parse(localStorage.getItem('user'));
            $('.btn-logout').html('<button type="button" class="btn btn-default" onClick="logout()">Выйти</button>');
            $('.userInfo').html('<div>' + user.role_name.role + ' ' + user.name + '</div>');
        }
    }

    $(document).ready(function () {
        logoutBtn();
    });

</script>