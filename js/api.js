$(document).ready(function() {
    $.ajaxSetup ({ cache: false });
    var wwwroot = $('body').data('wwwroot');
    var ui = new UI(wwwroot);
    $('#main-public').on('click', function(e) {
        e.preventDefault();
        ui.showQuestionnaires();
    });
});

var UI = function(wwwroot) {
    var content = $('#content');
    const questionnaires = $('<div></div>').load(wwwroot + '/html/questionnaires.html', function() {
        showQuestionnaires();
    });

    this.showQuestionnaires = showQuestionnaires;

    function showQuestionnaires() {
        content.empty();
        content.append(questionnaires.clone());
        content.find('.date-ui').datepicker({ dateFormat: 'yy-mm-dd' }, $.datepicker.regional['ru']);
        var stage = 1;
        var prevbtn = content.find('.qnprev');
        var nextbtn = content.find('.qnnext');
        var savebtn = content.find('.qnsave');
        prevbtn.on('click', function() {
            if (stage === 1) return;
            nextbtn.prop('disabled', false);
            if (--stage === 1) {
                prevbtn.prop('disabled', true);
            }
            content.find('#success').remove();
            showQuestionnairesStage(stage);
        });
        nextbtn.on('click', function() {
            if (stage === 4) return;
            prevbtn.prop('disabled', false);
            if (++stage === 4) {
                nextbtn.prop('disabled', true);
            }
            content.find('#success').remove();
            showQuestionnairesStage(stage);
        });
        showQuestionnairesStage(1);
        prevbtn.prop('disabled', true);
        function showQuestionnairesStage(stage) {
            if (stage < 1 || stage > 4) return;
            for (var i = 1; i <= 4; i++) {
                s = content.find('#questionnaires' + i);
                if (stage === i) {
                    s.show();
                } else {
                    s.hide();
                }
            }
        }
        savebtn.on('click', function(e) {
            e.preventDefault();
            var form = $(e.target).closest('form');
            var formdata = new FormData(form[0]);
            content.find('#errors').remove();
            $.ajax({
                type: 'post',
                url: wwwroot + '/actions.php?action=add',
                data: formdata,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (data) {
                    if (data != true) {
                        var message = '';
                        var errors = 'При отправке анкеты были обнаружены ошибки:<ul>';
                        if (data.sex) {
                            errors += '<li>' + 'вы не указали пол' + '</li>';
                        }
                        if (data.lastname) {
                            errors += '<li>' + 'вы не указали фамилию' + '</li>';
                        }
                        if (data.birth) {
                            errors += '<li>' + 'вы не указали дату рождения' + '</li>';
                        }
                        if (data.color) {
                            errors += '<li>' + 'ваш любимый цвет был указан некорректно' + '</li>';
                        }
                        if (data.skills) {
                            errors += '<li>' + 'вы не указали навыки' + '</li>';
                        }
                        if (data.avatar) {
                            message = data.avatar == 'noimage' ? 'аватар не является изображением' : 'максимальный размер аватара равен 100кб';
                            errors += '<li>' + message + '</li>';
                        }
                        if (data.photos) {
                            message = data.photos == 'noimage' ? 'одна из фотографий не является изображением' : 'максимальный размер фотографии равен 5мб';
                            errors += '<li>' + message + '</li>';
                        }
                        errors += '</ul>';
                        form.prepend('<div id=\'errors\' class=\'alert alert-danger\'>' + errors + '</div>');
                    } else {
                        showQuestionnaires();
                        content.find('form').prepend('<div id=\'success\' class=\'alert alert-success\'>Ваша анкета была успешно отправлена</div>');
                    }
                },
                error: function () { },
                complete: function () { }
            });
        });
        content.find('.colordialog').on('click', function() {
            var color = $(this).css('background-color');
            content.find('.selectedcolor').css('background-color', color);
            content.find('input[name=color]').val(color);
            content.find('#colordialog').modal('toggle');
        });
        var photo = 1;
        content.find('.addphotobtn').on('click', function() {
            content.find('.photos').append('<input type=\'file\' name=\'photo'+ ++photo + '\'>');
            if (photo === 5) {
                content.find('.addphotobtn').hide();
            }
        });
    }
};
