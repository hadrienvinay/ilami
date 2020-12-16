"# imali" 

To use this project: 
1. Make sure you have composer install on your machine : https://getcomposer.org/download/ / apt install composer
2. Make sure you also have install php (apt install php) and modules (php-xml, mysql-server ...)
3. Download the repository : git clone https://...
4. run composer install (https://symfony.com/doc/current/setup.html for explains) in the ilami repo
5. Create the database scheme : php bin/console doctrine:database:create (https://symfony.com/doc/current/doctrine.html)
6. Update it : php bin/console doctrine:schema:update
7. Update your database configuration in the .env file : line: DATABASE_URL=mysql://root:@127.0.0.1:3306/imali
8 Run your server : symfony server:start
9 Go to localhost:8000 and enjoyy :D 

   


