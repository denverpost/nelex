#!/bin/bash

sqlite3 -init www/control/create-tables.sql www/nelex.db .exit

echo Database initialized.
