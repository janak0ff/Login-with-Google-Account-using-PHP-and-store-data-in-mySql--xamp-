# Login-with-Google-Account-using-PHP-and-store-data-in-mySql--xamp-


install
https://getcomposer.org/Composer-Setup.exe





Create Google API project and get OAuth credentials.
click here Google Developers Console

Here are the steps
After this click on Create New Project link for create new project.
Enter Project Name and click on Create button.
Once you have created a new project then you can see your project list on web page.
After this click on Google API logo for go to the home page.
Once you have redirected to home page then select project from the project select box.
After click on project select box, then one modal will popup and under this, you can find a list of project, so select your project.
Now from left menu, you have to click on OAuth consent screen.
Once you have click on OAuth consent screen, then one page will load, here you have to define application name and after this click on save button.
When you have to click on save button, then after page will redirect another page, and here you have to click on Create credential button, so one drop-down menu will appear and from this, you have to select OAuth client ID.
After click on OAuth client ID menu then you have redirected to another page, and here you can find different Application type.
From different Application type option, you have to select Web application. Once you have select the Web application option, then one form will appear on the web page. Here you have to define Name and you have also define Authorized redirect URIs field and lastly click on Create button.
Once you have click on create button, then you can get your Client ID and your client secret key. You have to copy both keys for future use for implement Login using Google account using PHP.
Download / Install PHP Google API client library.
copy this on CMD:- composer require google/apiclient:"^2.0"
