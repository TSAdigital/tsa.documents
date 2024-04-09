let crypto = new CryptoHelper();

function update(data) {
    crypto.init().then(async () => {
        let certificates = await crypto.getCertificates();
        var select = document.getElementById(data);
        select.options.length = 1;
        certificates.forEach(function (cert, index) {
            var el = document.createElement("option");
            el.textContent = cert.subject.name;
            el.value = index;
            select.appendChild(el);
        });
    }).catch(() => {
        // пользователь отклонил запрос
    });
}

function sign() {
    crypto.init().then(async () => {
        let url_string = window.location.href;
        let url = new URL(url_string);
        let id = url.searchParams.get("id");
        let certificates = await crypto.getCertificates();
        let e = document.getElementById("select-sign");
        let value = Number(e.value);
        if(value && certificates[value]){
            let sign = await crypto.signString(certificates[value].$original, id);
            signSave(sign, id);
        }
    }).catch(() => {
        // пользователь отклонил запрос
    });
}

function signSave(sign, id) {
    $.ajax({
        url: '/document/sign',
        type: 'POST',
        data: {'sign' : sign, 'id' : id},
        success: function(){
            $('#sign').modal('hide');
            window.location.href = "#event";
            location.reload();
        },
        error: function(){
            alert('Не удалось подписать документ!');
        }
    });
}

function signInfo(id) {
    $.ajax({
        url: '/document/sign-info',
        type: 'POST',
        data: {'id' : id},
        success: async function (res) {
            const object = JSON.parse(res);
            let data = object.id;
            let sign = object.sign;
            let signInfo = await crypto.verify(data, sign, true);
            if (!signInfo) {
                document.getElementById('sign-info').innerHTML = 'Не удалось загрузить подпись';
            } else {
                signInfo.forEach(function (sign) {
                    document.getElementById('sign-info').innerHTML = `<div style="width: 100%; border: 2px solid blue; padding: 15px; font-size: 14px; margin: 0 auto"><b style="color: blue; text-align: center; font-size: 16px">Документ подписан электронной подписью</b> <br> Владалец: ${sign.cert.subject.name} <br> Сертификат: ${sign.cert.serialNumber} <br> Действителен с ${convertDate(new Date(sign.cert.validFrom))} по ${convertDate(new Date(sign.cert.validTo))}</div>`;
                });
            }
        },
        error: function(){
            alert('Не удалось загрузить подпись!');
        }
    });
}

function fileSign(id, sign){
    let data = {
        id: id,
        sign: sign,
        _csrf: yii.getCsrfToken()
    };
    $.ajax({
        url: '/document/sign-file',
        type: 'POST',
        data: data,
        success: function(){
            $('#sign').modal('hide');
            window.location.href = "#file";
            location.reload();
        },
        error: function(){
            alert('Не удалось подписать файл!');
        }
    });
}

async function singFile(id) {
    crypto.init().then(async () => {
        let data = {
            id: id,
            _csrf: yii.getCsrfToken()
        };
        $.ajax({
            url: '/document/get-file-url',
            type: 'POST',
            data: data,
            success: async function (res) {
                let file_url = res
                if (file_url) {
                    let data = await fetch(file_url).then(r => r.blob());
                    let certificates = await crypto.getCertificates();
                    let e = document.getElementById("select-file-sign");
                    let value = Number(e.value);
                    if (value && certificates[value]) {
                        let signs = await crypto.signFile(certificates[value].$original, new Blob([data]));
                        fileSign(id, signs)
                    }
                }
            },
            error: function(){
                alert('Не удалось получить URL файла!');
            }
        });
    }).catch(() => {
        // пользователь отклонил запрос
    });
}

function convertDate(date) {
    var day = date.getDate();
    day = day < 10 ? "0" + day : day;
    var month = date.getMonth() + 1;
    month = month < 10 ? "0" + month : month;
    var year = date.getFullYear();
    return day + "." + month + "." + year;
}