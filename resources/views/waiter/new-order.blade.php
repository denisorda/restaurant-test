@extends('layout.index')
@section('content')
    <div class="back">
        <a href="/"><i class="fa fa-caret-left" aria-hidden="true"></i> Назад</a>
    </div>
    <h2>Новый заказ</h2>
    <div class="errors"></div>
    <div>
        <div class="form-group">
            <label for="name">Номер стола</label>
            <input type="text" class="form-control" name="table-number">
        </div>
        <div>
            <table class="order-list"></table>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-default btn-lg" onClick="orderSend()">Отправить заказ</button>
        </div>
    </div>
    <div class="menu">
    </div>

    <script id="template-menu-item" type="text/template">
        <div class="menu-item col-xs-6 col-sm-4 col-md-3 col-lg-2 text-center">
            <a type="button" name="@{{name}}" id="@{{id}}" onClick="addMenuItem(this)">@{{name}}</a>
        </div>
    </script>

    <script id="template-order-item" type="text/template">
        <tr>
            <td class="text-center" style="width: 20px;">@{{number}}</td>
            <td>@{{name}}</td>
            <td class="text-right">
                <button class="btn btn-danger" onClick="deleteMenuItem(@{{i}})">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
    </script>

    <script>
        var orderList = [];
        var orderData = [];

        function addMenuItem(link) {
            var name = $(link).attr('name');
            var id = $(link).attr('id');
            orderList.push(name);
            orderData.push(id);
            renderOrder();
        }

        function renderOrder() {
            var renderOrderList = '';
            var templateOrderItem = _.template($('#template-order-item').html());
            for (var i = 0; i < orderList.length; i++) {
                renderOrderList += templateOrderItem({
                    number: i+1,
                    name: orderList[i],
                    i: i
                });
            }
            $('.order-list').html(renderOrderList);
        }

        function renderMenu(menu) {
            var renderMenu = '';
            var renderMenuItem = _.template($('#template-menu-item').html());
            for (var i = 0; i < menu.length; i++) {
                renderMenu += renderMenuItem({
                    id: menu[i].id,
                    name: menu[i].name
                });
            }
            $('.menu').html(renderMenu);
        }

        function deleteMenuItem(item) {
            orderList.splice(item, 1);
            orderData.splice(item, 1);
            renderOrder();
        }

        function orderSend() {
            $.post(
                "/new-order/send", {
                    number: $('input[name=table-number]').val(),
                    order: orderData
                }
            ).done(function () {
                    $('input[name=table-number]').val('');
                    orderList = [];
                    orderData = [];
                    renderOrder();
                }
            ).fail(function (error) {
                $('.errors').html(error.responseJSON.error);
                }
            )
        }

        function menu() {
            $.get(
                "/orders/waiter/menu", {}
            ).done(function (menu) {
                renderMenu(menu)
            }).fail(function (error) {
                location.href = '/'
            })
        }

        $(document).ready(function () {
            menu();
        });
    </script>
@endsection