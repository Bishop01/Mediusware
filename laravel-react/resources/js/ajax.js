$(document).ready(() => {

    $("#form").on('submit', (e) => {
        e.preventDefault();

        var title = $("#title").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var variants = $("#variants").val();

        if (from != "")
            from = +from
        if (to != "")
            to = +to

        if (from == "")
            from = 0
        if (to == "")
            to = Number.MAX_SAFE_INTEGER

        var data = {
            title: title,
            from: from,
            to: to,
            variants: variants
        }

        console.log(data)

        $.ajax({
            url: "/product",
            method: "Get",
            data: data,
            success: function (response) {
                console.log(response);
                $("body").html(response);

                $("#title").val(title);
                from == 0 ? "" : $("#from").val(from);
                to == Number.MAX_SAFE_INTEGER ? "" : $("#from").val(to);
            },
            error: function (err) {
                alert("Error")
                console.log(err)
            }
        })
    });

})