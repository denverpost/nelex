# nelex
The Denver Post's Next Election Results site.

## Usage

* Requirements [SQLite3](https://www.sqlite.org/index.html)

### Dev Setup

#### Database
Uses an SQLite3 database to track ongoing things; use `bash init-db.bash` to initialize and empty database.

#### Test data
From the repo root, extract the `test-data.tar` file and move its contents to the web path with `tar -xvf test-data.tar; mv 20180626 www/results/`. 
