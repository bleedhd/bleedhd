# Prerequisites

All of this documentation is fairly useless without some advanced understanding of the primary technologies involved:
* Symfony2 / [Composer](https://getcomposer.org/)
* AngularJS (v1.3.5)
* REST
* [Node.js / NPM](https://github.com/creationix/nvm)

It assumes familiarity with all of these topics and uses their terminology where appropriate.

#### Composer
Obviously, you need [Composer](https://getcomposer.org/) and it needs to be in your PATH.

The `proc_open` function has to be available for the PHP command line INI. Depending on the environment (development, 3rd party hosting, etc.) this might need some tweaking. For linux systems, check the `/etc/php5/cli/php.ini` and comment out the line that defines the `disable_functions`.

#### Node.js
The Node.js and NPM executables (`node` and `npm`) need to be in your PATH in order for the custom _post-install-cmd_ in the _composer.json_ file to work. I recommend using [NVM](https://github.com/creationix/nvm) to manage/install Node.js.

# Development Instructions

## Bootstrapping the Project

See the readme file in the repository root.

### Create User Accounts
Create a user for yourself and a dedicated editor(-only) role
```bash
# interactive with super admin role
bin/console fos:user:create --super-admin
# non-interactive with explicit role
bin/console fos:user:create editor editor@example.com editor
bin/console fos:user:promote editor ROLE_EDITOR
```


# General Symfony Hints

Clearing the cache
```bash
bin/console cache:clear [--env=prod]
```
Remember to add the `--env=prod` when doing things on the production system, otherwise you will only delete the development cache which has no effect.



# Terminology

Questionnaire
: a definition of a structured set of questions that, when answered, make up an *Assessment*.

Assessment
: the complete set of answers to a given questionnaire.



# Data Model Changes with Doctrine

To update the entity PHP classes from the YAML mappings, do
```bash
bin/console doctrine:generate:entities --no-backup GetunikBleedHdAssessmentDataBundle
```

Create a migration script
```bash
bin/console doctrine:migrations:diff
```

To execute pending migration and check the current migration status, do
```bash
bin/console doctrine:migrations:migrate
# check the status after successful migration
bin/console doctrine:migrations:status
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

## Patients
```
{
	"is_active": true,
	"firstname": "Patient One",
	"lastname": "One Patient",
	"birthdate": "1980-01-01T00:00:00Z",
	"sex": "m",
	"patient_number": "12345",
	"upn": 12345,
	"diagnosis": "diagnosis-1",
	"diagnosis_date": "2014-12-01T00:00:00Z",
	"patient_blood_type": "A+",
	"donor_blood_type": "A+"
}
```

### Create a Patient
```
curl -i -X POST -H "Content-Type: application/json" -H "Authorization: Bearer _access_token_" -d '{"is_active":true,"firstname":"Patient One","lastname":"One Patient","birthdate":"1980-01-01T00:00:00Z","sex":"m","patient_number":"12345","upn":12345,"diagnosis":"diagnosis-1","diagnosis_date":"2014-12-01T00:00:00Z","patient_blood_type":"A+","donor_blood_type":"A+"}' "http://bleed-hd.localhost/api/patients"
```

### Update a Patient
```
curl -i -X PUT -H "Content-Type: application/json" -H "Authorization: Bearer _access_token_" -d '{"id":1,"is_active":true,"firstname":"Patient One Modified","lastname":"One Patient Modified","birthdate":"1980-01-02T00:00:00Z","sex":"m","patient_number":"12345","upn":12345,"diagnosis":"diagnosis-1-1","diagnosis_date":"2014-12-02T00:00:00Z","patient_blood_type":"A+","donor_blood_type":"A+"}' "http://bleed-hd.localhost/api/patients/1"
```

### Get a Patient
```
curl -i -X GET -H "Content-Type: application/json" -H "Authorization: Bearer _access_token_" "http://bleed-hd.localhost/api/patients/1"
```

## PatientStatus
```
{
	"patient_id": 1,
	"transplant_date": "2012-08-08T00:00:00Z",
	"transplant_type": "type-1",
	"transplant_source": "source-1"
}
```

### Create a PatientStatus
```
curl -i -X POST -H "Content-Type: application/json" -H "Authorization: Bearer _access_token_" -d '{"patient_id":1,"transplant_date":"2012-08-08T00:00:00Z","transplant_type":"type-1","transplant_source":"source-1"}' "http://bleed-hd.localhost/api/patients/1/statuses"
```

### Update a PatientStatus
```
curl -i -X PUT -H "Content-Type: application/json" -H "Authorization: Bearer _access_token_" -d '{"id":1,"patient_id":1,"transplant_date":"2012-08-08T00:00:00Z","transplant_type":"type-1-1","transplant_source":"source-1-1"}' "http://bleed-hd.localhost/api/patients/1/statuses/1"
```

### Get a PatientStatus
```
curl -i -X GET -H "Content-Type: application/json" -H "Authorization: Bearer _access_token_" "http://bleed-hd.localhost/api/patients/1/statuses/1"
```

### Get all PatientStatuses for a Patient
```
curl -i -X GET -H "Content-Type: application/json" -H "Authorization: Bearer _access_token_" "http://bleed-hd.localhost/api/patients/1/statuses"
```
