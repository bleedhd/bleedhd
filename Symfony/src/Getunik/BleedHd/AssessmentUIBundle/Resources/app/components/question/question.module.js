
(function (angular, bleedHd) {

	var module = angular.module('question', [
		//'bleedHdApp',
		'assessment',
		bleedHd.getView('question', 'container'),
		bleedHd.getView('question', 'question-types/yesno'),
		bleedHd.getView('question', 'question-types/checkboxes'),
		bleedHd.getView('question', 'supplement-types/checkbox'),
	]);

})(angular, bleedHd);
