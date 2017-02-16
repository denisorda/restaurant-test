@section('content')
    <div class="dashboard row">
    </div>

    <script type="text/template" id="template-dashboard-item">
        <div class="col-xs-12 col-sm-6 col-lg-3 dashboard-item-container text-center">
            <div class="dashboard-item">
                <a href="/@{{ link }}">@{{title}}</a>
            </div>
        </div>
    </script>

    <script>
        function getUser() {
            $.post(
                "/login/user", {}
            ).done(function (user) {
                localStorage.setItem('user', JSON.stringify(user));
                render();
                //render navbar function in navbar.blade
                logoutBtn();
            }).fail(function (error) {
                location.href = '/login'
            })
        }

        function render() {
            var dashboard = '';
            var renderDashbordItem = _.template($('#template-dashboard-item').html());
            var user = JSON.parse(localStorage.getItem('user'));
            switch (user.role) {
                case 2:
                    dashboard = renderDashbordItem({
                            link: 'new-order',
                            title: 'Новый заказ'
                        }) + renderDashbordItem({
                            link: 'orders',
                            title: 'Заказы'
                        });
                    break;
                case 3:
                    dashboard = renderDashbordItem({
                            link: 'kitchen',
                            title: 'Кухня'
                        });
                    break;
                default:
                    dashboard = renderDashbordItem({
                            link: 'users',
                            title: 'Пользователи'
                        }) + renderDashbordItem({
                            link: 'new-order',
                            title: 'Новый заказ'
                        }) + renderDashbordItem({
                            link: 'orders',
                            title: 'Заказы'
                        }) + renderDashbordItem({
                            link: 'kitchen',
                            title: 'Кухня'
                        });
                    break;
            }
            $('.dashboard').html(dashboard);
        }

        $(document).ready(function () {
            getUser();
        });


//        var socket = new WebSocket("ws://10.10.10.25");
//
//        socket.onopen = function() {
//            alert("The connection is established.");
//        };
//
//        socket.onclose = function(event) {
//            if (event.wasClean) {
//                alert('Connection closed cleanly');
//            } else {
//                alert('Broken connections');
//            }
//            alert('Key: ' + event.code + ' cause: ' + event.reason);
//        };
//
//        socket.onmessage = function(event) {
//            alert("The data " + event.data);
//        };
//
//        socket.onerror = function(error) {
//            alert("Error " + error.message);
//        };


        //To send data using the method socket.send(data).

        //For example, the line:
       // socket.send("Hello");

    </script>
@endsection
@extends('layout.index')