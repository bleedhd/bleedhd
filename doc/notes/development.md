# Dev Notes

## Bootstrapping the Project

### Get the Database Up
```
php app/console doctrine:migrations:migrate
# check the status after successful migration
php app/console doctrine:migrations:status
```

### Create User Accounts
Create a user for yourself and a dedicated editor(-only) role
```
# interactive with super admin role
php app/console fos:user:create --super-admin
# non-interactive with explicit role
php app/console fos:user:create editor editor@example.com editor
php app/console fos:user:promote editor ROLE_EDITOR
```

### Create an OAuth Client
```
php app/console getu:oauth-server:create-client BleedHD --grant-type="password" --grant-type="refresh_token"
```

# Testing Things

## Accessing the REST API

First you have to get yourself an OAuth token. To do this, log in via `/user/login` and then get your token by visiting `/user/gettoken`. The last call will result in a JSON response containing your OAuth token information, e.g:

```
{
  "access_token":"OTc5ZWMyODllOGEyMjQxZWE0NDhhMmEyMjgzOWExZWM1NDhlODk5NzYwMWEwMzI4NzhkODkzZjY5MDgwNTFkOQ",
  "expires_in":3600,
  "token_type":"bearer",
  "scope":null,
  "refresh_token":"OTMzMTcyYWM1ZGZjYTI1M2ZkMTQ2NGExMjlmMDM0YTA4NTU0YmE3MDhhNDllZDRhYjlmNWQ2OGQ2ZDY5MDI0Yg",
  "expires_at":"2014-11-27T14:46:08+0100"
}
```

Now you can use CURL to access the REST API like so:
```
curl -X GET -H "Authorization: Bearer _access_token_" "http://bleed-hd.localhost/api/..."
```