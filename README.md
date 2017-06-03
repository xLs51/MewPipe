MewPipe
======

MewPipe is a web application made with Symfony2 that allows users to host, share and play online videos.

Functionalities
------------

- Registered user :
  - Upload video with a particular confidentiality :
    - Public (available to anybody)
    - PrivateLink (available to	anybody unauthenticated with the link)
    - Private	(available only to authenticated users)
  - Edit video informations
  - Play video with a custom player
  - View and edit profile
  - Link profile to Google with OpenID to authenticate later with those credentials

- Administrator :
  - Show and manage all users
  
- Web Service :
  - Get user by id, name or email
  - Get video by id or name
  - Get the more recent / shared / viewed videos

Installation
------------

- Download this project
- Download the dependencies with composer `composer install` in the MewPipe folder
- Edit the `parameters.yml` with your configuration
- Create the database `php app/console doctrine:database:create`
- Update the schema	`php app/console doctrine:schema:update --force`

------------
###### SUPINFO Project - 4PJT - 03/2015
