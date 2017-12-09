$(document).ready(function () {
    loadClients();
    loadOculist();
    addRecipeDataRow();

}).on('click', '#new_oculist_buttom', function (event) {
    event.preventDefault();
    addOculist();
}).on('click', '#new_line_recipe_data', function (event) {
    addRecipeDataRow();
}).on('click', '#remove_line_recipe_data', function (event) {
    removeRecipeDataRow();
}).on('click', '#new_recipe', function (event) {
    event.preventDefault();
    addRecipe();
});
function addRecipeDataRow() {
    recipe_data_count++;
    Mustache.tags = ["[[", "]]"];
    $("#recipe_data_rows").append(Mustache.to_html($("#recipe_data_row").html(), {'data_row_count': recipe_data_count}));
}
function removeRecipeDataRow() {
    $("#recipe_row_" + recipe_data_count).remove();
    recipe_data_count--;
}

function loadClients() {
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: WEB_PATH + 'clients',
        headers: {'apikey': USER_CLIENT},
        success: function (data) {
            Mustache.tags = ["[[", "]]"];
            $("#clients_list").html("");
            $("#clients_list").html(Mustache.to_html($("#client_row").html(), {'clients': data}));
            $("#clients_list").select2();
        },
        error: function () {
            alert("Error al cargar los clientes")
        }
    });
}

function loadOculist() {
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: WEB_PATH + 'oculist',
        headers: {'apikey': USER_CLIENT},
        success: function (data) {
            Mustache.tags = ["[[", "]]"];
            $("#oculist_list").html("");
            $("#oculist_list").html(Mustache.to_html($("#oculist_row").html(), {'clients': data}));
            $("#oculist_list").select2();
        },
        error: function () {
            alert("Error al cargar los Oculistas")
        }
    });
}

function addOculist() {
    data = [];
    has_error = false;
    jQuery.each($("#form_new_oculist").serializeArray(), function (i, field) {
        if (field.value == '') {
            $("#form_new_oculist [name=" + field.name + "]").parents('.form-group').addClass('has-error');
            has_error = true;
        } else {
            $("#form_new_oculist [name=" + field.name + "]").parents('.form-group').removeClass('has-error');
        }

    });
    if (!has_error) {
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: WEB_PATH + 'oculist',
            headers: {'apikey': USER_CLIENT},
            data: {
                professional_code: $("#form_new_oculist [name=professional_code]").val(),
                name: $("#form_new_oculist [name=name]").val(),
                lastname: $("#form_new_oculist [name=lastname]").val()
            },
            success: function (data) {
                loadOculist();
            },
            error: function () {
                alert("Error al agregar oculista");
            }
        });
    }
}

function addRecipe() {
    var date = $("[name=date]").datepicker("getDate");
    if (date == null) {
        alert("Indique una fecha");
        return false;
    }
    var recipe_data = [];
    for (var i = 1; i <= recipe_data_count; i++) {
        recipe_data.push(JSON.stringify({
            'close': $("#recipe_row_" + i + " input[name=distRadio" + i + "]:checked").val() == 0,
            'distance': $("#recipe_row_" + i + " input[name=distRadio" + i + "]:checked").val() == 1,
            'eye': $("#recipe_row_" + i + " input[name=eyeRadio" + i + "]:checked").val(),
            'esf': $("#recipe_row_" + i + " [name=esf]").val(),
            'cil': $("#recipe_row_" + i + " [name=cil]").val(),
            'eje': $("#recipe_row_" + i + " [name=eje]").val(),
            'prisma': $("#recipe_row_" + i + " [name=prisma]").val(),
            'disInt': $("#recipe_row_" + i + " [name=disInt]").val()
        }));
    }
    var data = {
        client_id: $("#clients_list").val(),
        oculist_id: $("#oculist_list").val(),
        date: formattedDate(date),
        is_bps: $('input[name=bpsRadio]:checked').val(),
        observations: $("#observations").val(),
        recipe_datas: recipe_data
    };
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: WEB_PATH + 'recipe',
        headers: {'apikey': USER_CLIENT},
        data: data,
        success: function (data) {
            loadOculist();
        },
        error: function () {
            alert("Error al agregar oculista");
        }
    });
}