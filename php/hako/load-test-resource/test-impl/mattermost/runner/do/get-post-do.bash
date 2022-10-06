#!/bin/bash

if [ -z ${TEST_TARGET} ]
then
    source env/env.bash
fi

source ${TEST_LOGGER}
if [ $# -ne 2 ]
then
    terror "Usage: $0 <id> <repeat_id>"
    exit 1
fi
ID=${1}
RID=${2}

tlog "ID=${ID}, RID=${RID} :DOING GET POST TEST..."

USER_ID="user-${ID}"
#TEAM="public-room"
#CHANNEL="channel-01"
CHANNEL_ID="6kzibtx36ibqzfrqmgkwjbum3h"

cd ${MATTERMOST_PHP_TESTDIR}
tlog "OP: php artisan mattermost:get_post_by_id ${USER_ID} Password-999 ${CHANNEL_ID}"

while [ 1 ]
do
    php artisan mattermost:get_post_by_id ${USER_ID} Password-999 ${CHANNEL_ID} | tee tmp_${ID}.txt
    tlog "ID=${ID}, RID=${RID}:`cat tmp_${ID}.txt`"
    grep "END: GET POST by id"  tmp_${ID}.txt > /dev/null
    if [ $? -eq 0 ]
    then
        break
    else
        tlog "ERROR... so RETRY: ${USER_ID}"
        sleep 1
    fi
done

rm -f tmp_${ID}.txt
