[![Maintainability](https://api.codeclimate.com/v1/badges/3031ba24ea94a94ee13b/maintainability)](https://codeclimate.com/github/OlivierFL/blog/maintainability) 
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=OlivierFL_blog&metric=alert_status)](https://sonarcloud.io/dashboard?id=OlivierFL_blog)

# Blog
Project 5 - First blog built with PHP

# Third party libraries

In order to install third party libraries, run `composer install`.

Packages used in this project :

- __Nikic FastRoute__ to handle routing
- __Twig__ as template engine
- __Slugify__ to generate slugs for blog posts
- __PhpMailer__ to send emails

# Docker Installation
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

When using Docker, the name of the `database host` is not `localhost`, but the name of the container, which by default is `mysql`.

An SQL dump file with sample data is available in the `dumps` folder.

The phpMyAdmin container url is `blog.localhost:8000` with `root` login and `admin` password. This can be changed in the `docker-compose` file.

# Sample data

In order to have a fully functional blog, the SQL file contains :

- 3 users with different states :
    - an admin user with `admin@example.com` login email and `Admin1234!` password. This user has role _admin_ and can create blog posts, and handle users, comments and social networks in admin dashboard.
    - a simple user with `user@example.com` login email and `User1234!` password. This user can post comments on blog posts.
    - a _deleted_ user. This user has role _disabled_, personal data are anonymised and user can't connect to the blog.
    
- 5 blog posts. 3 posts per page are displayed with pagination.
- 5 comments created by various users and in different states (_pending_, _rejected_ and _approved_).

# Sending emails

Emails are sent with `php mailer` _package_. To send emails, create `mail.yaml` configuration file. An example configuration is available in `mail.yaml.example` file.
