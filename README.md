# Instalação do Ambiente sem Docker
- Requisitos: 
    - PHP 8.0^
    - COMPOSER
    - Habilitar configurações no php.ini

### Windows:
- [download versão 8.0^](https://windows.php.net/download#php-8.0)
Selecione a versão "VS16 x64 Thread Safe", embora qualquer outra versão sirva. Opte pelo formato zip.
![imagem da tela de seleção do arquivo php para download](/git-public/select_php_version_for_windows.png)

- Após concluir o download basta descompactar o PHP em alguma pasta de seu sistema.

- Feito isso é preciso habilitar alguma extensões do PHP, como "fileinfo", "pdo_pgsql" e "pgsql". Você pode fazer isso indo direto na pasta onde o php foi extraído e então editar o arquivo "php.ini" ou então você pode copiar este [arquivo php.ini](/git-public/php.ini) e substituir o seu.

- Por fim, agora precisamos instalar o Composer, você acessar a página do (Composer)[https://getcomposer.org/download/] para isso ou simplemente [clicar aqui para baixar](https://getcomposer.org/Composer-Setup.exe). 
- Feito download do Composer, basta o instalar, para isso é importante que realize esses dois passos. Quanto aos demais passos é só dar "next".
    - Devemos selecionar a opção de Developer ao instalar.
    ![Composer, selecionando a opção developer](/git-public/composer_select_develop_mode.png)
    - Depois de avançar alguns passos, precisamos nos certificar que a versão do PHP que será utilizada pelo Composer é a mesma que baixamos e configuramos a pouco. Para isso verifique se o caminho na tela de instalação com título "Settings Check" é o mesmo caminho de onde foi extraído o PHP.
    ![Composer, configurando a versão do PHP vinculada](/git-public/composer_select_version_php.png)

### Linux:
- Instale os seguintes pacotes necessários:
> sudo apt-get install software-properties-common
- Em seguida, adicione o repositório do software:
> sudo add-apt-repository ppa:ondrej/php

- Depois disso, atualize o APT:
> sudo apt update

- E, finalmente, instale o PHP 8 e alguns módulos:
> sudo apt install php8.0 php8.0-intl php8.0-pgsql php8.0-pdo_pgsql php8.0-gd php8.0-fileinfo


- Em seguida, verifique a versão recém-instalada:
> php -V

- Agora só falta instalarmos o composer, para isso acesse [Download Composer](https://getcomposer.org/download/) e siga o passo a passo da instalação via command-line.

# Instalando nosso Aplicativo
- Depois de termos feitas todas as configurações de ambiente, agora precisamos clonar nosso repositório em qualquer pasta de nosso computador.
- Após clonado acesse a pasta raiz do projeto.
- Agora vamos duplicar o arquivo ".env.example" e então vamos renomear o arquivo duplicado para ".env".
    - Neste arquivo ".env" ficam todas as variáveis de ambiente, inclusive as de acesso ao banco de dados.
- Precisamos abrir um terminal, e nele vamos rodar um comando do Composer para instalar todas as dependências do projeto. Com o terminal aberto execute:
> composer update
- Após concluir o download de todas as depedências, precisamos rodar um último comando pra finalizar. Cujo qual irá gerar uma chave pra aplicação.
> php artisan key:generate 

# Configurando Variáveis de Ambiente
Acesse o seu arquivo ".env" e então vamos nos concentrar nas seguintes variáveis. Onde devemos informar os dados de acesso ao nosso banco de dados. <b>Atenção:</b> em nosso ambiente só ativamos configuração de acesso a bancos Postgresql. Caso necessite de acesso a outro tipo de banco, certifique-se de ativar as extensões no php.ini de conexão ao banco (<i>Exemplo: mysqli, pdo_mysql, sqlite3, pdo_sqlite, etc...</i>)
- DB_CONNECTION=?
    - Neste item vai o tipo de conexão: pgsql, mysql, sqlite
- DB_HOST=?
    - URL de acesso ao banco de dados
- DB_PORT=?
    - Porta de acesso ao banco de dados
- DB_DATABASE=?
    - Nome do banco de dados
- DB_USERNAME=?
    - Usuário com acesso ao banco de dados
- DB_PASSWORD
    - Senha do usuário com acesso ao banco dados

# Executando as Migrations de Banco de Dados
Depois de configurado as variáveis de ambiente, podemos agora testar nossa configuração com o banco, para isso execute a partir da raiz do projeto:
> php artisan migrate:status

Em caso positivo, veremos algo assim:
![exemplo de retorno ao executar php artisan migrate:status](/git-public/migrate_status_example.png)
Tendo obtido sucesso, agora podemos a executar pra valer:
> php artisan migrate

Com esse comando todo nosso banco será criado e já podemos iniciar a aplicação.

# Iniciando a Aplicação:
Com todos os passos acimas tendo sido seguidos e realizados com sucesso, podemos agora iniciar a aplicação utilizando o próprio servidor do Laravel. Para isso execute a partir da pasta raiz:
> php artisan serve

Se tudo ocorreu bem, sua aplicação estará rodando a partir de agora.

![aplicação executando](/git-public/app_start_with_laravel_server.png)

Para visualizar a aplicação em execução acesse o endereço informado no console.
