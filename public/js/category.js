$(document).ready(function() {
    //create category
    $(".btn-submit").click(function(e){
        e.preventDefault();
        var _token = $("input[name='_token']").val();
        var category = $("input[name='category']").val();
        var sub_category = $("input[name='sub_category']").val();

        $.ajax({
            url: "/category/create",
            type:'POST',
            data: {_token:_token, category:category, sub_category:sub_category},
            success: function(data) {
                if($.isEmptyObject(data.error)) {
                    toastr.success(data.message);
                    var html = '';
                    if(data.parent_category != null) {
                        if($("#"+data.parent_category).has("ul").length > 0) {
                            html += '<li id="'+data.category+'">'+data.category+'</li>';
                        } else {
                            html += '<ul>';
                            html += '<li id="'+data.category+'">'+data.category+'</li>';
                            html += '</ul>';
                        }
                        $("#"+data.parent_category).append(html);
                    } else {
                        html += '<li id="'+data.category+'">'+data.category+'</li>';
                        $("#category_list").append(html);
                    }
                } else {
                    toastr.error(data.error);
                }
            }
        });
    });

    //Get category using autocomplete
    $("#sub_category").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: '/category/autocomplete',
                type: 'post',
                dataType: "json",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    search: request.term
                },
                success: function (data) {
                    if (data.status == 200) {
                        response(data.data);
                    } else {
                        toastr.error('Category not found!');
                    }
                }
            });
        },
        select: function (event, ui) {
            $('#sub_category').val(ui.item.label);
            return false;
        }
    });
});