function ObserveDblClick() {
    alert('ovde');
    var plugin = this;

    // public methods
    this.init = function() {
        bindHeaders.call(this);
    };

    this.dblclickColHeader = function(col) {
        Handsontable.hooks.run(this, 'dblclickColHeader', col);
    };

    this.dblclickRowHeader = function(row) {
        Handsontable.hooks.run(this, 'dblclickRowHeader', row);
    };
	
    this.dblclickCell = function(row, col) {
        Handsontable.hooks.run(this, 'dblclickCell', row, col);
    };

    // private methods
    var bindHeaders = function() {
        var instance = this,
            eventManager = Handsontable.eventManager(instance);
        
        eventManager.addEventListener(instance.rootElement, 'dblclick', function(e) {
            if (Handsontable.Dom.hasClass(e.target, 'relative')) {
                var col = getColumn(e.target),
                    row = getRow(e.target);
                col -= countRowHeaders(instance);

                if (col == 0 & row == 0 && isCorner(e.target)) {
                    return; 
                } else if (col == 0) {
                    plugin.dblclickRowHeader(row);
                } else if (row == 0) {
                    plugin.dblclickColHeader(col);
                } else {
                    plugin.dblclickCell(row, col);
                }
            }
        });
    };

    function getColumn(target) {
        var TH = Handsontable.Dom.closest(target, 'TH');
        return Handsontable.Dom.index(TH);
    }

    function getRow(target) {
        var TR = Handsontable.Dom.closest(target, 'TR'); 
        return Handsontable.Dom.index(TR);
    }
    
    function isCorner(target) {
        var TR = Handsontable.Dom.closest(target, 'TR');
        return !TR.querySelector('.rowHeader');
    }

    function countRowHeaders(instance) {
        var THs = instance.view.TBODY.querySelector('tr').querySelectorAll('th');
        return THs.length;
    }

};

var observeDblClick = new ObserveDblClick();

Handsontable.hooks.add('beforeInit', observeDblClick.init);

Handsontable.hooks.register('dblclickColHeader');
Handsontable.hooks.register('dblclickRowHeader');
Handsontable.hooks.register('dblclickCell');

Handsontable.plugins.ObserveDblClick = ObserveDblClick; 