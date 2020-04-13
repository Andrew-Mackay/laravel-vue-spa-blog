# Laravel + Vue SPA Blog

## Overview
This is an individual blogging website that allows the user to create blog posts using a markdown editor with support for images. 

To create a new blog post the logged in user clicks on the 'New Post' button. This navigates to the new post screen where the user provides the title, summary and adds the content of the blog post using markdown and the included image manager. Of the uploaded images, the user can specify which image should be used as the header image. During editing the post can be instantly previewed by clicking the 'preview' button. During creation the post can either be saved as a draft or can be published immediately. 

To manage published and draft posts the user can click on the 'mange posts' button. Here, the user can edit or delete existing posts and publish any posts that are saved as a draft.

The published blog posts are displayed in a list format on the home page with a title, header image and summary of the post. To view the full post the user simply clicks on the item and is taken to the full blog post page.

The site also includes an about section to provide an overview of the blog.

## Technology
The backend of this project uses the [Laravel](https://laravel.com/) PHP framework whilst the frontend utilizes the [Vue.js](https://vuejs.org/) JavaScript framework. The project has been containerized using [Docker](https://www.docker.com/) with the aid of the [Laradock](https://laradock.io/) project.

## Getting Started

*NB: Requires [Docker](https://www.docker.com/) to be installed*

1) Clone the repository and laradock submodule:

    - `git clone --recurse-submodules git@github.com:Andrew-Mackay/k_blog.git`

2) Obtain a copy of the laravel `.env` and laradock `.env`

3) From within the laradock folder, start the docker containers with the command:

    - `docker-compose up -d nginx mysql`

4) Open a terminal in the laradock workspace using the command:  

    - `docker-compose exec --user=laradock workspace bash`

5) From within the workspace:
    1) Install the required packages:
        - `composer install`
        - `npm install`
    2) Apply the database migrations:
        - `php artisan migrate:refresh`
    3) Generate fresh keys:
        - `php artisan key:generate`
        - `php artisan jwt:secret`
    4) Create a new user:
        - `php artisan tinker`
            - `$user = new App\User();`
            - `$user->password = Hash::make('your-password');`
            - `$user->email = 'your-email@example.com';`
            - `$user->name = 'Your Name';`
            - `$user->save();`
    5) Compile the Vue components:
        - `npm run watch` 
            - If this does not automatically rebuild when you make changes, run `npm run watch-poll` instead

8) Open a browser and navigate to `localhost`

9) When finished, shutdown the docker containers with the command:  

    `docker-compose down`

### Windows 10 Home Workaround

If you are using Windows 10 Home edition you will need to use Docker-toolbox. The following workarounds may need to be added to accommodate this:

- Change `npm install` to `npm install --no-bin-links`
- Run `npm install -g cross-env`
- Add `innodb_use_native_aio=0` to `laradock/mysql/my.conf` under the section `mysqld`
- Navigate to the IP address of the default machine that docker uses in your browser instead of `localhost`  

### Possible Next Steps
Although I will no longer be continuing this project, here is a list of possible next steps that could be added to improve the site:
- comment section on posts
- 'like' system mechanic
- infinite scroll list of posts so they are not all loaded at once
- markdown editor improvements
    - The current editor requires the user to know markdown, one possible improvement would be to add dedicated buttons for the most common markdown features such as bold, italics, horizontal rule and font sizes
- share post to social media buttons
- 'back to posts' button to navigate from the current blog post you are reading to the list of all blog posts
- estimated read times
- recording of stats such as total site views and individual post views
- general stylistic improvements to make the site more aesthetically pleasing