(function (w ,$){

	var MultiImageMetaPlugin = {
		init: function () {
			this.prepare();
			this.events();
		},
		prepare: function () {
			this.mainContanier 		= $('div.image-entry');
			this.imageCon 			= this.mainContanier.find('img')
			this.addBtn 			= this.mainContanier.find('a.get-image');
			this.delBtn 			= this.mainContanier.find('a.del-image');
		},
		events: function () {
			this.delBtn.on('click', function () {
				var that 	= $(this);
				var addBtn 	= that.parent().find('a.get-image');
				var imgCon 	= that.parent().find('img');
				var input 	= that.parent().find('input[type="hidden"]');

				that.addClass('hidden');
				input.val('');
				imgCon.attr('src', '');
				addBtn.removeClass('hidden');
			});

			this.addBtn.on('click', function () {
				var that 	= $(this);
				var delBtn 	= that.parent().find('a.del-image');
				var imgCon 	= that.parent().find('img');
				var input 	= that.parent().find('input[type="hidden"]');
				wp.media.editor.send.attachment = function(props, attachment) { 
					that.addClass('hidden');
					delBtn.removeClass('hidden');

					imgCon.attr('src', attachment.url);
					input.val(attachment.id);
				};
				wp.media.editor.open();
			});
		}
	}

	$(document).ready(function () {
		MultiImageMetaPlugin.init();
	});
	
})(window, jQuery);