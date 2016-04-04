# Security Overview

## Roles
All roles are organized in a simple hierarchy where each higher layer includes all the layers below it:
* `ROLE_READER`
* `ROLE_ASSESSOR`
* `ROLE_SUPERVISOR`
* `ROLE_ADMIN`
* `ROLE_SUPER_ADMIN`

This role hierarchy is defined in the `app/config/security.yml` configuration file. All data service methods from the REST API define their required role to be at least on the reader level.

## Role Privileges
From the naming it should be fairly obvious what the roles are supposed to be allowed to do. For completeness sake, here is a summary for each role.

`ROLE_READER`: can only read data through the API and not add, modify or delete data - the one notable exception is dumping of client log entries which only requires read permission.

`ROLE_ASSESSOR`: can basically do all the relevant things like create and edit patients, assessments, etc. - but assessors cannot _delete_ anything.

`ROLE_SUPERVISOR`: can delete their own database entities where this is allowed. "their own" here means entities they created themselves.

`ROLE_ADMIN`: admins can delete all database entities where this is conceptually allowed.

`ROLE_SUPER_ADMIN`: super admins can always do everything! (this is a Symfony built-in concept)

## HTTPS
Generally, the entire BleedHD application requires **https**. For development purposes, it is possible to allow both http and https by modifying the `app/config/parameters.yml` file and setting the `http_channel` to the empty string. This should **NEVER** be done on any non-development system **EVER**.

## OAuth
The entire API is protected through OAuth access tokens. The implementation relies on the [FOSOAuthServerBundle](https://github.com/FriendsOfSymfony/FOSOAuthServerBundle) in combination with the [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle). The FOSUserBundle provides the authentication part while FOSOAuthServerBundle is mostly used for the authorization aspect.

Since most of the application is running on the clients browser, the OAuth implementation has a bit of a twist. The client actually doesn't make the OAuth requests (like requesting / refreshing tokens), instead the server has webservices that do this for the client. The custom token service endpoints (`/user/gettoken` and `/user/refreshtoken` in the SecurityBundle) rely on the normal FOSUserBundle authentication / session mechanism. The client can fetch its token from the server if the user has already logged in (under `/user/login`) and use the access token to make API requests.

# Session / Token Handling
OAuth access tokens are configured to last 60 minutes while the refresh token and the session cookie last twice that long. The lifetime configuration can be found in `app/config/config.yml`. This means that the access token has to be refreshed before 60 minutes. Accessing the get or refresh token service automatically resets the session cookie lifetime, so if a user is continuously working on the app he will only have to log in once.

Since we don't want unattended applications being permanently logged in, there is a mechanism in the client called _activity window_. The client only refreshes the token when it is about to run out **and** there has been some _activity_ within the configured time _window_. If there was no activity within that time, the token will be allowed to expire and the user is redirected to the login page. The activity window is set to 30 minutes and any API requests to the server will count as _activity_. If there was some activity within the last 30 minutes and the access token is about to expire, it is automatically refreshed behind the scenes and the whole thing starts anew with reset timers on the new access token, refresh token and session.

# User Management
The FOSUserBundle brings some simple user management functionality to the table - the login is part of that. There are however a number of other pages that are currently not linked anywhere in the application that can be useful:
* `/user/profile`: shows the currently logged in user's name and email (profile)
* `/user/profile/edit`: allows the currently logged in user to modify his/her profile information
* `/user/resetting/request`: allows a user to reset his/her password by specifying the username or email address
* `/user/profile/change-password`: allows the currently logged in user to change his/her password
* `/user/register`: `ROLE_ADMIN` **only** - user creation form

## Creating new Users
Full control over user management can currently only be achieved through the Symfony console (requires a developer), but creating limited access users can be accomplished by any administrator (`ROLE_ADMIN`) through the web interface. For security reasons, new users cannot simply create their own accounts - otherwise anybody could do that. The following steps describe the process how this should be done:

1. Log in with an administrator account
2. Open `/user/register`
3. Enter the new user's _email_ and _username_
4. Enter an arbitrary password - it doesn't really matter since we'll reset it in a couple of seconds
5. Select the role that the new user should have - probably _Editor_
6. Create the account - you are now logged in as the new user!
7. Open `/user/resetting/request`
8. Enter the new user's email address again (it **must** match the one you entered before)
9. Submit the form - the new user will now receive an email where he/she can reset their password
