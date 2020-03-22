#!/bin/sh

[ "$1" = "test" ] && BD="_test"
psql -h localhost -U avalon -d avalon$BD
