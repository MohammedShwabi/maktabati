<p align="center">
  <a href="https://github.com/MohammedShwabi/maktabati">
 <img src="img/logo.png" alt="Logo" height="100"></a>
</p>

<h3 align="center">maktabati</h3>

<div align="center">

![Status][status-shield]
[![GitHub Pull Requests][pull-shield]][pull-url]
[![License][license-shield]][license-url]

</div>

<p align="center">
Electronic library management website
<br> 
</p>

## 📝 Table of Contents

- [About](#about)
- [Screenshots](#screenshots)
- [Built Using](#built_using)
- [Prerequisites](#prerequisites)
- [Getting Started](#getting_started)
  - [Clone the Repository](#clone)
  - [Setting Up the Environment](#environment)
  - [Database Setup](#db_setup)
  - [Running the Project](#running_project)
- [Troubleshooting](#troubleshooting)
- [Future Work Recommendations](#recommendations)
- [Contributing](#contributing)
- [License](#license)
- [Authors](#authors)


## 🧐 About <a name = "about"></a>

![web site story page][books-screenshot]

Maktabati is a PHP web project designed for managing an electronic library.
This project includes a control panel that allows you to efficiently manage your library's resources. 
You can easily add, delete, and edit both books, authors, and categories, making it a powerful tool for library administration.

## Features

- **Library Management**: Add, delete, and edit books, authors, and categories.
- **Advanced Search**: Utilize the advanced search feature to find specific resources within the library.
- **Book Information**: View detailed information about each book, including ratings.
- **Electronic Copies**: Upload electronic copies of books for easy access.
<br> 

<!-- :camera: -->
## 📷 Screenshots <a name = "screenshots"></a>
<b>Here are some screenshots of the project:</b>

<b>Login Page:</b>

![Login Page][login-screenshot]

<b>Books List Page:</b>

![books list page][books-screenshot]

<b>Categories List Page:</b>

![categories list page][categories-screenshot]

<b>Authors List Page:</b>

![authors list page][authors-screenshot]

<b>Advance Search Page:</b>

![advance search page][advance-screenshot]

<b>Book Details Page:</b>

![book details page][book-details-screenshot]

## ⛏️ Built Using <a name = "built_using"></a>

* [![php][php.com]][php-url]
* [![MySQL][MySQL.com]][MySQL-url]
* [![Bootstrap][Bootstrap.com]][Bootstrap-url]
* [![JQuery][JQuery.com]][JQuery-url]

<!-- :gear: -->
## ⚙️ Prerequisites <a name = "prerequisites"></a>

Before you begin, ensure you have met the following requirements:

- **PHP** (>= 8.2.4) installed on your local machine.
- **Apache** (>= 2.4.56) installed and configured.
- **MySQL** (>= 8.2.4) or MariaDB database server.
- **Git** You'll need Git to clone the repository.

<!-- :checkered_flag: -->
## 🏁 Getting Started <a name = "getting_started"></a>

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

<!-- :open_file_folder: -->
## 📂 Clone the Repository <a name = "clone"></a>

Open your terminal/command prompt and run the following command to clone the repository:

```bash
git clone https://github.com/MohammedShwabi/maktabati
```
## 💻 Setting Up the Environment <a name = "environment"></a>
1. Configure your web server to serve the project from the cloned directory. For example, with Apache, you can create a virtual host configuration:

```apacheconf
<VirtualHost *:80>
    ServerName maktabati.local
    DocumentRoot "/path/to/maktabati"
    <Directory "/path/to/maktabati">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
Replace "/path/to/maktabati" with the actual path to your project directory.

2. Update your system's hosts file to point the domain to your local machine. On most systems, you can find this file at /etc/hosts (Linux/Mac) or C:\Windows\System32\drivers\etc\hosts (Windows). Add an entry like this:

```lua
127.0.0.1   maktabati.local
```

## 💾 Database Setup <a name = "db_setup"></a>
1. Create a MySQL or MariaDB database for maktabati. You can do this using a tool like phpMyAdmin or via the command line:

```sql
CREATE DATABASE maktabati;
```
2. Import the 'maktabati.sql' file into your newly created database. You can do this using the command-line MySQL client:

```bash
mysql -u your_username -p maktabati < maktabati.sql
```
Replace 'your_username' with your database username and enter the corresponding password when prompted.

3. Verify that the tables and test data have been imported successfully:

```sql
USE maktabati;
SHOW TABLES;
```
This should display a list of tables, indicating that the database setup was successful.

## 🚀 Running the Project <a name = "running_project"></a>
Now that you have set up the project and the database, you can run the maktabati project on your local web server. Open a web browser and navigate to http://maktabati.local (or the domain you configured in your web server).

2. enter the following credential to login to the web panel:
```js
email: mohamed@gmail.com
password: 123
```

<!-- :warning: -->
## ⚠️ Troubleshooting <a name = "troubleshooting"></a>
<p>If you encounter any issues during the setup process, refer to the <a href="https://www.php.net/docs.php" target="_new">PHP documentation</a> or search for solutions on <a href="https://stackoverflow.com/" target="_new">Stack Overflow</a>.</p>

<!-- 💡 -->
## 🔧 Future Work Recommendations <a name = "recommendations"></a>

While the current version of Maktabati is functional, there are several areas where the project can be further developed and enhanced:

- 🌐 Auto-complete advanced search functionality.
- 👥 User management system.
- 👤 User pages and user profiles.
- 🌍 Support for multiple languages for books, authors and publishers.
- 🧹 Sorting and filtering options for books.
- 👥 Ability to associate multiple authors with a book.

If you are interested in contributing to the project, consider working on these future enhancements to make Maktabati even better!


<!-- :raised_hands: -->
## 🙌 Contributing <a name = "contributing"></a>
If you'd like to contribute to the project, feel free to submit pull requests.

<!-- :scroll: -->
## 📜 License <a name = "license"></a>
<p>This project is licensed under the <a href="https://github.com/MohammedShwabi/maktabati/blob/main/LICENSE.md">MIT License</a>.</p>

## ✍️ Authors <a name = "authors"></a>

- [@MohammedShwabi](https://github.com/MohammedShwabi) 
- [@HeshamNoaman](https://github.com/HeshamNoaman) 

See also the list of [contributors](https://github.com/MohammedShwabi/maktabati/contributors) who participated in this project.

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
<!-- small icon -->
[status-shield]: https://img.shields.io/badge/status-active-success.svg

[pull-shield]: https://img.shields.io/github/issues-pr/kylelobo/The-Documentation-Compendium.svg
[pull-url]: https://github.com/MohammedShwabi/hekayti-laravel/issues/pulls

[license-shield]: https://img.shields.io/badge/license-MIT-blue.svg
[license-url]: https://github.com/MohammedShwabi/maktabati/blob/main/LICENSE.md

<!-- built using icons -->
[php.com]: https://img.shields.io/badge/php-777BB4?style=for-the-badge&logo=php&logoColor=white
[php-url]: https://www.php.net/
[Bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]: https://getbootstrap.com
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com 
[MySQL.com]: https://img.shields.io/badge/mysql-4479A1?style=for-the-badge&logo=mysql&logoColor=white
[MySQL-url]: https://mysql.com/

<!-- image -->
[login-screenshot]: /screenshot/screenshot.jpeg
[books-screenshot]: /screenshot/screenshot1.jpeg
[categories-screenshot]: /screenshot/screenshot2.jpeg
[authors-screenshot]: /screenshot/screenshot3.jpeg
[advance-screenshot]: /screenshot/screenshot4.jpeg
[book-details-screenshot]: /screenshot/screenshot5.jpeg
