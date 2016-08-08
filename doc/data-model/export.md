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
Meta-answers can optionally be exported as a separate column or _inline_ even though this is not at all recommended since not only does it break the atomic value rule, but it also makes it impossible to differentiate the meta values from user input that just happens to look the same.

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
An export configuration is a well-defined mapping from questionnaires of **one** specific type to an export CSV representation. An export configuration file on the highest level therefore looks like this:

```yaml
name: Default
type: who
columns:
    -
        ...
```

* `name` is used in the export UI to indicate to the user what _kind_ of export it is
* `type` defines which questionnaire type the export applies to
* `columns` is a series of export colum definitions (see next section)

### Columns
Exporting a CSV column is a two step process; first the relevant value has to be extracted from the data, then the value is transformed to a specific string representation of the original value.

```yaml
# optional
label: My Label
extractor: Response
reference: gvhd-new-diagnosis.chronic.skin.distinctive
# optional
transform:
    # also technically optional (defaults to Identity)
    type: InlineSupplement
    # transform-specific configuration
```

The `label` is optional and if it is not specified, the `reference` value is used as the label. The transform element is optional as well; if none is specified, it uses the identity transform. Transforms is where most of the export magic happens and they can have configuration options specific to a transform type which is documented in detail below.

#### Null Values
Since the result is a CSV which is just a collection of _string_ values, absence of source data like null values and similar is usually represented as an empty string in the CSV.

#### Extractors

##### PatientField
The **reference** for a patient field extractor is the patient entity property name as specified in the doctrine mapping configuration. Any table value can be extracted.

##### AssessmentField
The **reference** for an assessment field extractor is the assessment entity property name as specified in the doctrine mapping configuration. Any table value can be extracted.

##### Score
For the score extractor, some details of the scoring implementation for the particular questionnaire type have to be known. The easiest way to find out which values are available under which name is to use the debug switch in the BleedHD client and analyze the assessment score of an assessment with the required type. Every value stored in the `score` section of the assessment result can be retrieved by using the property name as the **reference**. Not that _all_ questionnaires have a `total` score property that contains the overall score of the assessment.

##### Response
Extracts the response to a specific question from the assessment data by giving the question's _complete slug_ as the **reference**.

It is possible to extract the _presence_ of a single option of a multi-valued checkbox response by adding an additional slug segment with an "@" sign and the option value to the end of the question slug. For example, if the slug `my.question.slug` would extract the value array `['a', 'c', 'f']`, then the slug `my.question.slug.@c` would return a _true_ value instead of the list (or _false_ if the value 'c' was not present in the response).

##### MetaResponse
Extracts the meta-response value for a specific question from the assessment data by giving the question's _complete slug_ as the **reference**.

##### Supplement
Extracts a supplement from a question given the complete question slug combined with the (dot-separated) supplement slug as the **reference**.

It is possible to extract the supplement of a single option of a multi-valued checkbox response by adding an additional slug segment with an "@" sign and the option value before the supplement slug segment. For example, if the option `my.question.slug@option` has a supplement called 'mysupplement', then the slug `my.question.slug.@option.mysupplement` would extract that supplement (or return NULL if the option or the supplement was not present).

#### Transforms

There are some options that are available for all transforms, but their effect might vary slightly form transform to transform. Those common options are:

* `inlineMeta` - `true`, `false` or mapping transform configuraton; defaults to `false`: This option is only meaningful for values extracted from assessment responses (Response, ResponseMeta, Supplement). If set, this will cause the response meta-answer to show up in the export instead of the usual empty string value if the response does not have a value for the extracted property. If necessary, the raw meta-answer value can be transformed with a mapping transform by simply treating the `inlineMeta` option like a mapping transform configuration (the type does not need to be specified).
* `listItemSeparator` - any string; defaults to ',': This separator is used for multi-valued sources to separate the individual items.
* `listEmptyValue` - any string; defaults to '': This value is used for multi-valued sources if the value is an empty list. This can be useful to differentiate an empty list from a missing value.
* `prefix` - any string; defaults to '': This string is prepended to the result value.
* `suffix` - any string; defaults to '': This string is appended to the result value.

##### Identity
As the name suggests, the identity transform doesn't do anything to the source value. It is the default transform and can be useful when used explicitly in situations where the common transform options are sufficient to achieve the desired output.

##### DateTimeFormat
A transform that can format PHP DateTime objects to strings. See [the PHP date documentation for instructions on how to build a format string](http://php.net/manual/en/function.date.php).

* `format` - PHP date time format string; defaults to 'Y-m-d H:i:s' (ISO date and time): This format string is used to convert the source DateTime object to a string.

##### Mapping
The mapping transform provides a mechanism for a configurable mapping of source values. For each transformed value, the mapping predicates are tested in the order in which they are configured and the first matching predicate is used to generate the result - this is basically a generalized switch / case statement.

* `map`: array of mapping predicates; defaults to empty array: A sequence of mapping predicate definitions that are used to _map_ the source value to _some other value_.

```yaml
map:
    -
        # source match predicate
        source: 'source value'
        value: 'mapped value'
    -
        # regex replace predicate
        regex: /^(\w*)/
        value: 'first word is $1'
    -
        # default mapping predicate
        default: 42
```

* **Source match predicates** test the source value for equality to the `source` property and if they match, the `value` property is used as the output.
* **Regex replace predicates** use the PHP [preg_match](http://php.net/manual/en/function.preg-match.php) function to check if the source value matches the given expression and then the [preg_replace](http://php.net/manual/en/function.preg-replace.php) function to generate the output.

##### BitVector
This transform is only meaningful for multi-valued sources, in particular answers for checkbox-type questions where the result is a list of the selected options. This transform will go through the options of the question that is associated with the extractor's **reference** property and generate a list of _true_ and/or _false_ values. This list is then collapsed into a single value using the usual configuration values `listItemSeparator` and `listEmptyValue` with the minor difference that for the BitVector transform the default for the `listItemSeparator` is the empty string.

* `trueValue` - string; defaults to '1': defines the value used for options that have been selected.
* `falseValue` - string; defaults to '0': defines the value used for options that have _not_ been selected.

**Note**: Be aware that many tools that read CSV files like Microsoft Office and LibreOffice try to _format_ the values they find in a CSV file which can have some undesired side-effects like getting rid of leading zeros. This would of course mean that a bit vector '00011' would be displayed as '11' which _might_ cause issues when trying to analyze the data. You can use `prefix` and `suffix` configuration options to work around this issue.

##### InlineSupplement
This transform is only meaningful for multi-valued sources where each value in turn has one or more supplements. These are the most complex types of questionnaire responses and as such require some special consideration. With the identity transform, a multi-valued source produces an intermediate result of an array of those source values which is then flattened using the default transform configuration options. The inline supplement transform allows each value to be _enriched_ by adding one or more supplements to it. The result is basically a list of tuples of the form `['value', 'supplement 1', 'supplement 2', ...]`. This list of lists is then collapsed into a single value by separating the tuple values with a separate string and then applying the usual list type options.

* `supplements` - array of supplement mapping transform configurations; defaults to empty array: Each supplements array element is its own mapping transform configuration, but instead of the `type` property, it has a `slug` property that indicates which supplement should be inlined. The supplement value is transformed using the mapping configuration and then added to the tuple.

### Directives
To reduce duplication of configuration among multiple export configuration files, the application supports _directives_ to facilitate modularization of configuration files. A directive is a YAML construct that can (theoretically) appear anywhere in an export configuration and modifies the YAML structure in some way.

```yaml
__directive:
    # directive operation
    op: insert
    # directive source
    source: ../path/to/some/file.yaml
```

#### Insert Directive
The insert directive inserts one or more array elements in the place of the directive's host element. It is assumed that the insert directive is the single child of an array element and the source is either a single YAML object or a sequence.

If the referenced source yaml is a sequential array, then the entire array is used to replace the directive's **host** element. If the source is an object, then the **hosting** array element will contain the inserted object.

### Examples

#### Minimal export configuration
```yaml
name: My Export Configuration
type: who
columns:
    -
        extractor: Response
        reference: who.mouth-and-nose.petechiae
```

#### Response with Supplement as Separate Column
```yaml
    -
        extractor: Response
        reference: who.mouth-and-nose.petechiae
    -
        extractor: Supplement
        reference: who.mouth-and-nose.petechiae.isnew
```

#### Common Transform Options in Action
```yaml
    -
        extractor: Response
        reference: some.question
        transform:
            inlineMeta:
                prefix: '((('
                suffix: ')))'
                map:
                    -
                        regex: /^n(.*)$/
                        value: $1
            listItemSeparator: '&'
            listEmptyValue: ***EMPTY***
            prefix: '<<<'
            suffix: '>>>'
```

* unanswered question (meta-answer = 'nya'): `(((ya)))`
* question not assessed (meta-answer = 'nass'): `(((ass)))`
* answer is a single value 'test': `<<<test>>>`
* answer is a multi-valued source with values 'one', 'two': `<<<one&two>>>`
* answer is a multi-valued source with an empty list: `***EMPTY***`

#### BitVector
```yaml
    -
        label: Binary
        extractor: Response
        reference: gvhd-new-diagnosis.chronic.skin.diagnostic
        transform:
            type: BitVector
    -
        label: Logic Symbols
        extractor: Response
        reference: gvhd-new-diagnosis.chronic.skin.diagnostic
        transform:
            type: BitVector
            trueValue: '⊤'
            falseValue: '⊥'
```

* With selected options 'poikiloderma', 'morphea-like-features'
  * Binary: `10010`
  * Logic Symbols: `⊤⊥⊥⊤⊥`
