$(document).ready(function () {
    loadClients();
}).on('click', '#new_client', function (event) {
    event.preventDefault();
    addNewClient();
}).on('click', '#edit_client', function (event) {
    event.preventDefault();
    editClient($("#form_client [name=id]").val());
}).on('click', '[data-edit-client]', function (event) {
    event.preventDefault();
    loadClientById($(this).attr('data-edit-client'));
}).on('click', '#clean_form_client', function (event) {
    if ($("#form_client [name=id]").val() != "") {
        $("#form_client [name=id]").val("");
        $("#new_client").show();
        $("#edit_client").hide();
    }
});
function loadClients() {
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: WEB_PATH + 'clients',
        headers: {'apikey': USER_CLIENT},
        success: function (data) {
            Mustache.tags = ["[[", "]]"];
            $("#table_clients").html("");
            $("#table_clients").html(Mustache.to_html($("#row_table_clientes").html(), {'clients': data}));
            $('#dataTables-example').DataTable({
                responsive: true
            });
        },
        error: function () {
            alert("Error al cargar los clientes")
        }
    });
}

function loadClientById(id) {
    $.ajax({
        type: 'GET',
        dataType: 'JSON',
        url: WEB_PATH + 'clients/' + id,
        headers: {'apikey': USER_CLIENT},
        success: function (data) {
            jQuery.each($("#form_client").serializeArray(), function (i, field) {
                $("#form_client [name=" + field.name + "]").val(data[field.name]);
            });
            $("#new_client").hide();
            $("#edit_client").show();
        },
        error: function () {
            alert("Error al cargar los clientes")
        }
    });
}

function addNewClient() {
    data = [];
    has_error = false;
    jQuery.each($("#form_client").serializeArray(), function (i, field) {
        if (field.name != 'telephone' && field.name != 'cellphone' && field.name != 'id') {
            if (field.value == '') {
                $("#form_client [name=" + field.name + "]").parents('.form-group').addClass('has-error');
                has_error = true;
            } else {
                $("#form_client [name=" + field.name + "]").parents('.form-group').removeClass('has-error');
            }
        }
    });
    if (!has_error) {
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: WEB_PATH + 'clients',
            headers: {'apikey': USER_CLIENT},
            data: {
                ci: $("#form_client [name=ci]").val(),
                name: $("#form_client [name=name]").val(),
                lastname: $("#form_client [name=lastname]").val(),
                telephone: $("#form_client [name=telephone]").val(),
                cellphone: $("#form_client [name=cellphone]").val(),
                birthdate: formattedDate($("#form_client [name=birthdate]").datepicker("getDate"))
            },
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("Error al cargar los clientes")
            }
        });
    }
}

function editClient(id) {
    data = [];
    has_error = false;
    jQuery.each($("#form_client").serializeArray(), function (i, field) {
        if (field.name != 'telephone' && field.name != 'cellphone') {
            if (field.value == '') {
                $("#form_client [name=" + field.name + "]").parents('.form-group').addClass('has-error');
                has_error = true;
            } else {
                $("#form_client [name=" + field.name + "]").parents('.form-group').removeClass('has-error');
            }
        }
    });
    if (!has_error) {
        var date = $("#form_client [name=birthdate]").datepicker("getDate");
        if (date == null) {
            alert("Indique una fecha");
            return false;
        }
        $.ajax({
            type: 'PUT',
            dataType: 'JSON',
            url: WEB_PATH + 'clients/' + id,
            headers: {'apikey': USER_CLIENT},
            data: {
                ci: $("#form_client [name=ci]").val(),
                name: $("#form_client [name=name]").val(),
                lastname: $("#form_client [name=lastname]").val(),
                telephone: $("#form_client [name=telephone]").val(),
                cellphone: $("#form_client [name=cellphone]").val(),
                birthdate: formattedDate(date)
            },
            success: function (data) {
                location.reload();
            },
            error: function () {
                alert("Error al cargar los clientes")
            }
        });
    }
}
