# Question Structure

```yaml
slug: question-slug
type: yesno
title: "This is the question title"
meta_answers: [...]
intro:
  question: "This is the question text"
  description: "This is the question description with some <strong>HTML</strong> markup"
options:
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

General question types

## `yesno`

Simple yes or no question using radio buttons. Only one of the two options may be chosen

```json
{
	"data": { "value": true },
	"meta": null,
}
```

## `checkboxes`

```json
{
	"data": [
		{ "value": "genitourinary" },
		{ "value": "cardiopulmonary" }
	],
	"meta": null,
},
```

## `radios`

```json
{
	"data": {
		"value": 2,
	},
	"meta": null,
}
```

## `multiquestion`

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
