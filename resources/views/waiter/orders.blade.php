@section('content')
    <div class="back">
        <a href="/"><i class="fa fa-caret-left" aria-hidden="true"></i> Назад</a>
    </div>
    <h2>Мои Заказы</h2>
    <div class="orders-waiter">
    </div>

    <script id="template-order-waiter" type="text/template">
        <div class="order-waiter">
            <div class="row title-order-waiter">
                <div class="col-xs-2 text-center">
                    Статус
                </div>
                <div class="col-xs-4">
                    Стол: @{{table}}
                </div>
                <div class="col-xs-6 text-right">
                    Заказ #@{{order_id}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 orders-items-waiter">
                    @{{items}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-4 pull-right text-right">
                    @{{btnPay}}
                </div>
            </div>

        </div>
    </script>

    <script id="template-order-waiter-item" type="text/template">
        <div class="order-waiter-item">
            <div class="row">
                <div class="col-xs-2 text-center">
                    <i class="fa fa-2x fa-@{{status}} text-@{{statusColor}}" aria-hidden="true"></i>
                </div>
                <div class="col-xs-4">
                    @{{name}}
                </div>

                <div class="col-xs-3">
                    @{{btnStatus}}
                </div>
                <div class="col-xs-3 text-right">
                    @{{timer}}
                </div>
            </div>
        </div>
    </script>

    <script>
        var orders = {};
        var intervals = {
            list: [],
            calcTimer: function (el,interval) {
                var ms = moment.utc(moment($(el).data('to')).format('x') - moment().format('x'));
                if (ms <= 0) {
                    $(el).html('<div class="text-danger">Время вышло!</div>')
                    interval && clearInterval(interval)
                } else {
                    $(el).html(ms.format('HH:mm:ss'));
                }
            },
            run: function () {
                var self = this;
                this.clear();
                $('.timers').each(function (index, el) {
                        self.calcTimer(el)
                        var interval = setInterval(function () {
                            self.calcTimer(el,interval)
                        }, 1000)
                        self.list.push(interval)
                    }
                )
            },
            clear: function () {
                this.list.map(function (t) {
                    clearInterval(t)
                });
                this.list = [];
            }
        };
        function ordersWaiterGet() {
            $.post(
                "/orders/waiter", {
                    id: JSON.parse(localStorage.getItem('user')).id,
                    role: JSON.parse(localStorage.getItem('user')).role
                }
            ).done(function (ordersNew) {
                orders = ordersNew;
                render(orders);
            }).fail(function (error) {
                location.href = '/'
            })
        }

        function render(orders) {
            var s = '';
            var templateOrder = _.template($('#template-order-waiter').html());
            var templateOrderItem = _.template($('#template-order-waiter-item').html());
            if (orders.length == 0) {
                s = '<div class="text-center order-kitchen">Заказов нет</div>'
            }
            for (key in orders) {
                var order = orders[key].order_item;
                var sItem = '';
                for (key2 in order) {
                    var sign = '', signColor = '', btn = '', timer = '';
                    switch (order[key2].status) {
                        case 'cooking':
                            sign = 'hourglass-half';
                            signColor = 'warning';
                            btn = '';
                            timer = '<div class="timers" id="timer-' + key + '-' + key2 + '" data-to="' + order[key2].time + '"></div>'
                            break;
                        case 'done':
                            sign = 'check';
                            signColor = 'success';
                            btn = '<button class="btn btn-primary" onClick="orderItemClose(' + order[key2].id + ')"><span>Отдано</span></button>';
                            timer = 'Готово';
                            break;
                        case 'close':
                            sign = 'check-circle-o';
                            signColor = 'primary';
                            btn = '';
                            timer = 'Блюдо подано';
                            break;
                        default:
                            sign = 'minus';
                            signColor = 'danger';
                            btn = '';
                            timer = 'Ожидает';
                            break;
                    }
                    var rowItem = templateOrderItem({
                        id: order[key2].id,
                        status: sign,
                        statusColor: signColor,
                        name: order[key2].menu.name,
                        btnStatus: btn,
                        timer: timer
                    });
                    sItem += rowItem;
                }
                var row = templateOrder({
                    order_id: orders[key].id,
                    table: orders[key].table,
                    items: sItem,
                    btnPay: orders[key].status == 'close' ? '<button class="btn btn-success" onClick="orderPay(' + orders[key].id + ')"><span>Заказ оплачен</span></button>' : ''
                });
                s += row;
            }
            $('.orders-waiter').html(s);
            intervals.run()
        }

        function orderItemClose(id) {
            $.post(
                "/orders/waiter/close", {
                    id: id
                }
            ).done(function () {
                ordersWaiterGet();
                render(orders);
            }).fail(function (error) {

            })
        }

        function orderPay(id) {
            $.post(
                "/orders/waiter/pay", {
                    id: id
                }
            ).done(function () {
                ordersWaiterGet();
                render(orders);
            }).fail(function (error) {

            })
        }

        $(document).ready(function () {
            ordersWaiterGet();
        });
    </script>
@endsection
@extends('layout.index')