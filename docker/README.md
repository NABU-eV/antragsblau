## Docker Development environment

1. Delete `config/config.json`
2. Add `INSTALLING` file in config/ 
   - `touch config/INSTALLING`
3. Run
   - `docker compose up -d`
4. Create `antragsblau` database
   - `docker exec docker-db-1 mysql -uroot -ppassword -e "CREATE DATABASE antragsblau;" `
5. Install antragsgruen as described in the README.
6.  Goto your ip address in your browser
7. Install antragsblau in your browser with the following db credentials
`Host: db`
`username: root`
`password: password`
`db name: antragsblau`
8. Celebrate!
