(function (_) {
    _.mixin({
        //extended core debounce function to respect also last call
        debounce: function (func, wait, immediate, respectLastCall) {
            var timeout;
            return function () {
                var context = this, args = arguments;
                var later = function () {
                    timeout = null;
                    if (!immediate || respectLastCall) func.apply(context, args);
                };
                if (immediate && !timeout) func.apply(context, args);
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    })
})(_)