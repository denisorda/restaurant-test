<?php
use App\Http\Sockets\SocketClass;
/*
 *  Routes for WebSocket
 *
 * Add route (Symfony Routing Component)
 * $socket->route('/myclass', new MyClass, ['*']);
 */
$socket->route('/', new SocketClass(), ['*']);
