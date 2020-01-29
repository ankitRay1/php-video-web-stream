!function($, w, d, undefined) {
	'use strict';
	var o = {
		recieveUri:'',
		sendUri:'',
		formid: '#chat-form',
		messageArea: '#messages',
		lastIdEl: 'input[name=lastid]',
		submitEl: '#submit',
		messageEl: '#message',
		mTimeout: 1000,
		template: function(line) {},
		activity: '',
		maxMessagesHeight: 200,
	},
	working = false,
	noActivity = 0,
	lastId = 0;

	function getIndex(object, index) {
		var j = 0;
		for(var i in object) {
			if(index == j) {
				return i;
			}
			j++;
		}
		return 0;
	}



	function submitMessage(form) {
		if($(o.messageEl).val().length == 0)
			return false;

		working = true;
		$(o.submitEl).attr("disabled", true);
		$.post(o.sendUri, form.serialize(), function(data) {
			working = false;
			$(o.submitEl).attr("disabled", false);
			$(o.messageEl).val('');

			if(noActivity > getIndex(o.activity, 2)) {
				getMessages();
			}

		});
	}

	function scrollBot(el) {
		$(el).animate({ scrollTop: $(document).height() }, "slow");
	}

	function getMessages(callback) {
		var nextRequest = o.mTimeout,
				form = $(o.formid);

		$.post(o.recieveUri, form.serialize(), function(data){
			noActivity = data.length > 0 ? 0 : noActivity + 1;

			for(var i in data) {
				if(typeof(data[i].id) == 'undefined') {
					noActivity = 10;
					break;
				}
				if(data[i].id > lastId) {
					lastId = data[i].id;
					$(o.lastIdEl).val(lastId);
				}
				$(o.messageArea).append(o.template.call(this, data[i]));
				if($(o.messageArea).height() >= o.maxMessagesHeight) {
					$(o.messageArea).css("overflow-y", "scroll");
				}
			}

			if(data.length > 0) {
				scrollBot(o.messageArea);
			}

			for(var j in o.activity) {
				if(noActivity > j)
					nextRequest = o.activity[j];
			}

			if(typeof(callback) == "function")
				setTimeout(callback, nextRequest);
		}).fail(function(){
			console.log("Unable to recieve messages");
			
		}).fail(function(xhr, ajaxOptions, thrownError, request, error) {
			if(xhr.status == 400) {
				return false;
			}
			if(typeof(callback) == "function")
				setTimeout(callback, nextRequest);
		});
		
	}

	$.jOnlineChat = function(options) {
		o = $.extend({}, o, options);
		o.activity = $.parseJSON(o.activity);

		var form = $(o.formid);

		$(form).on({
			submit: function(e) {
				e.preventDefault();
				submitMessage(form);
			},
			keypress: function(e) {
				if (e.keyCode == 13) {
					e.preventDefault();
					submitMessage(form);
					return false;
				}
			},
		});


		// Self executing timeout function
		(function getMessagesTimeout() {
			getMessages(getMessagesTimeout);
		})();

	};
}(jQuery, window, document);