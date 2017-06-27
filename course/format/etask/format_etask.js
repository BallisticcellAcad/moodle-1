YUI().use("yui2-yahoo-dom-event", "yui2-animation", "yui2-container", "yui2-dragdrop", function(Y) {
    // tooltip
    var tooltipElements = Y.YUI2.util.Dom.getElementsByClassName("gradeItemHeadTooltip", "a");
    new Y.YUI2.widget.Tooltip("tooltip", {
        context: tooltipElements, autodismissdelay: 60000, width: 400 + 'px'
    });

    // dialog grade settings
    var Event = Y.YUI2.util.Event;
    var Dom = Y.YUI2.util.Dom;
    Event.onDOMReady(function() {
        var settingElements = Dom.getElementsByClassName('gradeItemDialog');

        Dom.batch(settingElements, function(em) {
            var dialogs = [];

            dialogs[em.id] = new Y.YUI2.widget.Dialog('gradeSettings-' + em.id, {
                modal: true,
                visible: false,
                fixedcenter: true,
                constraintoviewport: true,
                postmethod: "form"
            });

            dialogs[em.id].render(document.body);
            Dom.setStyle('gradeSettings-' + em.id, 'display', 'block');
            Dom.setStyle(em.id, 'opacity', '1');
            Dom.setStyle(em.id, 'filter', 'alpha(opacity=50)');

            Event.on(em.id, 'click', dialogs[em.id].show, null, dialogs[em.id]);
        });
    });
});
