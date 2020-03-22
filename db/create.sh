#!/bin/sh

if [ "$1" = "travis" ]; then
    psql -U postgres -c "CREATE DATABASE avalon_test;"
    psql -U postgres -c "CREATE USER avalon PASSWORD 'avalon' SUPERUSER;"
else
    sudo -u postgres dropdb --if-exists avalon
    sudo -u postgres dropdb --if-exists avalon_test
    sudo -u postgres dropuser --if-exists avalon
    sudo -u postgres psql -c "CREATE USER avalon PASSWORD 'avalon' SUPERUSER;"
    sudo -u postgres createdb -O avalon avalon
    sudo -u postgres psql -d avalon -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    sudo -u postgres createdb -O avalon avalon_test
    sudo -u postgres psql -d avalon_test -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    LINE="localhost:5432:*:avalon:avalon"
    FILE=~/.pgpass
    if [ ! -f $FILE ]; then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE; then
        echo "$LINE" >> $FILE
    fi
fi
