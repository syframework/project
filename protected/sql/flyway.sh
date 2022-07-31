#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

database_ini="$DIR/../conf/database.ini"

if [ ! -f "$database_ini" ]; then
	echo 'No database.ini file found!'
	exit
fi

# Import database.ini variables
source <(grep = $database_ini | sed 's/ *= */=/g')

# Retrieve network name from the host
network="network-$(echo $host | cut -d'-' -f 2)"

docker run --network=$network --rm -v $DIR:/flyway/sql flyway/flyway -url=jdbc:mysql://$host:$port/$dbname -user=$username -password=$password -schemas="$dbname" $@