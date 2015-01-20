
(function (angular, bleedHd) {

	var module = angular.module('question', [
		//'bleedHdApp',
		'assessment',
		bleedHd.getView('question', 'container'),
		bleedHd.getView('question', 'question-types/yesno'),
		bleedHd.getView('question', 'question-types/radios'),
		bleedHd.getView('question', 'question-types/checkboxes'),
		bleedHd.getView('question', 'question-types/text'),
		bleedHd.getView('question', 'question-types/textarea'),
		bleedHd.getView('question', 'supplement-types/checkbox'),
		bleedHd.getView('question', 'supplement-types/text'),
	]);

})(angular, bleedHd);
