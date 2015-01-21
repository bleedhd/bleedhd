# Question Structure

```yaml
slug: question-slug
type: yesno
title: "This is the question title"
meta_answers: [...]
variation: variation-name
style: style-classes
intro:
  question: "This is the question text"
  description: "This is the question description with some <strong>HTML</strong> markup"
options:
  ...
scoring:
  ...
export:
  ...
```

## Text Components

### Header
**Category**
: informal question category label

**???** (optional)
: TBD

### Main
**Question Text** (optional)
: the actual question text

**Question Description** (optional)
: detailed description of the question

# Response / Result

A _response_ is the _result_ for a specific question. In the data model, the _Response_ table associates the _result_ field with a question for a specific assessment. The _result_ field is a JSON field since the result structure strongly depends on the question type. A result can have one of the three forms:

* a meta answer (see below)
* a single _value_ object
* an array of _value_ objects

## Result
To keep the meta answers nice and cleanly separated from actual results, the _result_ object has the following form:

```json
{
	"data": null,
	"meta": null,
}
```

If the _result_ is a `meta` answer, then the `data` property always has to be null and vice versa.

## Data
The `data` object is then either a single _value_ object or an array of _value_ objects.

```json
{
	"value": "the value"
},
```

```json
[
	{ "value": "the value" },
	{ "value": "another value" },
],
```

## Value
Each value object **must** have a _value_ property and it _may_ additionally have a _supplements_ property with additional information specific to the answer value. If present, the _supplements_ property has to be an object keyed on supplement slugs. Answers may have any number of supplements, but each of them must have a unique key in the scope of the **question**.

```json
{
	"value": "the value",
	"supplements": {
		"supplement-slug": "supplement-value",
		"supplement-slug2": "supplement-value2",
	},
}
```

Note that _supplement_ values **must** be primitive types or arrays of primitive types - objects are not allowed.


# Meta Answers

* `nya`: Not yet answered. The question has been skipped and should be revisited at a later stage.
* `nass`: Not assessed. The question was intentionally skipped and will be excluded from any score calculation.
* `napp`: Not applicable. The question does not apply to the current context and will be ignored.

```json
{
	"data": null,
	"meta": "nya",
}
```

# Question Types

This section describes, the configuration and behavior of all question types. All question types share a common base in terms
of functionality and configuration

```yaml
slug: question-slug
type: yesno
title: "This is the question title"
meta_answers: [...]
variation: variation-name
style: style-classes
...
scoring:
  ...
export:
  ...
```

* **slug**: Every question absolutely **MUST** have a slug that is unique within it's slug scope hierarchy. This means that it
  has to be unique in the scope defined by its closest parent that defines a slug. Violating this requirement will inevitably
  break stuff.
* **type**: This defines the question type. This must be one of the supported question types listed below.
* **title**: ???
* **meta_answers**: Array of meta-answers (defined in the `meta_answers` section in the root of the assessment YAML) that are
  available in the question container's footer.
* **variation**: The name of a template variation for this question type. Each question type may define any number of variations
  that are functionally equivalent but render different markup (e.g. vertical vs. horizontal radio button list).
* **style**: Style CSS classes string (space separated just like in a class attribute). This style string, if present, will be
  added to the question root element (not the container).
* **scoring**: The scoring configuration for this question. The specifics of the scoring configuration depend on the scoring
  implementation used and is defined in the scoring documentation. Depending on the implementation, questions without a
  scoring configuration may not be included in the score.
* **export**: The export configuration for this question. ???

Links to the specific question types:

* [yesno](#markdown-header-yesno)
* [checkboxes](#markdown-header-checkboxes)
* [radios](#markdown-header-radios)
* [text](#markdown-header-text)
* [textarea](#markdown-header-textarea)
* [multiquestion](#markdown-header-multiquestion)


## "yesno"

Simple yes or no question using radio buttons. Only one of the two options may be chosen.

### Configuration

The full configuration looks like this:
```yaml
slug: question-yes-no-demo
type: yesno
title: "Demo Question 1 (Yes/No)"
meta_answers: ['nya', 'nass']
intro:
  question: "Do you agree or not?"
  description: "Some <strong>HTML</strong> markup" # optional
options: # optional
  yes:
    label: "Jupp"
    value: "heck-yes"
    supplements: # optional
      ...
  no:
    label: "Nope"
    value: "heck-no"
    supplements: # optional
      ...
```

If any parts of the options configuration is left out, it will be completed with the default yesno options which are
```yaml
options: # optional
  yes:
    label: "Yes"
    value: true
  no:
    label: "No"
    value: false
```

### Result Example
```json
{
	"data": { "value": true },
	"meta": null,
}
```


## "checkboxes"

A list of multiple-choice items presented as checkboxes. This type of question has a multi valued answer - its result is
not _a_ value, but an array of value objects, each of which can have its own supplements. Checkbox questions can be very
tricky to deal with when processing their results. They should be used sparingly.

The implementations supports a _reset_ feature that adds a radio button in addition to the checkboxes which, when activated,
unchecks all checkboxes. It can be enabled by adding the `option_reset` configuration.

### Configuration

The full configuration looks like this:
```yaml
slug: checkboxes-demo
type: checkboxes
title: "Make your choices!"
meta_answers: ['nya']
intro:
  question: "Make your choices!"
  description: "Pick the things you like" # optional
option_reset: # optional
  label: "Nothing at all you fool!"
options:
  -
    label: "Star Wars"
    value: star-wars
    supplements: # optional
      ...
  -
    ...
```

Each option except for the _reset_ which isn't an actual option can define its own list of supplements.

### Result Example

```json
{
	"data": [
		{ "value": "genitourinary" },
		{ "value": "cardiopulmonary" }
	],
	"meta": null,
},
```


## "radios"

A simple choice between multiple items. Only one of the options can be selected at any given time.

### Configuration

The full configuration looks like this:
```yaml
slug: radios-demo1
type: radios
title: "Multiple-Choice"
meta_answers: ['nya', 'nass']
intro:
  question: "What is <em>the</em> answer?"
  description: "Please tell me" # optional
options:
  -
    label: "There is no answer"
    value: nope
    supplements: # optional
      ...
  -
    ...
```

Each option can define its own supplements list.

### Result Example

```json
{
	"data": {
		"value": 2,
	},
	"meta": null,
}
```

## "text"

## "textarea"

## "multiquestion"

Multiquestions are internally represented as individual questions - they are just rendered together in a single question container. As such, every question in a multi-question gets its own _response_ and the specifics of the _result_ are defined by the inner question type.


# Question Supplements

## `checkbox`

```json
{
	"value": true,
	"supplements": {
		"isnew": true,
	},
}
```

## `textfield`

```json
{
	"value": "other",
	"supplements": {
		"intervention": "medical stuff"
	},
}
```

## `radios`

```json
{
	"value": true,
	"supplements": {
		"confirmation": 4,
	},
}
```

## `checkboxes`
```json
{
	"value": 3,
	"supplements": {
		"symptoms": ["deep", "hidebound", "impaired mobility", "ulceration"],
	},
}
```
