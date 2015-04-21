# Data Model Overview

![Data Model Diagram](http://www.gliffy.com/go/publish/image/6917037/L.png)

## Patient
<dl>
	<dt>isActive</dt>
	<dd>Indicates the patient's status - <code>true</code> indicates an active patient, <code>false</code> an inactive one</dd>
	<dt>firstname</dt>
	<dd>Patient's first name</dd>
	<dt>lastname</dt>
	<dd>Patient's last name</dd>
	<dt>birthdate</dt>
	<dd>Patient's birthdate (date only)</dd>
	<dt>sex</dt>
	<dd>One character patient gender indication - <code>m</code> indicates male, <code>f</code> indicates female</dd>
	<dt>patientNumber</dt>
	<dd>A patient identifier</dd>
	<dt>upn</dt>
	<dd>Another patient identifier (unique patient number)</dd>
	<dt>diagnosis</dt>
	<dd>The patient's diagnosis</dd>
	<dt>diagnosisDate</dt>
	<dd>The date of the initial diagnosis</dd>
	<dt>remarks</dt>
	<dd>General purpose patient remarks field</dd>
</dl>

## PatientStatus
<dl>
	<dt>patientId</dt>
	<dd>The patient ID of the patient the status belongs to</dd>
	<dt>transplantDate</dt>
	<dd>The date of the transplant</dd>
	<dt>transplantType</dt>
	<dd>The type of the transplant, selected from a list of possible types</dd>
	<dt>transplantSource</dt>
	<dd>The source of the transplant, selected from a list of possible sources</dd>
	<dt>transplantCustom</dt>
	<dd>Extension of the transplant source field with custom user input</dd>
</dl>

## Assessment
<dl>
	<dt>patientId</dt>
	<dd>The ID of the patient this assessment belongs to</dd>
	<dt>questionnaire</dt>
	<dd>The identifier of the questionnaire this assessment is based on</dd>
	<dt>questionnaireVersion</dt>
	<dd>The version of the questionnaire this assessment was last updated with (happens on every response update)</dd>
	<dt>startDate</dt>
	<dd>Start date and time of the assessment</dd>
	<dt>platelets</dt>
	<dd>Platelet count at the time of the assessment</dd>
	<dt>remarks</dt>
	<dd>Assessment remarks</dd>
	<dt>result</dt>
	<dd>JSON object representing the assessment's calculated result</dd>
	<dt>progress</dt>
	<dd>Indicates the assessment's status as one of <code>completed</code> or <code>tentative</code></dd>
</dl>

The `createdDate` and `createdBy` fields of the assessment are automatically managed by the application just like the `lastUpdatedDate` and `lastUpdatedBy` fields and should not be set explicitly.

## Response
<dl>
	<dt>assessmentId</dt>
	<dd>The assessment ID this response belongs to</dd>
	<dt>questionSlug</dt>
	<dd>The fully qualified question slug for the question this response belongs to</dd>
	<dt>result</dt>
	<dd>The response result JSON object</dd>
</dl>

**Important Note**: The `questionSlug` property on the server side is mapped to the `id` property on the client side (using the serialization configuration) due to the fact that the current Restangular version (1.4.0) does not seem to be able to have a different ID property per resource.

## Questionnaire

[See the Questionnaire data model documentation](questionnaire.md)
