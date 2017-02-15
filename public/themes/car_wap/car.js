//detail

function product_detail() {
    $(function () {
        $('.product label').on('click',function () {
            $(this).addClass('active').siblings().removeClass('active');
            $('#spec_id').val($(this).attr('data_id'));
        })
    });
}