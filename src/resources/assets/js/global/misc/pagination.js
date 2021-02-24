import Storage from './storage'

export default {
    data() {
        return {
            pagination: {
                per_page: 10,
                current_page: 1,
                total: 0,
                pages: 0,
            },
            callBackHandler: ({})
        }
    },
    methods: {
        paginationInit(per_page, callBackHandler) {
            this.pagination.current_page = 1;
            this.pagination.per_page = per_page;
            this.callBackHandler = callBackHandler;
        },
        calcPages(pagination) {
            this.pagination = Object.assign({}, this.pagination, {
                per_page: this.pagination.per_page,
                current_page: pagination.current_page,
                total: pagination.total,
                pages: pagination.total_pages,
            });
            if (pagination.current_page > pagination.total_pages && pagination.total_pages > 0) {
                this.showRecords(pagination.total_pages, true);
            }
        },
        showRecords(from, force) {
            force = (typeof force != 'undefined') ? force : false;
            var params = this.loadingCached();
            if (force || !params.hasOwnProperty('page')) {
                params['page'] = from;
            }
            params['query'] = '';
            this.callBackHandler(params);
        },
        from(pageNumber) {
            return ((pageNumber - 1) * this.pagination.per_page + 1) * 1;
        },
        to(from) {
            var to = (from * 1) + (this.pagination.per_page - 1) * 1;
            if (to >= this.pagination.total) {
                to = this.pagination.total;
            }
            return to;
        },
        showPage(pageNumber, force) {
            force = (typeof force != 'undefined') ? force : true;
            return this.showRecords(pageNumber, force);
        },
        first() {
            if (this.pagination.current_page > 1) {
                this.showPage(1);
            }
        },
        prev() {
            if (this.pagination.current_page > 1)
                this.showPage(this.pagination.current_page - 1);
        },
        next() {
            if (this.pagination.current_page < this.pagination.pages) {
                this.showPage(this.pagination.current_page + 1);
            }
        },
        last() {
            if (this.pagination.current_page < this.pagination.pages) {
                this.showPage(this.pagination.pages);
            }
        },
        print_show() {
            if (this.pagination.pages === 0) {
                return 'Showing 0 to 0 of 0';
            }
            return 'Showing ' + this.from(this.pagination.current_page) + ' to ' + this.to(this.from(this.pagination.current_page)) + ' of ' + this.pagination.total;
        },
        caching(params) {
            if (typeof params != 'undefined') {
                var searched = Storage.get('searched', {});
                searched[window.location.pathname] = params;
                Storage.set('searched', searched);
                return true;
            }
            return false;
        },
        loadingCached() {
            var searched = Storage.get('searched', {});
            if (searched.hasOwnProperty(window.location.pathname)) {
                return searched[window.location.pathname];
            }
            return {
                limit: this.pagination.per_page
            };
        },
        getQueryCached() {
            var searched = this.loadingCached();
            if (searched.hasOwnProperty('query')) {
                if (searched.query.hasOwnProperty('value')) {
                    return searched.query.value.replace(/[\*\+]/g, "");
                } else if (typeof searched.query == 'string') {
                    return searched.query.replace(/[\*\+]/g, "");
                }
            }
            return ''
        }
    }
}
