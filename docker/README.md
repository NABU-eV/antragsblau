## Docker Development environment

1. Add `INSTALLING` file in config/ 
   - `touch config/INSTALLING`
2. Run
   - `docker compose up -d`
3. Install composer and npm dependencies
   - `composer install --prefer-dist`
   - `npm install && npm build`
4. Goto your ip address in your browser on port 4242
5. Install antragsblau in your browser with the following db credentials
`Host: db`
`username: root`
`password: password`
`db name: antragsblau`
6. Celebrate!
