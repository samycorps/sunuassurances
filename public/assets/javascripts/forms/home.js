var Home = function() {
    return {
        fields: {
        },
        init: function() {
        },
        gotoMenu: (type) => {
            const role_name = $('#role_name').val();
            window.location.href = `/sunu/${type}/${role_name}`;
        }
    }
}();