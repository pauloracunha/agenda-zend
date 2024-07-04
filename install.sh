#!/bin/bash
docker compose up -d
docker exec -i contact_app sh -c 'composer install'