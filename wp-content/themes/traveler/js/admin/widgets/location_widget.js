jQuery(".set_custom_images").length > 0 && "undefined" != typeof wp && wp.media && wp.media.editor && jQuery(".wrap").on("click", ".set_custom_images", function (e) {
    e.preventDefault();
    var t = jQuery(this),
        i = t.prev();
    return wp.media.editor.send.attachment = function (e, t) {
        i.val(t.id)
    }, wp.media.editor.open(t), !1
}), jQuery(function (e) {
    e("button.del_bgr").each(function () {
        e(this).on('click', function () {
            var t = e(this).parent("p");
            return t.find(".bgr_info_hidden").val(""), t.find("img").remove(), e(this).remove(), !1
        })
    })
}), jQuery(function (e) {
    e(this).on("click", ".checked_label_location", function () {
        e(this).prev().attr("checked", "")
    })
});
