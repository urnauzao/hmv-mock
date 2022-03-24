#!/usr/bin/env bash
echo "CERTIFIQUE-SE DE:"
echo "- ter configurado o .env com os dados de conexão do banco de dados";
if [ ! -f docker-compose.yml ]; then
    cd ..
fi
if [ -f .env ]; then
    echo "Já existe um .env"
else
    pwd
    ls -a .env.example
    cp -a .env.example .env
    echo "Arquivo .env de exemplo criado. Configure agora os dados de conexao ao banco"
fi

docker-compose up -d
echo "A aplicação está sendo executa em detach, uma espécie de Daemon, para pausar execute o script de stop"
echo "----"
echo "----"
echo "----"
echo "Para acessar a aplicação: http://localhost"

