# "OCD Thoughts" Blog

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

6) From within the workspace create a new user:
    - `php artisan tinker`
        - `$user = new App\User();`
        - `$user->password = Hash::make('your-password');`
        - `$user->email = 'your-email@example.com';`
        - `$user->name = 'Your Name';`
        - `$user->save();`

7) From within the workspace run:
    - `npm run watch` 
        - If this does not automatically rebuild when you make changes, run `npm run watch-poll` instead

8) Open a browser and navigate to `localhost`

9) When finished, shutdown the docker containers with the command:  

    `docker-compose down`

### Windows 10 Home Workaround

If using Windows 10 Home edition you will need to use Docker-toolbox. The following workarounds must be added to accommodate this:

- Change `npm install` to `npm install --no-bin-links`
- Run `npm install -g cross-env`
- Add `innodb_use_native_aio=0` to `laradock/mysql/my.conf` under the section `mysqld`
- Navigate to the IP address of the default machine that docker uses in your browser instead of `localhost`  
