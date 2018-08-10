#!/bin/bash

sqlite3 -init create-tables.sql nelex.db .exit

echo Database initialized.