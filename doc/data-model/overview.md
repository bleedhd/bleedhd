# Data Model Overview

![Data Model Diagram](http://www.gliffy.com/go/publish/image/6917037/L.png)

## Patient

## PatientStatus

## Assessment

## Response

**Important Note**: The `questionSlug` property on the server side is mapped to the `id` property on the client side (using the serialization configuration) due to the fact that the current Restangular version (1.4.0) does not seem to be able to have a different ID property per resource.

## Questionnaire

[See the Questionnaire data model documentation](questionnaire.md)
