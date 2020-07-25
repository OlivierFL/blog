[![Maintainability](https://api.codeclimate.com/v1/badges/3031ba24ea94a94ee13b/maintainability)](https://codeclimate.com/github/OlivierFL/blog/maintainability) 
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=OlivierFL_blog&metric=alert_status)](https://sonarcloud.io/dashboard?id=OlivierFL_blog)

# blog
Project 5 - First blog built with PHP

# Installation
This project is developed with Docker.

To install this project on your local machine, simply clone this repository.

The stack is composed of 4 containers : 
- nginx
- php
- mysql
- phpMyAdmin

Then launch the dev stack with Docker : `docker-compose up -d`.

The homepage is available on : `blog.localhost:8080`

# Database configuration
Database configuration example is available in `db-config.yaml.example`.


In order to have a working connection, copy and paste the content of `db-config.yaml.example` in a file called `db_config.yaml`.
This file will be parsed to get the data needed by the PDOFactory to connect to the database. 

When using Docker, the name of the `database host` is the not `localhost`, but the name of the container, which by default is `mysql`.
