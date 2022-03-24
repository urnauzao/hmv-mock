#!/usr/bin/env bash
if [ ! -f docker-compose.yml ]; then
    cd ..
fi
echo "você só deve utilizar esse comando quando for subir uma nova imagem ao Docker hub"
echo "."
sleep 2
echo "..."
sleep 2
echo "......"
sleep 2
echo "........"
sleep 2
echo "..........."
docker-compose build