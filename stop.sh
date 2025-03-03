#!/bin/bash
APP_CONTAINER="slim_app"
DB_CONTAINER="mysql_db"

if [ "$(docker ps -q -f name=$APP_CONTAINER)" ] || [ "$(docker ps -q -f name=$DB_CONTAINER)" ]; then
    echo "Stopping containers..."
    docker-compose down
    echo "Containers stopped correctly."
else
    echo "No containers running."
fi