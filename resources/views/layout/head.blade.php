<title>Restaurant-test</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

<link rel="stylesheet" href="/css/app.css">
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">

<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
<script src="http://underscorejs.org/underscore-min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
<script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
<script type="text/javascript" src="/js/script.js"></script>
<script>
    _.templateSettings = {
        interpolate: /\{\{(.+?)\}\}/g
    };

    $.ajaxSetup({
        headers: {'Authorization': localStorage.getItem('auth_token')}
    });

    var socket = io.connect('http://restaurant-test.local:8890');
    socket.on('message', function (data) {
        data = jQuery.parseJSON(data);
        if (data.role === 3 && data.refresh && typeof ordersKitchenGet === 'function') {
            ordersKitchenGet();
        }
        if (data.role === 2 && data.refresh && typeof ordersWaiterGet === 'function') {
            ordersWaiterGet();
        }
    });
</script>