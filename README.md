# OkvpnRedisQueueBundle


[![The Build Status](https://travis-ci.org/vtsykun/redis-message-queue.svg?branch=master)](https://travis-ci.org/vtsykun/redis-message-queue)  [![SensioLabsInsight](https://insight.sensiolabs.com/projects/40a378ee-6fb9-438b-9f23-262661b5ce2c/mini.png)](https://insight.sensiolabs.com/projects/40a378ee-6fb9-438b-9f23-262661b5ce2c)

The bundle integrates OroMessageQueue component. It provides more faster redis message queue transport for oro-platform
vs DBAL. See [OroMessageBundle](https://github.com/orocrm/platform/tree/master/src/Oro/Bundle/MessageQueueBundle) for details.

### Usage

First, you have to configure a transport layer and set one to be default. For the config settings.

```yaml
# app/config/config.yml

oro_message_queue:
  transport:
    default: 'redis'
    redis:
        host: 'localhost' 
        port: '6379'
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
    message_queue_transport_config: { host: 'localhost', port: '6379' }
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
numprocs=2
autostart=true
autorestart=true
startsecs=0
user=www-data
```
### License

GPLv3
