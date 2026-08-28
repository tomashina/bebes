function DigitalElephantFilterPagination() {
    DigitalElephantFilterPagination.selfObj = null;

    this.config = DEFConfig;
    this.selector = DEFConfig.selector.pagination;
    this.selectorLink = DEFConfig.selector.pagination + ' a';
    this.sync   = DigitalElephantFilterSync.instance();

    this.off = function() {
        $(this.selector).css('display', 'none');
    };

    this.render = function($_GET_string) {
        var $this = this;
        const qs = $_GET_string;

// napravi siguran separator
const sep = (qs.indexOf('?') === -1 && this.config.action.ajaxRenderPagination.indexOf('?') === -1)
    ? '?'
    : '&';

// buildaj finalni URL
const finalUrl =
    this.config.action.ajaxRenderPagination +
    (qs ? qs : '') +
    sep +
    'path=' + encodeURIComponent(this.config.categoryPath);

$(this.selector).load(finalUrl, function(result, status){
    if (status === 'success') {
        $this.off();
        $this.sync.addLoadedElements($this.selector);
    }
});
 function(result, status){
            if (status === 'success') {
                $this.off();
                $this.sync.addLoadedElements($this.selector);
            }
        });
    };

    this.preloaderOn = function() {
        $(this.selector).html('' +
            '<i ' +
            'class="' + this.config.preloaderClass + '"' +
            'style="margin: auto; margin-top: 10px; display: block;">');
    };

    this.isset = function() {
        return this.config.state.isPagination;
    }
}

/**
 * Singletone
 * @return DigitalElephantFilterPagination
 */
DigitalElephantFilterPagination.instance = function() {
    if (this.selfObj == null) {
        this.selfObj = new DigitalElephantFilterPagination();
    }

    return this.selfObj;
};