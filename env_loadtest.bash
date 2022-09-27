#!/bin/bash

export MATTERMOST_PHP_TOPDIR=/root/workspace
export MATTERMOST_PHP_TESTDIR=${MATTERMOST_PHP_TOPDIR}/mattermost-test
export MATTERMOST_RS_TOPDIR=${MATTERMOST_PHP_TOPDIR}/load-test-resource

export WEB_SERVER_URL=192.168.11.52
export TOP_DIR=`pwd`
export TEST_TARGET=mattermost
export TEST_IMPL_DIR=${MATTERMOST_RS_TOPDIR}/test-impl/${TEST_TARGET}
export TEST_ITEM_DIR=${MATTERMOST_RS_TOPDIR}/test-item/${TEST_TARGET}
export TEST_RNTM_DIR=${TOP_DIR}/test-runtime
export TEST_LOGGER=${TOP_DIR}/test-logger/simple-logger.bash
export TEST_LOGPATH=${MATTERMOST_RS_TOPDIR}/log
