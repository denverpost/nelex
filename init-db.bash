#!/bin/bash

sqlite3 -init create-tables.sql www/nelex.db .exit

echo Database initialized.
