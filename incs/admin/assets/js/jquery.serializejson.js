(function($){
    // serializeJSON that flattens top-level section[...] into option keys
    // e.g., "section[option]" => { option: value }
    // supports nested keys like "section[dimension][top]" => { dimension: { top: value }}
    $.fn.serializeJSON = function() {
        var obj = {};
        var arr = this.serializeArray();
        arr.forEach(function(item){
            var name = item.name;
            var value = item.value;

            // Names with brackets
            var m = name.match(/^([^\[]+)((?:\[[^\]]*\])*)$/);
            if (m) {
                var base = m[1];
                var rest = m[2]; // like [opt][sub] or [opt][]

                if (rest) {
                    // extract keys inside brackets
                    var keys = rest.replace(/^\[|\]$/g, '').split('\]\[');
                    var mainKey = keys[0];

                    // If only one key, it's a simple key: section[key]
                    if (keys.length === 1) {
                        obj[mainKey] = value; // later entries overwrite earlier
                    } else {
                        // nested structure: ensure obj[mainKey] exists
                        if (typeof obj[mainKey] === 'undefined') obj[mainKey] = {};
                        var cur = obj[mainKey];

                        for (var i = 1; i < keys.length; i++) {
                            var k = keys[i];
                            var last = (i === keys.length - 1);

                            if (last) {
                                if (k === '') {
                                    // push into array
                                    if (!Array.isArray(cur)) {
                                        // if cur is an empty object, convert to array
                                        if (Object.keys(cur).length === 0) {
                                            cur = [];
                                            obj[mainKey] = cur;
                                        } else {
                                            // fallback: wrap into array
                                            cur = [cur];
                                            obj[mainKey] = cur;
                                        }
                                    }
                                    cur.push(value);
                                } else {
                                    cur[k] = value;
                                }
                            } else {
                                if (k === '') {
                                    if (!Array.isArray(cur)) {
                                        cur = [];
                                        obj[mainKey] = cur;
                                    }
                                    if (cur.length === 0 || typeof cur[cur.length - 1] !== 'object') cur.push({});
                                    cur = cur[cur.length - 1];
                                } else {
                                    if (typeof cur[k] === 'undefined') cur[k] = {};
                                    cur = cur[k];
                                }
                            }
                        }
                    }

                } else {
                    // no brackets, top-level name
                    obj[base] = value;
                }
            } else {
                obj[name] = value;
            }
        });
        return obj;
    };
})(jQuery);