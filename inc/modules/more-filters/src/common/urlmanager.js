function urlManager() {
    return {
        getParam: (param) => {
            if (param === undefined) return
            const url = new URL(window.location.href)
            return url.searchParams.get(param)
        },
        updateParam: (param, val) => {
            if (val === undefined || !val) return
            val = encodeURI(val)
            let url = new URL(window.location.href)
            let search_params = url.searchParams
            search_params.sort()

            search_params.set(param, val)
            url.search = search_params.toString()
            url = url.toString()
            history.pushState('', '', url)
        },
        deleteParam: (param) => {
            if (param === undefined) return
            let url = new URL(window.location.href)
            let search_params = url.searchParams
            search_params.delete(param)
            url.search = search_params.toString()
            url = url.toString()
            history.pushState('', '', url)
        },
        deleteParms: () => {
            var url = new URL(window.location.href)
            url.search = ''
            url = url.toString()
            history.pushState('', '', url)
        }
    }
}

const UrlManager = urlManager();

export default UrlManager;
