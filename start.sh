#!/bin/bash

APP_CONTAINER="slim_app"
DB_CONTAINER="mysql_db"

if [ "$(docker ps -q -f name=$APP_CONTAINER)" ] && [ "$(docker ps -q -f name=$DB_CONTAINER)" ]; then
    echo "Containers already running."
else
    echo "Starting containers..."
    
    docker-compose down -v

    docker-compose up --build

    echo "Waiting for MySQL to be ready..."
    sleep 5

    echo "Executing migrations and seeders..."
    docker exec -it $APP_CONTAINER php vendor/bin/phinx migrate
    docker exec -it $APP_CONTAINER php vendor/bin/phinx seed:run

    echo "Project started correctly. http://localhost:8000"
fi
