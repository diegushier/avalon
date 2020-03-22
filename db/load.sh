#!/bin/sh

BASE_DIR=$(dirname "$(readlink -f "$0")")
if [ "$1" != "test" ]; then
    psql -h localhost -U avalon -d avalon < $BASE_DIR/avalon.sql
fi
psql -h localhost -U avalon -d avalon_test < $BASE_DIR/avalon.sql
