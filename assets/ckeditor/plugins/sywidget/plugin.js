CKEDITOR.plugins.add('sywidget', {
	requires: 'widget',
	init: function(editor) {
		editor.widgets.add('sywidget', {
			upcast: function(element, data) {
				if (typeof element.attributes['data-sylock'] !== 'undefined') {
					if (element.attributes['data-sylock'] === 'attributes') {
						storeAttributes(element);
					} else {
						data.html = element.getHtml();
					}
					return true;
				}
				return false;
			},
			downcast: function(element) {
				if (typeof element.attributes['data-sylock-attributes'] !== 'undefined') {
					restoreAttributes(element);
					var res = new CKEDITOR.htmlParser.element(element.name, element.attributes);
					res.setHtml(element.getHtml());
				} else {
					var res = new CKEDITOR.htmlParser.element(element.name, element.attributes);
					res.setHtml(this.data.html);
				}
				return res;
			},
			editables: {
				content: '[data-sylock="attributes"]'
			}
		});
	}
});

function storeAttributes(element) {
	element.attributes['data-sylock-attributes'] = JSON.stringify(element.attributes);

	element.children.forEach(function (element) {
		if (element.type === CKEDITOR.NODE_ELEMENT) {
			storeAttributes(element);
		}
	});
}

function restoreAttributes(element) {
	if (typeof element.attributes['data-widget'] !== 'undefined') {
		delete element.attributes['data-widget'];
		var parent = element.getAscendant('div');
		if (parent !== null) {
			if (typeof element.attributes['data-sylock-attributes'] !== 'undefined') {
				delete element.attributes['data-sylock-attributes'];
			}

			if (typeof element.attributes['data-sycomponent-slot'] !== 'undefined' && element.attributes['data-sycomponent-slot'].length > 0) {
				element.setHtml(element.attributes['data-sycomponent-slot']);
				delete element.attributes['data-sycomponent-slot'];
			}
			parent.replaceWith(element);
		}
	}

	if (typeof element.attributes['data-sylock-attributes'] !== 'undefined') {
		element.attributes = JSON.parse(element.attributes['data-sylock-attributes']);
		delete element.attributes['data-sylock-attributes'];
	}

	element.children.forEach(function (element) {
		if (element.type === CKEDITOR.NODE_ELEMENT) {
			restoreAttributes(element);
		}
	});
}