#!/bin/bash

if [ $# -ne 2 ]
then
    echo "Usage: $0 <id> <user-num>"
    exit 1
fi
ID=${1}
USER_NUM=${2}

if [ -z ${TEST_TARGET} ]
then
    source env/env.bash
fi

source ${TEST_LOGGER}

tlog "SETUP TEST... : USER_NUM=${USER_NUM}"
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-stop.bash ${MATTERMOST_TOOL_DIR}
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-reset.bash ${MATTERMOST_TOOL_DIR}
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-start.bash ${MATTERMOST_TOOL_DIR}
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/test-data/create.bash ${USER_NUM} ${MATTERMOST_TOOL_DIR}
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/batch/setup.bash ${MATTERMOST_TOOL_DIR}

