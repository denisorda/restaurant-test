@extends('layout.index')
@section('content')
    <div class="back">
        <a href="/"><i class="fa fa-caret-left" aria-hidden="true"></i> Назад</a>
    </div>
    <h2>Заказы Кухня</h2>
    <div class="orders-kitchen">
    </div>

    <script id="template-order-kitchen" type="text/template">
        <div class="order-kitchen">
            <div class="row title-order-kitchen">
                <div class="col-xs-2 text-center">
                    Статус
                </div>
                <div class="col-xs-5">
                    Стол: @{{table}}
                </div>
                <div class="col-xs-5 text-right">
                    Официант: @{{waiter}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 orders-items-kitchen">
                    @{{items}}
                </div>
            </div>
        </div>
    </script>

    <script id="template-order-kitchen-item" type="text/template">
        <div class="order-kitchen-item">
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
        function ordersKitchenGet() {
            $.get(
                "/orders/kitchen", {}
            ).done(function (ordersNew) {
                orders = ordersNew;
                render(orders);
            }).fail(function (error) {
                location.href = '/'
            })
        }

        function render(orders) {
            var s = '';
            var templateOrder = _.template($('#template-order-kitchen').html());
            var templateOrderItem = _.template($('#template-order-kitchen-item').html());
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
                            btn = '<button class="btn btn-warning" onClick="orderItemReady(' + order[key2].id + ')"><span>Готово</span></button>';
                            timer = '<div class="timers" id="timer-' + key + '-' + key2 + '" data-to="' + order[key2].time + '"></div>'
                            break;
                        case 'done':
                            sign = 'check';
                            signColor = 'success';
                            btn = '';
                            timer = 'Готово';
                            break;
                        default:
                            sign = 'minus';
                            signColor = 'danger';
                            btn = '<button class="btn btn-danger" onClick="setTime(' + order[key2].id + ')"><span>Приняли</span></button>';
                            timer = '';
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
                    table: orders[key].table,
                    waiter: orders[key].user.name,
                    items: sItem
                });
                s += row;
            }
            $('.orders-kitchen').html(s);
            intervals.run()
        }

        function setTime(id) {
            bootbox.prompt({
                title: "Время приготовления в минутах",
                inputType: 'number',
                callback: function (result) {
                    $.post(
                        "/orders/kitchen/prepare", {
                            id: id,
                            time: result
                        }
                    ).done(function () {
                        ordersKitchenGet();
                        render(orders);
                    }).fail(function (error) {

                    })
                }
            });
        }

        function orderItemReady(id) {
            $.post(
                "/orders/kitchen/ready", {
                    id: id
                }
            ).done(function () {
                ordersKitchenGet();
                render(orders);
            }).fail(function (error) {

            })
        }

        $(document).ready(function () {
            ordersKitchenGet();
        });
    </script>
@endsection