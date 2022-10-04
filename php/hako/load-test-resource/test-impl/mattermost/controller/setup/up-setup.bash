#!/bin/bash

if [ -z ${TEST_TARGET} ]
then
    source env/env.bash
fi

source ${TEST_LOGGER}

tlog "SETUP TEST..."
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-stop.bash ${MATTERMOST_TOOL_DIR}
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-reset.bash ${MATTERMOST_TOOL_DIR}
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/bin/mm-start.bash ${MATTERMOST_TOOL_DIR}
bash test-utils/remote_script.bash ${MATTERMOST_TOOL_DIR}/batch/setup.bash ${MATTERMOST_TOOL_DIR}

