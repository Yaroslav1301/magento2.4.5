# Roadmap_MessageQueue module

## Explanation
This extension was developed to show how to use Message Queue in M2. Here is listed 2 example of its usage.

1. With Database Usage
   2. E.g you are trying to migrate lot of customers,  data for which you should take from some Api or external service. In this case you can use migrate.customers consumer to work with db
2. With Rabbit Mq Usage
   3. Here just simple example how to be connected to the AMQP server and how to send data in there. <br/>
      4. Try to remove product from admin and run crons or manually run queue bin/magento queue:consumers:start roadmapProductDelete
      5. Then check the results in var/log/message_queue_publisher.log

## Additional information

sudo docker-compose -f rabbitmq.yaml up -d to run servive Rabbit MQ <br/>
127.0.0.1:15672/ - acces to the admin <br/>
user: admin <br/>
pass: admin <br/>


