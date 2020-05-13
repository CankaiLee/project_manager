exports.install = function (Vue, options) {
    Vue.prototype.html_build_query = function (options) {
        let query_array = [];
        for (let key in options) {
            let val = options[key];
            query_array.push(key + '=' + val);
        }
        return query_array.join('&');
    }
};
