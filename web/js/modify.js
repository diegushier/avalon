var createEmpForm =
    `<section>
    <h2>Crear empresa</h2>
    <form>
        <div class='form-group'>
            <label for='nombreEmp'>Nombre de la empresa</label>
            <input type='text' class='form-control' id='nombreEmp' aria-describedby='nombreInfo'>
            <small id='nombreInfo' class='form-text text-muted'>
                Este será el nombre que lo identifique como empresa.
            </small>
        </div>
        <div class='form-group'>
            <label for='paisEmp'>Pais</label>
            <select class='form-control' id='paisEmp'>
                <option>Eliga un país</option>
            </select>
        </div>
        <a class='btn btn-dark text-white' id='sendata'>Crear</a>
    </form>
</section>`;

var authForm = `
<section>
    <h2>Confirmación de usuarios</h2>
    <p>Esto no es más que una medida de seguridad.</p>
    <form>
        <div class="form-group">
            <label for="claveEmp">Clave</label>
            <input type="text" class="form-control" id="claveEmp" aria-describedby="claveInfo" placeholder="Ingresa la clave que ha sido envada a tu correo">
            <small id="claveInfo" class="form-text text-muted">
                Para tener acceso a la creación de una empresa, primero debe confirmar su cuenta mediante la clave que le fué enviada a su correo.
            </small>
        </div>
        <a class="btn btn-dark text-white" id="senclave">Enviar</a>
    </form>
</section>`;

var user;
var urlUserAuth;
var urlSend;
var urlEmpSend;
var urlPais;


var forms = {
    'authForm': authForm,
    'createEmpForm': createEmpForm,
}

var hrefUser = $('#comfirmDeleteUser').attr('href');

$('#comfirmDeleteUser').removeAttr('href');

$('#deleteUser').change(() => {
    if ($('#deleteUser').val() === nombre) {
        $('#comfirmDeleteUser').attr('href', hrefUser);
    } else {
        $('#comfirmDeleteUser').removeAttr('href');
    }
})

arr = {
    '#modificar-imagen': '#setImg'
}

$.each(arr, (k, v) => {
    $(k).change(function () {
        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == 'gif' || ext == 'png' || ext == 'jpeg' || ext == 'jpg')) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(v).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            $(v).attr('src', '/assets/no_preview.png');
        }
    });
})

function sendClave(id, url, user) {
    $(id).click(() => {
        $.ajax({
            method: 'GET',
            url: url,
            data: {
                id: user,
                clave: $('#claveEmp').val(),
            },
            success: function (data) {
                showForm('create');
                sendEmpData('#sendata', urlEmpSend, user)
                getPaises(urlPais);
            },
        })
    });
}

function getPaises(url) {
    $.ajax({
        method: 'GET',
        url: url,
        data: {},
        success: function (data) {
            var aux = data,
                k = 0;
            while (k < aux.length) {
                $('#paisEmp').append(
                    "<option value='" + (k + 1) + "'>" + aux[k].nombre + "</option>"
                )
                k++;
            }
        },
    })
}

function sendEmpData(id, url, user) {
    $(id).click(() => {
        $.ajax({
            method: 'GET',
            url: url,
            data: {
                id: user,
                name: $('#nombreEmp').val(),
                pais_id: $('#paisEmp').val(),
            },
            success: function (data) {
                console.log('Empresa = ' + data)
            },
        })
    })
}

function showForm(opc) {
    $('#formEmp').empty();

    switch (opc) {
        case 'auth':
            $('#formEmp').append(forms['authForm']);
            sendClave('#senclave', urlSend, user);
            break;
        case 'create':
            $('#formEmp').append(forms['createEmpForm']);
            break;
        default:
            console.log('Fail forms')
    }
}

function getUser(id, url) {
    $.ajax({
        method: 'GET',
        url: url,
        data: {
            entidad: id,
        },
        success: function (data) {
            if (data) {
                console.log(data)
            } else {
                showForm('auth');
            }
        },
    })
}

function setter(id, url1, url2, url3, url4) {
    user = id;
    urlUserAuth = url1;
    urlSend = url2;
    urlEmpSend = url3;
    urlPais = url4;
}

