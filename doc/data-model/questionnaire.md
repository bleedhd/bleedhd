
# Overall Structure

```yaml
title: Demo
quick_links:
  -
    label: Test
    screen: screen_1
    question: question-yes-no-demo2
  -
    label: Multi-Valued Supplements
    screen: screen_7
    question: supplements-demo1
meta_answers:
  nya: not yet answered
  nass: not assessed
  napp: not applicable
  ns: no symptom
chapters:
  -
    sections:
      -
        screens:
          -
            questions:
              - ...
              - ...
```

## Quick Links
Quick links can be arbitrarily defined in the `quick_links` section of the questionnaire. The `label` and `screen` properties are required, the `question` is optional and if present, the quick link will cause the loaded screen to scroll to that particular question.

## Meta Answers
The list of available meta answers - in particular their labels - must be defined for each questionnaire. The keys are well-defined, but the labels may differ from questionnaire to questionnaire.

## The Hierarchy
Note: even though most of it is currently not used, the hierarchy stayed in place because it will make future reorganizations and possibly extensions a bit easier.

The questions are organized in a hierarchy of _chapters_, _sections_ and _screens_. Each level in the hierarchy can define a `slug` property that will be used to construct the full question slug. The items in the `screens` section **must** have a slug since this slug is used as part of the screen URL. Since this can cause problems when moving questions in an assessment or with duplicate screen (short-)slugs, screens **may** define a `url_slug` property that will be used as the screen's slug. The questionnaire itself may also define a `slug` property; if it is not present, the internal assessment name (file name) will be used.

Given a questionnaire with the following structure,
```yaml
slug: sample
chapters:
  -
    slug: ch01
    sections:
      -
        screens:
          -
            slug: first
            url_slug: firsts-url-segment
            questions:
              -
                slug: question-one
                ...
              -
                slug: question-two
                ...
      -
        slug: sec01
        screens:
          -
            slug: second
            questions:
              -
                slug: question-three
                ...
```

the resulting full question slugs will be
* `sample.ch01.first.question-one`
* `sample.ch01.first.question-two`
* `sample.ch01.sec01.second.question-three`

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

## Ownership
The different aspects of the respones / result object have different _owners_ in the client UI implementation. The owner is in charge of updating that particular piece of the response and the ownership is linked to the hierarchical view nature of the implementation. The following image illustrates who owns what:

![Response Ownership](http://www.gliffy.com/go/publish/image/7454453/L.png)


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
score:
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
* **score**: The scoring configuration for this question. The specifics of the scoring configuration depend on the scoring
  implementation used and is defined in the [scoring documentation](scoring-config.md). Depending on the implementation, questions without a
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
```yaml
slug: radios-demo
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

### Variants
* `horizontal`: Arranges the radios horizontally instead of the vertical default. Note that in horizontal mode, option supplements do not work (there is no place to put them).

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

The text question type presents the user with a simple single line text input field. It can also be used
for restricted input like numeric values by configuring the `pattern` accordingly

### Configuration

```yaml
slug: text-demo
type: text
title: "Hobbies"
meta_answers: ['nya']
intro:
  question: "Which of your hobbies consumes the most time?"
  description: "Sleeping doesn't count!" # optional
placeholder: "e.g. Skiing" # optional
pattern: "/^($|[1-9]$|10$)/"
supplements:
  ...
```

**placeholder**: The placeholder provides an input hint inside the text field while it is empty.

**pattern**: This property limits the allowed input values by providing a regular expression that must match
at any time - any character entered that would result in a string that would not match the expression will be
blocked. The value can either be a JavaScript style RegExp delimited by slashes or one of the following
predefined shorthands.
* `integer`: integer values (digits only)
* `decimal`: any numeric value that may include a decimal point (`.` character)

### Result Example

```json
{
	"data": {
		"value": "Some random text",
	},
	"meta": null,
}
```


## "textarea"

The textarea type allows long and descriptive multiline responses.

### Configuration

```yaml
slug: textarea-demo
type: textarea
title: "Essay"
meta_answers: ['nya']
intro:
  question: "Tell me something about yourself"
  description: "Don't be shy..." # optional
label: "About you" # optional
placeholder: "I am ..."
rows: 20
cols: 100
supplements:
  ...
```

**placeholder**: The placeholder provides an input hint inside the text field while it is empty.

**rows**: The number of rows that defines the height of the text area. It does not limit the number of lines
the user can actually enter. See [http://www.w3schools.com/tags/att_textarea_rows.asp](http://www.w3schools.com/tags/att_textarea_rows.asp)

**cols**: The number of cols that defines the width of the text area. This has usually no effect since the styling of the with to 100% takes
precedence. See [http://www.w3schools.com/tags/att_textarea_cols.asp](http://www.w3schools.com/tags/att_textarea_cols.asp)

### Result Example

```json
{
	"data": {
		"value": "Some random text\nwith line breaks",
	},
	"meta": null,
}
```

## "multiquestion"

Multiquestions are internally represented as individual questions - they are just rendered together in a single question container. As
such, every question in a multi-question gets its own _response_ and the specifics of the _result_ are defined by the inner question
type. The multiquestion settings only affect the question container and not the actual questions.

### Configuration

```yaml
slug: multi-demo
type: multi
title: "These are multiple questions"
meta_answers: ['nya', 'nass']
intro:
  question: "What's the deal with the questions below?"
  description: "Description" # optional
questions:
  ...
```

The `questions` property is simply an array of questions presented inside the container.


# Question Supplements
The supplement configuration supports an additional `default` property - questions don't need that because they have meta-answers, but supplements in some cases must provide a meaningful default. The value of the default property obviously must be a value supported by the supplement type / options.

```yaml
supplements:
  -
    slug: some-supplement
    type: radios
    title: "This is a title"
    intro:
      question: "Any questions?"
    default: 42
    options:
      -
        label: "Forty-Two"
        value: 42
      -
        label: "The number of the beast"
        value: 666
```

## "checkbox"

```json
{
	"value": true,
	"supplements": {
		"isnew": true,
	},
}
```

## "textfield"

```json
{
	"value": "other",
	"supplements": {
		"intervention": "medical stuff"
	},
}
```

## "radios"

```json
{
	"value": true,
	"supplements": {
		"confirmation": 4,
	},
}
```

## "checkboxes"
```json
{
	"value": 3,
	"supplements": {
		"symptoms": ["deep", "hidebound", "impaired mobility", "ulceration"],
	},
}
```
