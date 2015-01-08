
# Development Setup

## Prerequisites

* \*AMP Stack  
  * Apache with virtual host configuration
  * MySQL with `root` user and empty password
  * PHP with a reasonable basic configuration
* [Composer](https://getcomposer.org/)
* [NodeJS with npm](http://nodejs.org/download/)  
  required for LESS file compilation

## Installation / Configuration

1. Clone the repository and initialize submodules

   ```bash
   git clone git@bitbucket.org:getunik/blutspendezentrum-basel-bleed-hd.git bleed-hd
   cd bleed-hd
   git submodule update --init --recursive
   ```
2. Install Composer Dependencies

   ```bash
   composer.phar install
   ```
3. Create (empty) Database

   ```bash
   echo "create database bleedhd" | mysql -u root -p
   cd Symfony
   php app/console doctrine:migrations:migrate
   ```
4. Create a User for yourself

	```bash
	php app/console fos:user:create --super-admin
	```
