var Home = function() {
    return {
        fields: {
        },
        init: function() {
        },
        gotoMenu: (type) => {
            const role_name = $('#role_name').val();
            window.location.href = `/portal/${type}/${role_name}`;
        }
    }
}();