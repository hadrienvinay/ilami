"# imali" 

To use this project: 
1. Make sure you have composer install on your machine : https://getcomposer.org/download/ / apt install composer
2. Download the repository : git clone https://...
3. run composer install (https://symfony.com/doc/current/setup.html for explains) in the ilami repo
4. Create the database scheme : php bin/console doctrine:database:create (https://symfony.com/doc/current/doctrine.html)
5. Update it : php bin/console doctrine:schema:update
6. Update your database configuration in the .env file : line: DATABASE_URL=mysql://root:@127.0.0.1:3306/imali
7. Run your server : symfony server:start
8. Go to localhost:8000 and enjoyy :D 

   


