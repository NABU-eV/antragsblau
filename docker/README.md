## Docker Development environment

1. Delete `config/config.json`
   <br/>
   <br/>
2. Add `INSTALLING` file in config/ 
   - `touch config/INSTALLING`
<br/>
<br/>

3. Run
   - `docker compose up -d`
<br/>
<br/>
4. Create `antragsblau` database
   - `docker exec docker-db-1 mysql -uroot -ppassword -e "CREATE DATABASE antragsblau;" `
<br/>
<br/>
5. Goto your ip address in your browser
