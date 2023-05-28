## Docker Development environment

1. Add `INSTALLING` file in config/ 
   - `touch config/INSTALLING`
2. Run
   - `docker compose up -d`
3. Create `antragsblau` database
   - `docker exec docker-db-1 mysql -uroot -ppassword -e "CREATE DATABASE antragsblau;" `
5. Install composer and nmp dependencies
   - `composer install --prefer-dist`
   - `npm install && npm build`
4. Goto your ip address in your browser
