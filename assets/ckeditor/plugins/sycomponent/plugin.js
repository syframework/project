CKEDITOR.plugins.add('sycomponent', {
	requires: 'widget',
	init: function(editor) {
		editor.widgets.add('sycomponent', {
			upcast: function(element) {
				return (typeof element.attributes['data-sycomponent'] !== 'undefined' && element.attributes['data-sycomponent'].length > 0);
			},
			downcast: function(element) {
				if (typeof element.attributes['data-sycomponent-slot'] !== 'undefined' && element.attributes['data-sycomponent-slot'].length > 0) {
					element.setHtml(element.attributes['data-sycomponent-slot']);
					delete element.attributes['data-sycomponent-slot'];
					return element;
				}
			},
			mask: true
		});
	}
});