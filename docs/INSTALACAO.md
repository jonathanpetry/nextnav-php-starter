# Instalação de referência

O NextNav não depende de Composer, Node ou banco para executar os exemplos. Node é utilizado somente para conferir a sintaxe do JavaScript durante o desenvolvimento.

Este guia descreve uma instalação, não um servidor já provisionado ou homologado. A validação local do starter foi feita com PHP 8.3.12. Para um servidor novo, use uma versão mantida e atualizada; esta referência usa Debian 13, Nginx e PHP 8.4-FPM. Os pacotes estão disponíveis no [Debian](https://packages.debian.org/trixie/php8.4-fpm); confira o [suporte das versões PHP](https://www.php.net/supported-versions.php) ao instalar.

## Desenvolvimento local

Na raiz do projeto:

```bash
php -S 127.0.0.1:8080 -t public
```

Acesse `http://127.0.0.1:8080`. Esse servidor é apenas para desenvolvimento. Não exponha a raiz do projeto e não publique o servidor embutido do PHP na internet.

O servidor embutido pode responder a uma URL de diretório inexistente, como `/storage/`, usando o `index.php` público como fallback. Isso não publica a pasta privada; na configuração Nginx abaixo, `try_files` responde `404` para esse caminho. Confira a proteção novamente no servidor final.

Edite `app/config/app.php` para trocar identidade, cores, tema e loader. Imagens usam caminhos locais relativos a `public/`, sem URL externa, query string ou fragmento. `environment.example` documenta variáveis; não existe leitura automática de `.env`.

## 1. Preparar a VPS

Referência para uma VPS nova Debian 13, com acesso administrativo:

```bash
sudo apt update
sudo apt install nginx php8.4-fpm php8.4-cli
sudo useradd --system --user-group --no-create-home --shell /usr/sbin/nologin nextnav
sudo install -d -o root -g root -m 0755 /var/www/nextnav
sudo install -d -o nextnav -g nextnav -m 0750 /var/log/nextnav
```

Envie o projeto inteiro para `/var/www/nextnav`, preservando `app/` e `public/` como pastas irmãs. O usuário de implantação deve ser dono do código; o processo `nextnav` precisa apenas lê-lo, e o Nginx precisa ler `public/`. Não use permissão `777` e não conceda escrita no código ao PHP.

Os exemplos não gravam arquivos no servidor. Quando um produto precisar de arquivos, conceda escrita somente na subpasta correspondente de `storage/`. Mantenha uploads executáveis fora de `public/`.

DNS e certificado TLS válido para o domínio são pré-requisitos para publicar o site. Os caminhos de certificado abaixo são exemplos, não arquivos entregues pelo starter.

## 2. Configurar o PHP-FPM

Crie no servidor `/etc/php/8.4/fpm/pool.d/nextnav.conf`, sem substituir pools de outros sistemas:

```ini
[nextnav]
user = nextnav
group = nextnav
listen = /run/php/nextnav.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660
pm = ondemand
pm.max_children = 5
pm.process_idle_timeout = 10s
clear_env = yes
security.limit_extensions = .php
env[APP_ENV] = production
php_admin_flag[display_errors] = off
php_admin_flag[display_startup_errors] = off
php_admin_flag[log_errors] = on
php_admin_value[error_reporting] = E_ALL
php_admin_value[error_log] = /var/log/nextnav/php-errors.log
php_admin_value[expose_php] = off
```

Ajuste `pm.max_children` à memória e à carga medidas, não ao número de usuários cadastrados. O socket Unix não deve ser exposto na rede. Com `clear_env = yes`, apenas as variáveis explicitamente liberadas chegam aos processos do pool. Consulte a [configuração oficial do PHP-FPM](https://www.php.net/manual/en/install.fpm.configuration.php).

O banco é opcional nesta fase. Ao implementar persistência, instale o driver escolhido, por exemplo `sudo apt install php8.4-mysql`, e acrescente ao mesmo pool:

```ini
env[DB_DSN] = "mysql:host=127.0.0.1;port=3306;dbname=nextnav;charset=utf8mb4"
env[DB_USERNAME] = "usuario-do-aplicativo"
env[DB_PASSWORD] = "substitua-no-servidor"
```

Use um usuário de banco com os privilégios necessários ao produto. Proteja o arquivo do pool com proprietário `root:root` e permissão `0600`, pois passa a conter segredo; não copie esse arquivo para o projeto, repositório ou `public/`. Use aspas e escapes conforme a sintaxe INI quando houver caracteres especiais. Não passe credenciais em parâmetros HTTP. `APP_ENV` pode ser definido também no ambiente do processo em outros servidores; no FPM, exportá-lo apenas no terminal não configura os workers.

O bootstrap responde genericamente a exceções não tratadas e registra classe, arquivo e linha no log do PHP. Não registra automaticamente a mensagem da exceção, SQL ou dados de entrada. `APP_ENV` identifica esse registro, mas não ativa login nem muda permissões. Trate erros esperados nas páginas e processe requisições antes de emitir HTML. Erros anteriores ao bootstrap, falhas fatais do motor ou saída já enviada dependem também da configuração do servidor; por isso `display_errors` deve estar desligado no pool.

Restrinja leitura dos logs aos responsáveis, configure rotação e retenção no servidor, e nunca registre senhas, tokens ou dados pessoais desnecessários.

## 3. Configurar o Nginx

Crie `/etc/nginx/sites-available/nextnav`, ajustando domínio e certificados já emitidos:

```nginx
server {
    listen 80;
    server_name nextnav.exemplo.com;
    return 301 https://nextnav.exemplo.com$request_uri;
}

server {
    listen 443 ssl;
    server_name nextnav.exemplo.com;
    ssl_certificate /etc/letsencrypt/live/nextnav.exemplo.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/nextnav.exemplo.com/privkey.pem;

    root /var/www/nextnav/public;
    index index.php;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ /\. {
        return 404;
    }

    location ~ \.php$ {
        try_files $uri =404;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTPS on;
        fastcgi_pass unix:/run/php/nextnav.sock;
    }
}
```

As páginas são arquivos PHP reais; não há rewrite para um roteador central. O Nginx encaminha somente arquivos PHP existentes ao pool privado, conforme o contrato [FastCGI do Nginx](https://nginx.org/en/docs/http/ngx_http_fastcgi_module.html). Não crie aliases para `app/`, `storage/` ou para a raiz do projeto.

Ative apenas o novo site e valide a configuração antes de recarregar os serviços:

```bash
sudo ln -s /etc/nginx/sites-available/nextnav /etc/nginx/sites-enabled/nextnav
sudo php-fpm8.4 -t
sudo nginx -t
sudo systemctl reload php8.4-fpm
sudo systemctl reload nginx
```

Se algum teste falhar, corrija antes de recarregar. Não remova configurações de outros sites. Restrinja a exposição pública às portas necessárias; mantenha o banco privado e o acesso administrativo controlado. Certificados precisam de renovação automática validada.

## 4. Conferir a instalação

- `/` e as páginas dos módulos respondem sem erros; CSS, JavaScript e imagens usam HTTPS.
- Menu reabre no nível correto, tema funciona, formulários e loader encerram após sucesso, cancelamento e erro.
- `/app/config/app.php`, `/environment.example`, `/storage/`, `/AGENTS.md` e caminhos inexistentes respondem `404`, sem revelar conteúdo privado.
- PHP executa os arquivos; nunca é oferecido como download ou texto-fonte.
- O arquivo de log pode ser escrito pelo pool e não pode ser lido pela web.
- Driver e conexão são validados no ambiente real quando o banco for utilizado. CLI e FPM podem carregar extensões/configurações diferentes; não deixe `phpinfo()` público para testar.
- Faça uma verificação por teclado e nos temas claro/escuro, inclusive em tela pequena.

Este starter não oferece autenticação, autorização nem isolamento entre clientes. Não coloque dados reais ou sensíveis nos exemplos. Antes de liberar um produto real, implemente essas fronteiras, proteção CSRF nas escritas, validação no servidor e a política de sessão adequada ao produto. Ocultar módulos de demonstração no menu não bloqueia URLs.
