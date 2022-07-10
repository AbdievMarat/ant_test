$(() => {

    $(document).on('click','.copy_url', function() {
        const temp = $("<input>");
        $("body").append(temp);
        temp.val($(this).data('url')).select();
        document.execCommand("copy");
        temp.remove();
    });

});

