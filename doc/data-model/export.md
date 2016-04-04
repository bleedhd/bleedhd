# Export

## Score Export
The primary function of the export is to allow extraction and transformation of individual data points form the stored assessment data and produce a tabular representation in the form of a CSV file.


## Export Mappings by Question Types
* yesno:
* checkboxes:
  * comma separated list of values
  * bit-vector
* radios:
* text:
* textarea:
* multiquestion: For the export, multiquestions are irrelevant. Every sub-question of a multi-question is a fully functional question of one of the other types and those rules apply.

### Meta-Answers
Meta-answers can optionally be exported as a separate column.

### Supplements
In the simpler cases, each question supplement can be exported as an additional column.

The interesting part is exporting multi-valued questions with supplements. The following options exist
1. Export each value as multiple separate columns - one for the value and one for each of the supplements
2. Export as a single list-style column with tuple values (separator character) - e.g. "value-1|supplement-value-a", "value-4|supplement-value-b"

The supplement types behave just like the previously defined question types with the following mapping:
* checkbox → yesno
* textfield → text
* radios → radios
* checkboxes → checkboxes


## Configuration
TODO


## Implementation
TODO
