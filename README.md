# OkvpnRedisQueueBundle ![OkvpnRedisQueueBundle](./src/Resources/doc/img/redis.png)
  
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/40a378ee-6fb9-438b-9f23-262661b5ce2c/mini.png)](https://insight.sensiolabs.com/projects/40a378ee-6fb9-438b-9f23-262661b5ce2c)
[![Latest Stable Version](https://poser.pugx.org/okvpn/redis-queue-bundle/v/stable)](https://packagist.org/packages/okvpn/redis-queue-bundle)
[![License](https://poser.pugx.org/okvpn/redis-queue-bundle/license)](https://packagist.org/packages/okvpn/redis-queue-bundle)
[![The Builds](https://travis-ci.org/vtsykun/redis-message-queue.svg?branch=master)](https://travis-ci.org/vtsykun/redis-message-queue)

--------------------------------
The bundle integrates OroMessageQueue component. It provides more faster redis message queue transport for oro-platform
vs DBAL. See [OroMessageBundle](https://github.com/orocrm/platform/tree/master/src/Oro/Bundle/MessageQueueBundle) for details.

## Install and Test Dependencies

#### Install Redis

```bash
sudo apt-get update
sudo apt-get install redis-server
sudo apt-get install php7.0-redis
```

Check that Redis is up & running:

```
redis-benchmark -q -n 5000

Output:
PING_INLINE: 102040.82 requests per second
PING_BULK: 208333.33 requests per second
SET: 238095.23 requests per second
GET: 227272.73 requests per second
INCR: 238095.23 requests per second
LPUSH: 227272.73 requests per second
LPOP: 217391.30 requests per second
SADD: 227272.73 requests per second
SPOP: 227272.73 requests per second
LPUSH (needed to benchmark LRANGE): 238095.23 requests per second
LRANGE_100 (first 100 elements): 90909.09 requests per second
LRANGE_300 (first 300 elements): 27472.53 requests per second
LRANGE_500 (first 450 elements): 19011.41 requests per second
LRANGE_600 (first 600 elements): 14619.88 requests per second
MSET (10 keys): 166666.67 requests per second
```
#### Install using composer

```bash
composer require okvpn/redis-queue-bundle
```

### Usage
First, you have to configure a transport layer and set one to be default. For the config settings.

```yaml
# app/config/config.yml

oro_message_queue:
  transport:
    default: 'redis'
    redis:
      dsn: 'redis://pass@localhost:6379/0'
```

We can configure one of the supported transports via parameters:

```yaml
# app/config/config.yml

oro_message_queue:
    transport:
        default: '%message_queue_transport%'
        '%message_queue_transport%': '%message_queue_transport_config%'
    client: ~
```

```yaml
# app/config/parameters.yml

    message_queue_transport: 'redis'
    message_queue_transport_config: { dsn: 'redis://pass@localhost:6379/0' }
```

### Supervisord

As you read before you must keep running `oro:message-queue:consume` command and to do this best
we advise you to delegate this responsibility to [Supervisord](http://supervisord.org/).
With next program configuration supervisord keeps running four simultaneous instances of
`php app/console oro:message-queue:consume` command and cares about relaunch if instance has dead by any reason.

```ini
[program:oro_message_consumer]
command=/path/to/app/console --env=prod --no-debug oro:message-queue:consume
process_name=%(program_name)s_%(process_num)02d
numprocs=4
autostart=true
autorestart=true
startsecs=0
user=www-data
```

## License

MIT License.
