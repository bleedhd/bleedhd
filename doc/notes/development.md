# Terminology

Questionnaire
: a definition of a structured set of questions that, when answered, make up an *Assessment*.

Assessment
: the complete set of answers to a given questionnaire.

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

# Data Model Changes with Doctrine

To update the entity PHP classes from the YAML mappings, do
```
php app/console doctrine:generate:entities GetunikBleedHdAssessmentDataBundle
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
