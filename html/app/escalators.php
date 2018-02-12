<style>
    #content {
        margin: 0;
        padding: 0;
        min-width: unset;
    }
</style>
<script>
    let iframeRef = null
    function resizeIframe(obj) {
        iframeRef = obj
        iframeWindow = obj.contentWindow
        const leftPanel = document.getElementById('left-panel')
        const width = window.innerWidth;
        if(width < 979) {
            obj.style.width = window.innerWidth + 'px';
        } else {
            obj.style.width = (window.innerWidth - leftPanel.offsetWidth) + 'px';
        }
        obj.height = (window.innerHeight - (52 + 40));
        iframeWindow.height = (window.innerHeight - (52 + 40));
    }
    let timerId = null
    window.onresize = function() {
        if(iframeRef !== null) {
            if(timerId != null) {
                clearTimeout(timerId)
            }
            timerId = setTimeout(() => {
                resizeIframe(iframeRef)
                timerId = null
            }, 200)
        }
    }
</script>
<iframe src="/escalator-app/index.html"
        frameborder="0" scrolling="yes" onload="resizeIframe(this)">
</iframe>
