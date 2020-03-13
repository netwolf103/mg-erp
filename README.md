# MG-ERP
基于symfony4开发的ERP系统，主要用来管理Magento1X订单、产品，客户等。

# MG-ERP
基于symfony4开发的ERP系统，主要用来管理Magento1X订单、产品，客户等。

# 代码维护人员
![头像](https://avatars3.githubusercontent.com/u/1772352?s=100&v=4)
------------
Zhang Zhao <netwolf103@gmail.com>
------------
Wechat: netwolf103

## MG ERP 安装

### 安装 MySQL & RabbitMQ

### 安装php_amqp
    wget https://pecl.php.net/get/amqp-1.9.4.tgz
    tar zxvf amqp-1.9.4.tgz
    cd amqp-1.9.4
    /php-bin-path/phpize
    ./configure --with-php-config=/php-bin-path/php-config
    make
    make install

### 安装 & 配置Supervisor
    yum install supervisor
    systemctl start supervisord
    systemctl enable supervisord

#### 配置Supervisor
    ; /etc/supervisord.d/messenger-worker.ini
    [program:messenger-consume]
    command=php /path/to/your/app/bin/console messenger:consume pull:catalog:category:product catalog:category:product:stock:alert catalog:category:product:google:create catalog:category:product:google:push catalog:category:product:google:delete pull:sales:order push:sales:order:hold push:sales:order:unhold push:sales:order:comment push:sales:order:shipment pull:sales:order:shipment push:sales:order:shipment:platform pull:sales:order:invoice push:sales:order:address pull:sales:order:address:geo push:sales:order:shippingmethod push:sales:order:email push:sales:order:send:confirm:email pull:sales:order:payment:transaction pull:customer --time-limit=3600
    user=www
    numprocs=2
    autostart=true
    autorestart=true
    process_name=%(program_name)s_%(process_num)02d

#### 启动Supervisor
    supervisorctl reread
    supervisorctl update
    supervisorctl start messenger-consume:*
    supervisorctl status

### 软件包依赖
    "php": "^7.1.3",
    "ext-ctype": "*",
    "ext-iconv": "*",
    "dompdf/dompdf": "^0.8.3",
    "google/apiclient": "^2.0",
    "liip/imagine-bundle": "^2.1",
    "sensio/framework-extra-bundle": "^5.1",
    "symfony/apache-pack": "^1.0",
    "symfony/asset": "4.3.*",
    "symfony/console": "4.3.*",
    "symfony/dotenv": "4.3.*",
    "symfony/expression-language": "4.3.*",
    "symfony/flex": "^1.3.1",
    "symfony/form": "4.3.*",
    "symfony/framework-bundle": "4.3.*",
    "symfony/http-client": "4.3.*",
    "symfony/intl": "4.3.*",
    "symfony/messenger": "4.3.*",
    "symfony/monolog-bundle": "^3.1",
    "symfony/orm-pack": "*",
    "symfony/process": "4.3.*",
    "symfony/security-bundle": "4.3.*",
    "symfony/serializer-pack": "*",
    "symfony/swiftmailer-bundle": "^3.1",
    "symfony/translation": "4.3.*",
    "symfony/twig-bundle": "4.3.*",
    "symfony/validator": "4.3.*",
    "symfony/web-link": "4.3.*",
    "symfony/yaml": "4.3.*"
### 开发包依赖
    "symfony/debug-pack": "*",
    "symfony/maker-bundle": "^1.0",
    "symfony/profiler-pack": "*",
    "symfony/test-pack": "*",
    "symfony/web-server-bundle": "4.3.*"
### 依赖库安装
    composer install

### 编辑配置(生产)

    vim .env.local

    # dev or prod
	APP_ENV=prod

	# DB info
	DB_NAME=mg-erp
	DB_HOST=localhost
	DB_PORT=3306
	DB_USER=user
	DB_PASS=pass

	# Develop Paypal
	PAYPAL_CLIENTID="Your Client Id"
	PAYPAL_CLIENTSECRET="Your Client Secret"

	# Develop Oceanpayment
	OC_ACCOUNT="Your Account"
	OC_TERMINAL="Your Terminal"
	OC_SECURECODE="Your secure code"

	DATABASE_URL=mysql://user:pass@localhost:3306/mg-erp
	MESSENGER_TRANSPORT_DSN=amqp://user:pass@localhost:5672/%2f/

### 执行SQL生成表结构
	php bin/console --env=prod doctrine:migrations:migrate

### 运行APP
	php bin/console --env=prod server:run

### 浏览器访问
#### 初始账号 & 密码 admin 111111
	http://127.0.0.1:8000

### Web服务器配置
#### 以Apache为例，运行
	composer require symfony/apache-pack
#### 配置Vhosts
	<VirtualHost *:80>
	    ServerName domain.tld
	    ServerAlias www.domain.tld

	    DocumentRoot "/var/www/project/public"
	    ErrorLog "logs/domain.tld-error_log"
	    CustomLog "logs/domain.tld-access_log" combined
	    <Directory "/var/www/project/public">
	        Options Indexes FollowSymLinks
	        AllowOverride All
	        Require all granted
	    </Directory>
	</VirtualHost>

### 应用命令
#### 同步产品
    php bin/console app:magento:sync-catalog-product <api_username> <api_key> <api_url>
#### 同步产品库存
    php bin/console app:magento:sync-catalog-inventory <api_username> <api_key> <api_url>
#### 同步物流配置
    php bin/console app:magento:sync-config-shipping-method <api_username> <api_key> <api_url>
#### 同步客户
    php bin/console app:magento:sync-customer <api_username> <api_key> <api_url>
#### 同步订单
    php bin/console app:magento:sync-sales-order <api_username> <api_key> <api_url>
#### 同步订单交易号
    php bin/console app:magento:sync-sales-order-payment-transaction <api_username> <api_key> <api_url>
#### 同步订单物流单号
    php bin/console app:magento:sync-sales-order-shipment <api_username> <api_key> <api_url>
## 部分功能展示
![退款列表](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview1.png?raw=true)
![订单详情](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview2.png?raw=true)
![导入物流单号](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview3.png?raw=true)
![手动添加物流单号](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview4.png?raw=true)
![订单产品换货](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview5.png?raw=true)
![增加订单产品](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview6.png?raw=true)
![编辑订单地址](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview7.png?raw=true)
![发票详情](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview8.png?raw=true)
![订单详情功能](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview9.png?raw=true)
![订单列表](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview10.png?raw=true)
![命令行](https://github.com/netwolf103/mg-erp/blob/master/preview/Preview11.png?raw=true)
