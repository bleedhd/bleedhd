
(function (angular, bleedHd) {

	var module = angular.module('question', [
		//'bleedHdApp',
		'assessment',
		bleedHd.getView('question', 'container'),
		bleedHd.getView('question', 'question-type-yesno'),
		bleedHd.getView('question', 'supplement-type-checkbox'),
	]);

})(angular, bleedHd);
