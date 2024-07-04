#!/bin/bash
docker exec -i contact_db sh -c 'exec mysql -uroot -p"root"' < ./database/migration.sql