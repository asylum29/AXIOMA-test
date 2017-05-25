$(document).ready(function() {
    $.ajaxSetup ({ cache: false });
    var wwwroot = $('body').data('wwwroot');
    var ui = new UI(wwwroot);
});

var UI = function(wwwroot) {
    var content = $('#content');
    var questionnaires = $('<div></div>').load(wwwroot + '/html/questionnaires.html', function() {
        showQuestionnaires();
    });

    this.showQuestionnaires = showQuestionnaires;

    function showQuestionnaires() {
        content.empty();
        content.append(questionnaires);
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
            showQuestionnairesStage(stage);
        });
        nextbtn.on('click', function() {
            if (stage === 4) return;
            prevbtn.prop('disabled', false);
            if (++stage === 4) {
                nextbtn.prop('disabled', true);
            }
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
        savebtn.on('click', function(e) { // Проблема с отправкой файлов!
            e.preventDefault();
            $('#errors').remove();
            var form = $(e.target).closest('form');
            $.ajax({
                url: wwwroot + '/actions.php?action=add',
                timeout: 60000,
                data: form.serialize(),
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    if (data != true) {
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
                        if (data.skills) {
                            errors += '<li>' + 'вы не указали навыки' + '</li>';
                        }
                        if (data.avatar) {
                            errors += '<li>' + 'вы не указали аватар' + '</li>';
                        }
                        if (data.photos) {
                            errors += '<li>' + 'вы не указали фотографии' + '</li>';
                        }
                        errors += '</ul>';
                        form.prepend('<div id=\'errors\' class=\'alert alert-danger\'>' + errors + '</div>');
                    }
                },
                error: function () {

                },
                complete: function () {

                }
            });
        });
        content.find('.colordialog').on('click', function() {
            var color = $(this).css('background-color');
            content.find('.selectedcolor').css('background-color', color);
            content.find('input[name=color]').val(color);
            content.find('#colordialog').modal('toggle');
        });
        var photos = 1;
        content.find('.addphotobtn').on('click', function() {
            content.find('.photos').append('<input type=\'file\' name=\'photos[]\'>');
            if (++photos === 5) {
                content.find('.addphotobtn').hide();
            }
        });
    }
};
