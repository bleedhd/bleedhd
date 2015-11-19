# Scoring Configuration
The scoring configuration can be present on the level of questions and/or on the level of options. The option level score configuration completely replaces the question level configuration if present. The main difference being, that if a question is answered with a specific option, that option's score configuration will be used in the score calculation while options without an explicit score calculation and question types without options will use the question level score configuration.

The one (/two) exception to this rule is the `yesno` question and `checkbox` supplement types, where for semantic consistency, the question level option is _only_ used for the `yes` option. If the `no` option should also provide a scoring configuration, it is recommended to configure both (yes and no) options explicitly.

# Questionnaire Scoring

## BSMS
The current implementation of the BSMS questionnaire makes scoring quite trivial. There is only one score relevant question and it defines the score in two _parts_:
* `type` - The primary _type_ (concept by getunik) of the bleeding; can be 0, 1 or 2.
* `subtype` - The _subtype_ (concept by getunik) indicating a more fine-grained bleeding severity; can be 'a', 'b' or 'c'.

```yaml
score:
  type: 1
  subtype: "a"
```

The score calculator expects at most one answer with such a score configuration and the total score (BSMS bleeding grade) is simply the concatenation of type and subtype.

## WHO
According to the WHO questionnaire scoring rules, every answer can define a grade that is an integer value greater or equal to 0. If an answer with an associated grade is given, it is aggregated with all previous grades using the `max` function. Simply put, answer with the highest grade defines the total score

```yaml
score:
  grade: 1
```

## GVHD Features
This is another trivial questionnaire in terms of scoring. There is one question and it establishes the presence or absence of GVHD features. The calculator expects at most one answer with a `present` property which may be either `true` or `false`

```yaml
score:
  present: true
```

## GVHD New Diagnosis
Most of the new diagnosis scoring boils down to counting. Counting the number of unanswerd questions that are relevant for chronic and acute diagnosis, counting of diagnostic signs, counting of distinctive signs and counting of confirmed distinctive signs. The rest is a simple two stage aggregation of the counts that first boils everything down to an acute and a chronic score and then computes the total score out of those two. The following samples describe how the individual counts are controlled through the scoring configuration:


#### Diagnostic & Distinctive GVHD Signs/Symptoms
```yaml
score:
  type: diagnostic | distinctive | distinctiveDependent
```
The presence of the type attribute in a question's scoring configuration implies that

1. The question is relevant for the chronic score and if left unanswered will count as a _missing chronic_ question
2. If there is an answer associated with the score, it counts the presence of a diagnostic/distinctive/distinctiveDependent sign

#### Confirmation Supplements
```yaml
score:
  status: pending | positive
```
This scoring configuration is only valid on questions for `distinctive` signs. An answer with the `pending` status indicates that the confirmation is still pending while `positive` implies a positive test result and counts as a confirmed distinctive sign (a negative confirmation is implicitly everything else).

#### Distinctive Dependent Symptoms
Like the `diagnostic` signs, the `distinctiveDependent` signs do not have explicit confirmation supplements. Instead distinctive dependent symptoms can only count towards the chronic GVHD scoring if there is at least one other _normal_ distinctive sign that is pending. This scoring mode is currently only used in two of the lung symptoms (BOS and air trapping on chest CT).

**Note:** The wording in the original specification states that those special symptoms can only be confirmed "by a distinctive sign in _another organ_", which may or may not be a consequence of the fact that there cannot be any other distinctive sings in the lung. In any case, the scoring implementation implicitly ignores the "another organ" aspect at the moment and _any_ normal distinctive pending sign counts.

#### Acute Grading
```yaml
score:
  acute: skin
```
The presence of the `acute` property implies that the question is relevant for the acute score and if left unanswered will count as a _missing acute_ question. Additionally, the answer value of the question (if present) is used as the acute score for the organ that is indicated by the property's value.

#### Acute Delay
```yaml
score:
  delay: normal | delayed
```
Indicates the delay between the last allogeneic transplant and onset of symptoms. At most one answer may have this property and its value defines the aGVHD delay status.

## GVHD Current Staging
This scoring algorithm is slightly more complicated than the previous ones. It is based on a grading system with values from 0 (good) to 3 (bad) and within a category/organ, the highest score usually counts. The total score involves some counting and some special handling with potential _overrides_, so the score configuration can be a bit tricky. The actual score values for each individual question are usually taken directly from the response value (which should then of course be in the range [0, 3]).

**Grouping of Questions - Categories / Organs**
```yaml
score:
  category: skin | lungs | liver | ...
  organ: true | false (absence implies false)
```
Questions are generally grouped into _categories_ which _may_ represent organs, but also groups of questions like "performance" or "other" questions. Within such a group, the default case is that the highest score will count for this category. In addition, the `organ` property declarse the category to be an organ which means that it's score will be used to calculate the global severity in the final stage.

**Non-GVHD Causes**
```yaml
score:
  category: ...
  nongvhd: true | false
```
The `nongvhd` property will effectively remove the organ/category from the global scoring _if_ the response value matches the property's value. This override is permanent and cannot be undone.

**Bumping**
```yaml
score:
  bump: 1 (integer value)
```
_Bumping_ a score means that any **non-zero** value will be increased by the `bump` value for the purpose of global scoring. This is used to increase the weight of the lung score and avoid explicit special handling of lungs in the calculator code.

**Priority Overrides**
```yaml
score:
  priority: 2 (integer value)
```
With the `priority` property, the category/organ max scoring can be tweaked to allow one priority level to override a lower one. For the organ scoring, specifying a priority means that:
* if the current aggregate score has _no_ priority or a priority that is _lower_ than the current response value, it will override the current aggregate
* if the current aggregate score has a priority that is _equal_ to the current response value's priority, then the `max` function is used as usual
* if the current aggregate score has a _higher_ priority than the current response value, the current aggregate score remains unchanged

**Range Value Mapping**
```yaml
score:
  value:
    -
      range: [0, 40]
      value: 3
    -
      range: [40, 60]
      value: 2
    -
      range: [60, 80]
      value: 1
    -
      range: [79, 100]
      value: 0
    -
      range: []
      value: -1
```
Certain response values cannot directly be used as the severity score, but they can be used to _compute_ the severity score. In this case, the possible values can be divided into ranges and the ranges can be mapped to a severity score. The `range`s in the `value` property are processed top to bottom and the first matching range that is found defines the severity `value`. The ranges themselves take the form of a 2-element array with the first element defining the (**inclusive**) low value and the second element defining the (**exclusive**) high value. If the response value happens to fall into that range, the matching severity value is used for scoring. A range with the empty array can be used as a catch-all range - make sure to put this range at the end of your list. The value `'null'` can be used in either of the two array positions to declare an open-ended range (e.g. `['null', 0]` will match any number < 0).
