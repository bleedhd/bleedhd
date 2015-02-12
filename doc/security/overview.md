# Security Overview

## Roles
All roles are organized in a simple hierarchy where each higher layer includes all the layers below it:
* `ROLE_READER`
* `ROLE_EDITOR`
* `ROLE_ADMIN`
* `ROLE_SUPER_ADMIN`

This role hierarchy is defined in the `app/config/security.yml` configuration file. All data service methods from the REST API define their required role to be at least on the reader level.

## Role Privileges
From the naming it should be fairly obvious what the roles are supposed to be allowed to do. For completeness sake, here is a summary for each role.

`ROLE_READER`: can only read data through the API and not add, modify or delete data - the one notable exception is dumping of client log entries which only requires read permission.

`ROLE_EDITOR`: can basically do all the relevant things like create and edit patients, assessments, etc. - but editors cannot _delete_ anything.

`ROLE_ADMIN`: admins can delete database entities where this is conceptually allowed.

`ROLE_SUPER_ADMIN`: super admins can always do everything!

## HTTPS
Generally, the entire BleedHD application requires **https**. For development purposes, it is possible to allow both http and https by modifying the `app/config/parameters.yml` file and setting the `http_channel` to the empty string. This should **NEVER** be done on any non-development system **EVER**.

## OAuth
The entire API is protected through OAuth access tokens. The implementation relies on the [FOSOAuthServerBundle](https://github.com/FriendsOfSymfony/FOSOAuthServerBundle) in combination with the [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle). The FOSUserBundle provides the authentication part while FOSOAuthServerBundle is mostly used for the authorization aspect.

Since most of the application is running on the clients browser, the OAuth implementation has a bit of a twist. The client actually doesn't make the OAuth requests (like requesting / refreshing tokens), instead the server has webservices that do this for the client. The custom token service endpoints (`/user/gettoken` and `/user/refreshtoken` in the SecurityBundle) rely on the normal FOSUserBundle authentication / session mechanism. The client can fetch its token from the server if the user has already logged in (under `/user/login`) and use the access token to make API requests.

# Session / Token Handling
OAuth access tokens are configured to last 60 minutes while the refresh token and the session cookie last twice that long. The lifetime configuration can be found in `app/config/config.yml`. This means that the access token has to be refreshed before 60 minutes. Accessing the get or refresh token service automatically resets the session cookie lifetime, so if a user is continuously working on the app he will only have to log in once.

Since we don't want unattended applications being permanently logged in, there is a mechanism in the client called _activity window_. The client only refreshes the token when it is about to run out **and** there has been some _activity_ within the configured time _window_. If there was no activity within that time, the token will be allowed to expire and the user is redirected to the login page. The activity window is set to 30 minutes and any API requests to the server will count as _activity_. If there was some activity within the last 30 minutes and the access token is about to expire, it is automatically refreshed behind the scenes and the whole thing starts anew with reset timers on the new access token, refresh token and session.


