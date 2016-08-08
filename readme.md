
# Development Setup

## Prerequisites

* \*AMP Stack
  * Apache with virtual host configuration
  * MySQL with `root` user and empty password
  * PHP with a reasonable basic configuration
* [Composer](https://getcomposer.org/)
* [NodeJS with npm](http://nodejs.org/download/)
  required for LESS file compilation

### Checking Prerequisites

```bash
composer --version
```
This should give you a version for your installed composer (no error).

```bash
node --version
```
This should give you a version for your Node.js installation (no error).

## Installation / Configuration

1. Clone the repository and initialize submodules
   ```bash
   git clone --recursive git@bitbucket.org:getunik/blutspendezentrum-basel-bleed-hd.git bleed-hd
   cd bleed-hd
   ```

2. Create (empty) Database
   ```bash
   echo "CREATE DATABASE bleed_hd DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci" | mysql -u root -p
   ```

3. Run the setup script
   ```bash
   ./scripts/setup.sh
   ```
   This script does quite a lot and asks you some questions regarding your setup.
   1. When the script asks your for parameters for your `app/config/parameters.yml` file, you can mostly stick to the defaults with the exceptions
      1. `database_user` should of course be the user name of your local MySQL database
      2. `database_password` should of course be the password of the user specified before
      3. `http_channel` here, you most likely want to enter `''` (empty string) if you don't have a properly configured HTTPS localhost
   2. At some point, the script will ask you to write down some generated OAuth tokens; do that. The text containing the tokens should look something like this: `Added a new client with public id XXX, secret YYY`
   3. Once the script is completed, open the file `Symfony/app/config/parameters.yml` and paste the OAuth tokens you copied in the previous step. The corresponding keys are `oauth_client_id: XXX` and `oauth_client_secret: YYY`
   4. Flush all the caches
   ```bash
   bin/console cache:clear --env=dev
   bin/console cache:clear --env=prod
   ```

4. Run a server
   You can either configure your webserver as you normally would, or if you want to keep it simple, just run
   ```bash
   Symfony/bin/console server:run
   ```
   If you chose to configure your existing webserver, make sure to point to the `Symfony/web` directory as the webroot.

For more instructions about development see [Development Instructions](doc/notes/development.md).

### Cron Job for Deleting Old Exports
To minimize the risk of data leakage, generated export files should be deleted after a fixed amount of time (30 minutes by default). This can be achieved with this very simple cron job

```
0,30      *      *       *       *       find /path/to/project/Symfony/var/export/* -type f -mmin +30 -delete
```

# Deployment / Release
The project uses a Git based deplyoment process and the _usual suspects_ for any Symfony based project.

**Before you update**:
* make sure that everything has been tested properly
* always make sure that you have a _very_ recent backup of the database.
* make sure you have a Jira release in the BBSBLEED project with the proper version number for the upcoming release (the version number in the sample commands below is "A.B.C" - semantic versioning!)

The release and deployment steps are deliberatly not _fully_ automated. This gives you a chance to do some deliberate sanity checks between each of the manual steps and avoid _accidentally_ incomplete releases.

**Prepare the release**:

```bash
git flow release start A.B.C
scripts/version-bump.sh A.B.C
git flow release finish A.B.C
git push origin master develop v-A.B.C
```

**Deploy the release**
To update the target system, you do all this from the `Symfony` directory of the project:
```bash
git fetch
git checkout v-A.B.C
git submodule update --init --recursive
scripts/update.sh
```

**Post-Deployment**
* Resolve and close all remaining Jira issues
* _Release_ the Jira release
* Send release info mail to project manager including Jira release notes link
