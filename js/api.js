$(document).ready(function() {
    //$.ajaxSetup ({ cache: false });
    var wwwroot = $('body').data('wwwroot');
    var ui = new UI(wwwroot);
    $('#main-public').on('click', function(e) {
        e.preventDefault();
        ui.showQuestionnaires();
    });
    $('#main-admin').on('click', function(e) {
        e.preventDefault();
        ui.showListTable();
    });
});

var UI = function(wwwroot) {
    var content = $('#content');
    var questionnaires, questionform;
    var authform = $('<div></div>').load(wwwroot + '/html/authform.html', loadList);
    function loadList() {
        questionnaires = $('<div></div>').load(wwwroot + '/html/questionnaires.html', loadQuestionnaires);
    }
    function loadQuestionnaires() {
        questionform = $('<div></div>').load(wwwroot + '/html/questionsform.html', showQuestionnaires);
    }

    this.showQuestionnaires = showQuestionnaires;
    this.showAuthForm = showAuthForm;
    this.showListTable = showListTable;

    function showQuestionnaires() {
        content.empty();
        content.append(questionform.clone());
        content.find('.date-ui').datepicker({ format: 'yyyy-mm-dd', language: 'ru' });
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
                            message = data.photos == 'noimage' ? 'одна из фотографий не является изображением' : 'максимальный размер фотографии равен 1мб';
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

    function isAdmin(isfalse, istrue) {
        $.ajax({
            type: 'get',
            url: wwwroot + '/actions.php?action=isadmin',
            dataType: 'json',
            success: function (data) {
                if (data != true) {
                    isfalse();
                } else {
                    istrue();
                }
            }
        });
    }

    function showAuthForm() {
        content.empty();
        isAdmin(function() {
            content.append(authform.clone());
            content.find('input[type=submit]').on('click', function(e) {
                e.preventDefault();
                var form = $(e.target).closest('form');
                var formdata = new FormData(form[0]);
                content.find('#errors').remove();
                $.ajax({
                    type: 'post',
                    url: wwwroot + '/actions.php?action=auth',
                    data: formdata,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data) {
                        if (data != true) {
                            form.prepend('<div id=\'errors\' class=\'alert alert-danger\'>Неверный пароль</div>');
                        } else {
                            showListTable();
                        }
                    }
                });
            });
        },
            showListTable
        );
    }

    function showListTable() {
        content.empty();
        isAdmin(showAuthForm, function() {
            content.append(questionnaires.clone());
            content.find('.filterbtn').on('click', function(e) {
                //e.preventDefault();
                getData($(e.target).closest('form').serialize());
            });
            content.find('.sortlink').on('click', function(e) {
                e.preventDefault();
                content.find('.sortlink span').text('');
                var target = $(e.target);
                target.find('span').text('^');
                var form = content.find('form');
                form.find('input[name=sort]').val(target.data('sort'));
                getData(form.serialize());
            });
            content.find('.retbtn').on('click', function(e) {
                e.preventDefault();
                content.find('#element').hide();
                content.find('#elements').show();
            });
            getData();
        });
        function getData(formdata) {
            $.ajax({
                type: 'get',
                url: wwwroot + '/actions.php?action=getlist',
                data: formdata,
                dataType: 'json',
                success: elementsReceived
            });
        }
        function elementsReceived(data) {
            if (!data.noauth) {
                content.find('#contentlist tr').remove();
                var template = content.find('#listtmpl').html();
                var rendered = Mustache.render(template, {
                    'list': data,
                    'formatdate': formatDate,
                    'formatempty': formatEmpty
                });
                content.find('#contentlist').append(rendered);
                content.find('.more').on('click', function(e) {
                    var target = $(e.target);
                    $.ajax({
                        type: 'get',
                        url: wwwroot + '/actions.php?action=getelement&id=' + target.data('id'),
                        dataType: 'json',
                        success: elementReceived
                    });
                });
            } else {
                showAuthForm();
            }
        }
        function elementReceived(data) {
            if (!data.noauth) {
                if (data != false) {
                    content.find('#elements').hide();
                    content.find('#element').show();
                    content.find('#contentelement tr').remove();
                    var template = content.find('#elementtmpl').html();
                    var rendered = Mustache.render(template, {
                        'element': data,
                        'formatdate': formatDate,
                        'formatsex': formatSex,
                        'formatimage': formatImage,
                        'formatempty': formatEmpty
                    });
                    content.find('#contentelement').append(rendered);
                }
            } else {
                showAuthForm();
            }
        }
        function formatDate() {
            return function(date, render) {
                var d = new Date(render(date));
                var options = {
                    year: 'numeric',
                    month: 'numeric',
                    day: 'numeric'
                };
                return d.toLocaleString('ru', options);
            }
        }
        function formatSex() {
            return function(value, render) {
                var v = render(value).trim();
                return v == 'm' ? 'мужской' : 'женский';
            }
        }
        function formatImage() {
            return function(value, render) {
                var v = render(value).trim();
                return '<img src="' + wwwroot + '/actions.php?action=getfile&id=' + v + '" />';
            }
        }
        function formatEmpty() {
            return function(value, render) {
                var v = render(value).trim();
                return v == '' ? '—' : v;
            }
        }
    }
};
