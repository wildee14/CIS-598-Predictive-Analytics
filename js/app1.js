window.Instagram = {
	config: {},

	insta_url: 'https://api.instagram.com/v1',
	init: function(opt) {
	 opt = opt || {};
	 this.config.client_id = opt.client_id;
        },
	highFreq: function(callback) {
	    var ep = this.insta_url + '/media/popular?client_id=' + this.config.client_id;
	    this.getData(ep,callback);
	},

        loc: function(name,callback) {
		var ep = this.insta_url + '/tags/'+name+ '/media/recent?client_id=' + this.config.client_id;
        	this.getData(ep, callback);
	},
	getData: function(url, callback){
		$.ajax({
			type:'GET', 
			url:url, 
			dataType: 'jsonp', 
			sucess: function(response){
				if(typeof callback === 'function') callback(response);
			}
		});
	}

};

Instagram.init(
 {
   client_id: 'd49da08a520f47cbb6e7618f077f33ef'
 }
);
