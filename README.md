# Symfony Integration with Magento

📦 Microservices with Symfony 5.

Installation
------------

In order to run this app do the following:

### 1-Minute Install

- Get the application code and install php dependencies and node packages.
```bash
git clone https://github.com/VuThuan/symfony-magento.git symfony-magento
```
- Open the Magento project, create new db name is `magento243` and run command
```bash
  cd magento243
  composer update 
  bin/magento setup:install --base-url=http://magento243.local/ \                                                   
    --db-host=localhost --db-name=amlabel --db-user=root --db-password=admin123 \
    --admin-firstname=Magento --admin-lastname=User --admin-email=user@example.com \
    --admin-user=admin --admin-password=admin123 --language=en_US \
    --currency=USD --timezone=America/Chicago --cleanup-database \
    --sales-order-increment-prefix="ORD$" --session-save=db --use-rewrites=1 \
    --search-engine=elasticsearch7 --elasticsearch-host=localhost \
    --elasticsearch-port=9200
```
- In case you work with LAMP Server, you will need to configure your apache virtual host for Magento project.
```
<VirtualHost *:80>
    ServerAdmin admin@magento243.local
    ServerName magento243.local
    ServerAlias www.magento243.local
    DocumentRoot /var/www/symfony-magento/magento243/pub
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
        <Directory /var/www/symfony-magento/magento243/pub>
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Order allow,deny
            allow from all
        </Directory>
</VirtualHost>
```
- After that you need following guide to create new [Integration](https://docs.magento.com/user-guide/system/integrations.html)

Make sure you set the **Resource Access** for `Magento_Catalog::products`
- Copy the **Access token** and **Base url Magento** into Symfony settings file located
```
namespace App\Controller;

class DefaultController extends AbstractController
{
    protected $urlMagento = "http://magento243.local/";

    protected $accessToken = "mff8x55yav8vszt0vb56vvvrxbxzobxk";
}
```
Deploy The Symfony Application
----------------------
- Download and install the `symfony-cli` tool
```bash
wget https://get.symfony.com/cli/installer -O - | bash
```
- After that, add the Symfony installation path to the `PATH` user
```bash
export PATH="$HOME/.symfony/bin:$PATH"
```
- Open the Symfony project
```bash
cd symfony
composer install
symfony server:start
```
- And now, the server will running on http://127.0.0.1:8000

Result
----
- Video demo : https://monosnap.com/direct/cTRF6MZYQZF5WQcL8RnSTvfGqEYLzV
  
About Project
----
This project works like this:
- The symfony side will have the effect of displaying the sku replace form, push the data to the Magento server through the api and receive the returned data for display.
- Magento side will be responsible for storing data and handling those APIs through REST API
- About the replace form sku will work as follows: When user input OLD_SKU and press replace button will check if this sku exists on Magento server side or not. Otherwise, it will show an error. And NEW_SKU is similar but opposite.