#!/bin/bash

if [ -z ${TEST_TARGET} ]
then
    source env/env.bash
fi

source ${TEST_LOGGER}
if [ $# -ne 4 ]
then
    terror "Usage: $0 <id> <repeat_id> <MAX-USERS> <MSG-SIZE>"
    exit 1
fi
ID=${1}
RID=${2}
MAX_USERS=${3}
MSG_SIZE=${4}

tlog "ID=${ID}, RID=${RID} MAX_USERS=${MAX_USERS} MSG_SIZE=${MSG_SIZE}:DOING POST TEST..."

TMP_ID=`expr ${RANDOM} % ${MAX_USERS} \+ 1`
MESSAGE=`cat /dev/urandom  | base64 | fold -w  ${MSG_SIZE} | head -n 1`
#TMP_ID=`test-utils/random-id.bash ${MAX_USERS}`
#MESSAGE=`test-utils/random.bash ${MSG_SIZE}`
#USER_ID="user-${TMP_ID}"
USER_ID="user-${ID}"
TEAM="public-room"
CHANNEL="channel-01"

cd ${MATTERMOST_PHP_TESTDIR}
tlog "OP: php artisan mattermost:create_post ${USER_ID} Password-999 ${TEAM} ${CHANNEL} \"${MESSAGE}\""

while [ 1 ]
do
    php artisan mattermost:create_post ${USER_ID} Password-999 ${TEAM} ${CHANNEL} "${MESSAGE}" | tee tmp_${ID}.txt
    tlog "ID=${ID}, RID=${RID}:`cat tmp_${ID}.txt`"
    grep "END: CREATE POST"  tmp_${ID}.txt > /dev/null
    if [ $? -eq 0 ]
    then
        break
    else
        tlog "ERROR... so RETRY: ${USER_ID}"
        sleep 1
    fi
done

rm -f tmp_${ID}.txt