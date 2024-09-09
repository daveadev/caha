# Caha Pay
A fork from SRP (Simplified Receivables &amp; Payables)

# Setup
 * mkdir caha
 * git clone --recursive --depth=20 https://github.com/daveadev/caha.git .
  or git clone git@github.com:daveadev/caha.git .
 * Copy & modify config & webroot files:
 	- api/config/core.php.default --> api/config/core.php
 	- api/config/database.php.default --> api/config/database.php
 	- api/webroot/index.php.default --> api/webroot/index.php
 * In config/database.php , update the login credentials
 * In webroot/index.php, update the cake core path
 * Create tmp folders
 	- mkdir tmp
 	- mkdir tmp/logs
 	- mkdir tmp/cache
 	- mkdir tmp/cache/models
 	- mkdir tmp/cache/persistent
 	- mkdir tmp/cache/system01
 	- chmod -R 777 tmp
 * Create reports folder
 	- mkdir reports
 	- chmod -R 777 reports
 * Import database from backup


