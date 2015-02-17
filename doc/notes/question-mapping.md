
# cGvHD Table 3 / cGvHD staging / cGvHD organ scoring

Performnance scrore type [not score relevant]
: cgvhd-organ-scoring.performance.type

Performance score (actual score)
: cgvhd-organ-scoring.performance.score

-----

* TODO: special range partition score calculation

Skin BSA(%) Value
: cgvhd-organ-scoring.skin.bsa-score

Skin BSA Features
: cgvhd-organ-scoring.skin.bsa-features

Score relevant Skin Features (non-BSA)
: cgvhd-organ-scoring.skin.features-score

Skin Features (non-BSA)
: cgvhd-organ-scoring.skin.features-other

Skin non-GvHD cause (override question)
: cgvhd-organ-scoring.skin.non-gvhd

-----

Mouth symptom scoring
: cgvhd-organ-scoring.mouth.score

Mouth lichen planus-like features (yes/no) [not score relevant]
: cgvhd-organ-scoring.mouth.features

Mouth non-GvHD cause (override question)
: cgvhd-organ-scoring.mouth.non-gvhd

-----

Eyes symptom scoring
: cgvhd-organ-scoring.eyes.score

Eyes KCS symptoms (yes/no) [not score relevant]
: cgvhd-organ-scoring.eyes.features

Eyes non-GvHD cause (override question)
: cgvhd-organ-scoring.eyes.non-gvhd

-----

GI Tract symptom scoring
: cgvhd-organ-scoring.gi-tract.score

GI Tract symptoms [not score relevant]
: cgvhd-organ-scoring.gi-tract.features

GI Tract non-GvHD cause (override question)
: cgvhd-organ-scoring.gi-tract.non-gvhd

-----

Liver symptom scoring
: cgvhd-organ-scoring.liver.score

Liver non-GvHD cause (override question)
: cgvhd-organ-scoring.liver.non-gvhd

-----

Lugs symptom score
: cgvhd-organ-scoring.lungs.symptoms-score

* TODO: FEV1 score has higher priority for global lung scoring

Lung Pulmonary function test (FEV1) score
: cgvhd-organ-scoring.lungs.pft-score

Lungs non-GvHD cause (override question)
: cgvhd-organ-scoring.lungs.non-gvhd

-----

Joints and Fascia symptoms score
: cgvhd-organ-scoring.joings-and-fascia.score

J&F P-ROM shoulder [not score relevant]
: cgvhd-organ-scoring.joings-and-fascia.p-rom.shoulder-left
: cgvhd-organ-scoring.joings-and-fascia.p-rom.shoulder-right

J&F P-ROM elbow [not score relevant]
: cgvhd-organ-scoring.joings-and-fascia.p-rom.elbow-left
: cgvhd-organ-scoring.joings-and-fascia.p-rom.elbow-right

J&F P-ROM wrist/finger [not score relevant]
: cgvhd-organ-scoring.joings-and-fascia.p-rom.wrist-left
: cgvhd-organ-scoring.joings-and-fascia.p-rom.wrist-right

J&F P-ROM ankle [not score relevant]
: cgvhd-organ-scoring.joings-and-fascia.p-rom.ankle-left
: cgvhd-organ-scoring.joings-and-fascia.p-rom.ankle-right

-----

Genital Tract symptom score
: cgvhd-organ-scoring.genital-tract.score

Sexually active?
: cgvhd-organ-scoring.genital-tract.sexually-active

Genital Tract symptoms [not score relevant]
: cgvhd-organ-scoring.genital-tract.features

Genital Tract non-GvHD cause (override question)
: cgvhd-organ-scoring.genital-tract.non-gvhd

-----

Other indicators (multi question)
: cgvhd-organ-scoring.other-indicators.ascite.score
: cgvhd-organ-scoring.other-indicators.peicardial-effusion.score
: cgvhd-organ-scoring.other-indicators....
: cgvhd-organ-scoring.other-indicators.eosinophilia.value
: cgvhd-organ-scoring.other-indicators.others

-----

Overall GvHD Severity score
: cgvhd-organ-scoring.evaluator.score

# WHO

* who.mouth-and-nose.petechiae
* who.mouth-and-nose.petechiae.isnew (+)
* who.mouth-and-nose.mouth-bleed
* who.mouth-and-nose.mouth-bleed.duration (+)
* who.mouth-and-nose.nose-bleed
* who.mouth-and-nose.nose-bleed.duration (+)
* who.skin-and-tissue.petechiae
* who.skin-and-tissue.petechiae.isnew (+)
* who.skin-and-tissue.petechiae.generalized (+)
* who.skin-and-tissue.skin-hematomas
* who.skin-and-tissue.skin-hematomas.isnew (+)
* who.skin-and-tissue.skin-hematomas.number (+)
* who.skin-and-tissue.skin-hematomas.size (+)
* who.skin-and-tissue.soft-tissue-hematomas
* who.skin-and-tissue.soft-tissue-hematomas.isnew (+)
* who.skin-and-tissue.deep-tissue-hematomas
* who.skin-and-tissue.deep-tissue-hematomas.isnew (+)
* who.skin-and-tissue.joint-bleeding
* who.skin-and-tissue.joint-bleeding.isnew (+)
* who.gastrointestinal.melanotic-stool
* who.gastrointestinal.melanotic-stool.multiple (+)
* who.gastrointestinal.blood-stool
* who.gastrointestinal.blood-stool.multiple (+)
* who.gastrointestinal.hematemesis
* who.gastrointestinal.hematemesis.multiple (+)
* who.urogenital.urine-microscopic-blood
* who.urogenital.urine-macroscopic-blood
* who.urogenital.vaginal-bleeding
* who.urogenital.vaginal-bleeding.multiple (+)
* who.pulmonary.blood-cough
* who.pulmonary.blood-lavage
* who.pulmonary.blood-sputum
* who.body-cavities.blood-cavity-fluid
* who.body-cavities.intervention
* who.cns.retinal-bleed-noimp
* who.cns.retinal-bleed-imp
* who.cns.liquor
* who.cns.liquor.microscopic (+)
* who.cns.liquor.macroscopic (+)
* who.cns.bleed-nosymptoms
* who.cns.bleed-symptoms
* who.invasive-sites.bleed
* who.invasive-sites.bleed.site
* who.bleeding-complications.hemodynamic-instability
* who.bleeding-complications.hemodynamic-instability.severe (+)
* who.bleeding-complications.fatal-bleed
* who.bleeding-complications.rbc
* who.intervention.intervention-needed
* who.intervention.intervention-type
* who.intervention.intervention-type.other (+)
* who.intervention.medication-administered
* who.intervention.medication-type
* who.intervention.medication-type.other (+)
* who.source-check.nursing-documented
* who.source-check.medical-record


# BSMS

* bsms.general.grade
* bsms.general.type-or-site
* bsms.general.type-or-site.specify
