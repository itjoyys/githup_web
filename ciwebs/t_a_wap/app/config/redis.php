<?
$config['socket_type'] = 'tcp'; //`tcp` or `unix`
$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = REDIS_HOST;
$config['password'] = NULL;
$config['port'] = REDIS_PORT;
$config['timeout'] = 0;