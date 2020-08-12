APP_RUN_DIR=/home/vega/educa.vn/nodejs/service/run
LOG_FILE=$APP_RUN_DIR/logcript.txt
case "$1" in
start)
    {
    while :		
            do
            sleep 30
            service=`cat $APP_RUN_DIR/service.nodejs.pid`
            if (( $(ps -ef | grep -v grep | grep $service | wc -l) > 0 ))
            then
                echo "$(date) : $service is running!!!" >>$LOG_FILE
            else
                node serve.js 1>nodeservice.log 2>nodeservice.error &
                echo $!>$APP_RUN_DIR/service.nodejs.pid
            fi
            done
    }&      
	echo $!>$APP_RUN_DIR/start.bash.pid
 
    ;;
stop)
   kill `cat $APP_RUN_DIR/start.bash.pid`
   rm $APP_RUN_DIR/start.bash.pid
   kill `cat $APP_RUN_DIR/service.nodejs.pid`
   rm $APP_RUN_DIR/service.nodejs.pid
   ;;
restart)
   $0 stop
   $0 start
   ;;
status)
   if [ -e $APP_RUN_DIR/start.bash.pid ]; then
      echo start bash shell is running, pid=`cat $APP_RUN_DIR/start.bash.pid`
   else
      echo bash shell is NOT running
      exit 1
   fi
   ;;
*)
   echo "Usage: $0 {start|stop|status|restart}"
esac
exit 0
