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

## GvHD Features
This is another trivial questionnaire in terms of scoring. There is one question and it establishes the presence or absence of GvHD features. The calculator expects at most one answer with a `present` property which may be either `true` or `false`

```yaml
score:
  present: true
```

## GvHD First Diagnosis
Most of the first diagnosis scoring boils down to counting. Counting the number of unanswerd questions that are relevant for chronic and acute diagnosis, counting of diagnostic signs, counting of distinctive signs and counting of confirmed distinctive signs. The rest is a simple two stage aggregation of the counts that first boils everything down to an acute and a chronic score and then computes the total score out of those two. The following samples describe how the individual counts are controlled through the scoring configuration:


**Diagnostic & Distinctive GvHD Signs/Symptoms**
```yaml
score:
  type: diagnostic | distinctive
```
The presence of the type attribute in a question's scoring configuration implies that
1. The question is relevant for the chronic score and if left unanswered will count as a _missing chronic_ question
2. If there is an answer associated with the score, it counts the presence of a diagnostic/distinctive sign

**Confirmation Supplements**
```yaml
score:
  status: pending | positive
```
This scoring configuration is only valid on questions for distinctive signs. An answer with the `pending` status indicates that the confirmation is still pending while `positive` implies a positive test result and counts as a confirmed distinctive sign (a negative confirmation is implicitly everything else).

**Acute Grading**
```yaml
score:
  acute: skin
```
The presence of the `acute` property implies that the question is relevant for the acute score and if left unanswered will count as a _missing acute_ question. Additionally, the answer value of the question (if present) is used as the acute score for the organ that is indicated by the property's value.

**Acute Delay**
```yaml
score:
  delay: normal | delayed
```
Indicates the delay between the last allogeneic transplant and onset of symptoms. At most one answer may have this property and its value defines the aGvHD delay status.
