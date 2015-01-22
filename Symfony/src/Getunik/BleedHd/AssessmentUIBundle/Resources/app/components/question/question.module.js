
(function (angular, bleedHd) {

	var module = angular.module('question', [
		'bleedHdApp',
		'typeRegistry',
		'assessment',
		bleedHd.getView('question', 'container'),
		bleedHd.getView('question', 'question-types/yesno'),
		bleedHd.getView('question', 'question-types/radios'),
		bleedHd.getView('question', 'question-types/radios-horizontal'),
		bleedHd.getView('question', 'question-types/checkboxes'),
		bleedHd.getView('question', 'question-types/text'),
		bleedHd.getView('question', 'question-types/textarea'),
		bleedHd.getView('question', 'supplement-types/checkbox'),
		bleedHd.getView('question', 'supplement-types/checkboxes'),
		bleedHd.getView('question', 'supplement-types/radios'),
		bleedHd.getView('question', 'supplement-types/radios-horizontal'),
		bleedHd.getView('question', 'supplement-types/text'),
		bleedHd.getView('question', 'supplement-types/textarea'),
	]);

})(angular, bleedHd);
