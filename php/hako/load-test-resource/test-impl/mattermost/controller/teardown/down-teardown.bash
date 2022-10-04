#!/bin/bash

if [ -z ${TEST_TARGET} ]
then
    source env/env.bash
fi

source ${TEST_LOGGER}

tlog "TEARDOWN TEST..."
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-stop.bash ${MATTERMOST_TOOL_DIR}
