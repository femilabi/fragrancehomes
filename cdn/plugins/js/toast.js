function toast(msg, length, size, callback){
	if(msg == '') return;
	var box = $('<div class="toast" style="z-index:9999"></div>');
	var sizeClass = '';
	switch(size){
		case 'large':
			sizeClass = 'large';
			break;
		case 'small':
			sizeClass = 'small';
			break;
		default:
			sizeClass = 'medium';
			break;
	}
	$(box).addClass(sizeClass);
	box.html(msg);
	var duration = (length == 'long')? 3000 : 700;
	$(document.body).append(box);
	$(box).css('left', (($(document.body).width() - $(box).width())/ 2) + 'px');
	box.fadeIn(1500);
	$(box).animate({position: 'fixed'}, duration, function(){
		$(this).fadeOut(2500,'', function (){
			$(this).detach();
			if (typeof callback === "function") {
				var e = {msg: msg,
							length: length,
							size:size
				}
				callback(e);
			}
		});
	});
}