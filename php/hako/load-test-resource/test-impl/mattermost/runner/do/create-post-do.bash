#!/bin/bash

if [ -z ${TEST_TARGET} ]
then
    source env/env.bash
fi

source ${TEST_LOGGER}
if [ $# -ne 3 ]
then
    terror "Usage: $0 <id> <repeat_id> <MSG-SIZE>"
    exit 1
fi
ID=${1}
RID=${2}
MSG_SIZE=${3}

tlog "ID=${ID}, RID=${RID} MSG_SIZE=${MSG_SIZE}:DOING POST TEST..."

MESSAGE=`cat /dev/urandom  | base64 | fold -w  ${MSG_SIZE} | head -n 1`
USER_ID="user-${ID}"
#TEAM="public-room"
#CHANNEL="channel-01"
CHANNEL_ID="6kzibtx36ibqzfrqmgkwjbum3h"

cd ${MATTERMOST_PHP_TESTDIR}
tlog "OP: php artisan mattermost:create_post_by_id ${USER_ID} Password-999 ${CHANNEL_ID} \"${MESSAGE}\""

COUNT=0
while [ 1 ]
do
    #sleep 2
    php artisan mattermost:create_post_by_id ${USER_ID} Password-999 ${CHANNEL_ID} "${MESSAGE}" | tee tmp_${ID}.txt
    tlog "ID=${ID}, RID=${RID}:`cat tmp_${ID}.txt`"
    grep "END: CREATE POST"  tmp_${ID}.txt > /dev/null
    if [ $? -eq 0 ]
    then
        break
    else
        COUNT=`expr ${COUNT} \+ 1`
        tlog "ERROR... so RETRY: ${USER_ID} : COUNT=${COUNT}"
        if [ $COUNT -ge 3 ]
        then
            tlog "ERROR: can not recover error..."
            break
        fi
        sleep 1
    fi
done

rm -f tmp_${ID}.txt