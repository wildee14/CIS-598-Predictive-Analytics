window.Instagram = {
    /**
     * Store application settings
     */
    config: {},

    BASE_URL: 'https://api.instagram.com/v1',

    init: function( opt ) {
        opt = opt || {};
	this.config.access_token = opt.access_token;
        this.config.client_id = opt.client_id;
    },

    /**
     * Get a list of popular media.
     */
    nearby: function( callback ) {
        var endpoint = this.BASE_URL + '/media/search?lat=48.858844&lng=2.294351&access_token=' + this.config.access_token;
        this.getJSON( endpoint, callback );
    },
    /**
     * Get a list of popular media.
     */
    user: function( callback ) {
        var endpoint = this.BASE_URL + '/users/143957662?access_token=' + this.config.access_token;
        this.getJSON( endpoint, callback );
    },

    /**
     * Get a list of recently tagged media.
     */
    recent: function(  callback ) {
        var endpoint = this.BASE_URL + '/users/self/media/recent?access_token=' + this.config.access_token;
        this.getJSON( endpoint, callback );
    },
    recentM: function(acc,  callback ) {
        var endpoint = this.BASE_URL + '/users/self/media/recent?access_token=' + acc;
        this.getJSON( endpoint, callback );
    },

    getJSON: function( url, callback ) {
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'jsonp',
            success: function( response ) {
                if ( typeof callback === 'function' ) callback( response );
            }
        });
    }
};

Instagram.init({
    client_id: 'bbf9d5179ff24ceea0d41e9bf7b6651b',
    access_token: '143957662.1677ed0.d8fb439ed71345849dc675eacbc284f1',
    URL: 'http://test.mattwilderson.com/seniorProject/index.php'
});

//window.document.onLoad = (Instagram.user(function(r) {console.log(r);})); 
//$(window).load($("#recent").text(Instagram.user(function(r){return r;})));
