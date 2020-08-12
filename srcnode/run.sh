
#!/bin/bash
APP_RUN_DIR=/home/vega/educa.vn/nodejs/service/run
case "$1" in
start)
   node serve.js 1>nodeservice.log 2>nodeservice.error &
   echo $!>$APP_RUN_DIR/service.nodejs.pid
        while :
        do     
        sleep 5   
        service=`cat $APP_RUN_DIR/service.nodejs.pid`
        if (( $(ps -ef | grep -v grep | grep $service | wc -l) > 0 ))
        then
            echo "$service is running!!!"
        else
            node serve.js 1>nodeservice.log 2>nodeservice.error &
            echo $!>$APP_RUN_DIR/service.nodejs.pid       
        fi
   
    done
    ;;
stop)
   kill `cat $APP_RUN_DIR/service.nodejs.pid`
   rm $APP_RUN_DIR/service.nodejs.pid
   ;;
restart)
   $0 stop
   $0 start
   ;;
status)
   if [ -e $APP_RUN_DIR/service.nodejs.pid ]; then
      echo service nodejs is running, pid=`cat $APP_RUN_DIR/service.nodejs.pid`
   else
      echo service nodejs is NOT running
      exit 1
   fi
   ;;
*)
   echo "Usage: $0 {start|stop|status|restart}"
esac

exit 0 

