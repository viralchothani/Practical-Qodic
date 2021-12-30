
//create category
function createCategory() {
    //get value
    var _token = $("input[name='_token']").val();
    var category = $("input[name='category']").val();
    var sub_category = $("input[name='sub_category']").val();

    //category create using ajax
    $.ajax({
        url: "/category/create",
        type:'POST',
        data: {_token:_token, category:category, sub_category:sub_category},
        success: function(data) { //ajax response
            if($.isEmptyObject(data.error)) {
                toastr.success(data.message); //success msg toastr
                
                //append menu
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
                return true;
            } else {
                toastr.error(data.error); //error msg toastr
                return true;
            }
        }
    });
}


//Get category using autocomplete
$("#sub_category").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: '/category/get',
            type: 'post',
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                search: request.term
            },
            success: function (data) { //ajax response
                if (data.status == 200) {
                    response(data.data);
                } else {
                    toastr.error('Category not found!'); //error msg toastr
                }
                return true;
            }
        });
    },
    select: function (event, ui) {
        $('#sub_category').val(ui.item.label); //display category listing
        return false;
    }
});

