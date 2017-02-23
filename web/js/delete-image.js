$(".del-check").change(function() {
    $(this).parent().toggleClass("del-wrapper", this.checked)
}).change();