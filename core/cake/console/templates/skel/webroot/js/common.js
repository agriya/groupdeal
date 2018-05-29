jQuery.fn.confirm = function () {
    this.click(
        function () {
            return window.confirm("Are you sure you want to " + this.innerHTML.toLowerCase() + "?");
        }
    );
};

$(document).ready(function () {
//
$('a.js-delete').confirm();
});
