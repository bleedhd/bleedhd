# Question Structure

```yaml
slug: question-slug
type: yesno
meta_answers: [...]
question:
  title: This is the question text
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


# Meta Answers

* `nya`: Not yet answered. The question has been skipped and should be revisited at a later stage.
* `nass`: Not assessed. The question was intentionally skipped and will be excluded from any score calculation.
* `napp`: Not applicable. The question does not apply to the current context and will be ignored.
* `ns`: No symptom. ???

```json
response = {
	"question.slug": {
		"value": null,
		"meta": "nya",
	}
}
```


# Question Types

General question types

## `yesno`

Simple yes or no question using radio buttons. Only one of the two options may be chosen

```json
response = {
	"bleedwho.oral": {
		"value": true,
	}
}
```

## `checkboxes`

```json
response = {
	"bsms.bleeding-site": {
		"value": [
			{ "value": "genitourinary" },
			{ "value": "cardiopulmonary" }
		],
	},
}
```

## `radios`

```json
response = {
	"bsms.grade": {
		"value": 2,
	}
}
```

## `multiquestion`

```json
response = {
	"gvhd-initial.other-indicators": {
		"gvhd-initial.other-indicators.ascites": {
			"value": 1,
		},
		"gvhd-initial.other-indicators.pericardial-effusion": {
			"value": 2,
		},
		"gvhd-initial.other-indicators.eosinophilia": {
			"value": "< 500 µl",
		},
		"gvhd-initial.other-indicators.others": {
			"value": "",
		},
	}
}
```


# Question Supplements

## `isnew`

```json
response = {
	"bleedwho.oral": {
		"value": true,
		"supplements": {
			"isnew": true,
		},
	},
}
```

## `textfield`

```json
response = {
	"bleedwho.intervention.methods": {
		"value": [
			{ "value": "surgery" },
			{ "value": "other", "supplements": { "intervention": "medical stuff" } },
		],
	},
}
```

## `radios`
```json
response = {
	"gvhd-initial.skin.distinctive": {
		"value": true,
		"supplements": {
			"confirmation": "positive",
		},
	},
}
```

## `checkboxes`
```json
response = {
	"gvhd-initial.skin.features": {
		"value": 3,
		"supplements": {
			"symptoms": ["deep", "hidebound", "impaired mobility", "ulceration"],
		},
	},
}
```
