git  clone https://github.com/ajithmv-web/mighty-warners-project.git
cd mighty-warners-project
#
composer install
#
npm install
#
copy .env.example .env
#
php artisan key:generate
#
# Edit .env file with your database credentials:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

#
php artisan migrate --seed
#
php artisan serve
http://127.0.0.1:8000/
#
Admin Email- admin@gmail.com
Password - Admin1@112
#
User Email- user@gmail.com
Password - User2@112



