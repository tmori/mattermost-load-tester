#!/bin/bash

if [ -z ${TEST_TARGET} ]
then
    source env/env.bash
fi

source ${TEST_LOGGER}

tlog "SETUP TEST..."
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-start.bash ${MATTERMOST_TOOL_DIR}
