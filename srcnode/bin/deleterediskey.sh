#!/bin/bash

if [ $# -ne 3 ] 
then
  echo "Delete keys from Redis matching a pattern using SCAN & DEL"
  echo "Usage: $0 <host> <port> <pattern>"
  exit 1
fi

cursor=-1
keys=""

while [ $cursor -ne 0 ]; do
  if [ $cursor -eq -1 ]
  then
    cursor=0
  fi

  reply=`redis-cli -h $1 -p $2 SCAN $cursor MATCH $3`
  cursor=`expr "$reply" : '\([0-9]*[0-9 ]\)'`
  keys=${reply##[0-9]*[0-9 ]}
  redis-cli -h $1 -p $2 DEL $keys
done