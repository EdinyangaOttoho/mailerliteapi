# MailerLite Integration Example
This project demonstrates the integration of the MailerLite API

## Project Setup
The following are to be considered when running this project:

### Database Configuration
Create a database and update its connection in the .env configuration file of the Laravel project.

In this repository exists a file called "mailerliteapi.sql".

Import the table called "server" it into your database.

Hurray! You're up for connection

### Running the project

You need to have the API URL setup in your .env. That can be done by adding the key, MAILERLITE_API_URL.

The default value is: https://api.mailerlite.com/api/v2 and must be updated.

Once that is done, you can navigate to the project root directory and type:


```bash
php artisan serve
```



Congratulations. You have a basic setup of the MailerLite HTTP Client!!
