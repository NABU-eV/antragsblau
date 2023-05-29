## Docker Development environment

1. Add `INSTALLING` file in config/ 
   - `touch config/INSTALLING`
2. Run
   - `docker compose up -d`
3. Create `antragsblau` database
   - `docker exec docker-db-1 mysql -uroot -ppassword -e "CREATE DATABASE antragsblau;" `
4. Install composer and npm dependencies
   - `composer install --prefer-dist`
   - `npm install && npm build`
6. Goto your ip address in your browser on port 4242
7. Install antragsblau in your browser with the following db credentials
`Host: db`
`username: root`
`password: password`
`db name: antragsblau`
8. Celebrate!