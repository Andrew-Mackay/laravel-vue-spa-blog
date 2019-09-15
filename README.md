# "K Blog"

*Blogging website for Kirsty.*

The backend of this project uses the [Laravel](https://laravel.com/) PHP framework whilst the frontend utilizes the [Vue.js](https://vuejs.org/) JavaScript framework. The project has been containerized using [Docker](https://www.docker.com/) with the aid of the [Laradock](https://laradock.io/) project.

## Getting Started

*NB: Requires [Docker](https://www.docker.com/) to be installed*

1) Clone the repository and laradock submodule:

    `git clone --recurse-submodules git@github.com:Andrew-Mackay/k_blog.git`

2) Obtain a copy of the laravel `.env` and laradock `.env`

3) From within the laradock folder, start the docker containers with the command:

     `docker-compose up -d nginx mysql`

4) Open a terminal in the laradock workspace using the command: 

    `docker-compose exec --user=laradock workspace bash`

5) From within the workspace run:
    - `composer install`
    - `php artisan migrate:refresh`
    - `npm install`
    - `npm run watch`

6) Open a browser and navigate to `localhost`

7) When finished, shutdown the docker containers with the command: 

    `docker-compose down`

### Windows 10 Home Workaround

If using Windows 10 Home edition you will need to use Docker-toolbox. The following workarounds must be added to accommodate this:

- Change `npm install` to `npm install --no-bin-links`
- Run `npm install -g cross-env`
- Add `innodb_use_native_aio=0` to `laradock/mysql/my.conf` under the section `mysqld`
