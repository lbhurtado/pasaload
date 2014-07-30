#!/bin/sh

URL="http://173.255.117.249/log.php"

if [ "$1" = "RECEIVED" ]; then
    DELIMITED_RESPONSE=`curl -s -F fileToUpload=@$2 $URL`
    if [ "$DELIMITED_RESPONSE" != "-1" ]; then
        if [ "$DELIMITED_RESPONSE" != "0" ]; then
            echo $DELIMITED_RESPONSE
            da=$(echo $DELIMITED_RESPONSE | cut -f1 -d' ')
            id=$(echo $DELIMITED_RESPONSE | cut -f2 -d' ')
            amount=$(echo $DELIMITED_RESPONSE | cut -f3 -d' ')
            printf -v controlid "%05d" $id
            FILENAME=`mktemp /var/spool/sms/outgoing/PASALOADXXXXXX`
            echo "To: $da" >$FILENAME
            echo "" >> $FILENAME
            echo "You have successfully remitted $amount pesos to our server.  Your control number is $controlid." >> $FILENAME
        fi
    fi
fi