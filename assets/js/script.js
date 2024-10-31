jQuery('.fraction').each(function(key, value) {
    $this = jQuery(this)
    var split = $this.html().split("/")
    if( split.length == 2 ){
        $this.html('<span class="top">'+split[0]+'</span><span class="bottom">'+split[1]+'</span>')
    }    
});