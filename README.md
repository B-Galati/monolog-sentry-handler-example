Symfony project example with package `bgalati/monolog-sentry-handler`.

This is the implementation of the Symfony guide that is being shown is this repository.

**Usage:**

```shell
docker run -d --rm -p 15672:15672 -p 5672:5672 --hostname my-rabbit --name my-rabbit -e RABBITMQ_DEFAULT_USER=user -e RABBITMQ_DEFAULT_PASS=password rabbitmq:3-management
symfony server:start

# Modify LogController as you like and then call it to trigger some Sentry events
curl localhost:8000/log

# Trigger sentry events from failing command
symfony console unknown-command
symfony console about unknown-argument

# Modify LogHandler as you like and then call it to trigger some Sentry events
symfony console messenger:dispatch:log
symfony console messenger:consume async
```
