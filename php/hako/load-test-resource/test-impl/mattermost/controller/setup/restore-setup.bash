#!/bin/bash

if [ $# -ne 2 ]
then
    echo "Usage: $0 <id> <backup-file>"
    exit 1
fi
ID=${1}
BACKUP_FILE=${2}

if [ -z ${TEST_TARGET} ]
then
    source env/env.bash
fi

source ${TEST_LOGGER}

tlog "SETUP RESTORE... : backup-file=${BACKUP_FILE}"
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-stop.bash ${MATTERMOST_TOOL_DIR}
bash test-utils/remote_script.bash \
    ${MATTERMOST_DB_BKP_TOOL_DIR}/bin/db_drop.bash \
    ${POSTGRES_DB_NAME} \
    ${MATTERMOST_DB_BKP_TOOL_DIR}
bash test-utils/remote_script.bash \
    ${MATTERMOST_DB_BKP_TOOL_DIR}/bin/db_create.bash \
    ${POSTGRES_DB_NAME} \
    ${MATTERMOST_DB_BKP_TOOL_DIR}
bash test-utils/remote_script.bash \
    ${MATTERMOST_DB_BKP_TOOL_DIR}/bin/db_restore.bash \
    ${POSTGRES_DB_NAME} \
    ${MATTERMOST_DB_BKP_DIR}/${BACKUP_FILE} \
    ${MATTERMOST_DB_BKP_TOOL_DIR}
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-start.bash ${MATTERMOST_TOOL_DIR}
