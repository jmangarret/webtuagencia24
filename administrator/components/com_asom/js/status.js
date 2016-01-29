jQuery.noConflict();

jQuery(document).ready(function(){
    var $ = jQuery, _remove = true, time = 0, data;
    data = {
        'title'   : $('#_status .tit').text(),
        'title2'  : $('#_status .tit2').text().split('\\;'),
        'statuses': (function(){
            var st = $('#_status .data').text(), ar1, ar2, result = {};
            ar1 = st.split('\\:');
            for(var i = 0, l = ar1.length; i < l; i++){
                ar2 = ar1[i].split('\\;');
                result['c' + ar2[0]] = {
                    'id'   : ar2[0],
                    'color': ar2[1],
                    'name' : ar2[2]
                };
            }
            return result;
        })()
    };

    $('#_status').remove();

    /**
     * Funcion que crea la ventana de cambio de estado.
     */
    var windowStatus = function(el, nst){
        var ht = '<div id="w-change-status">', sts = $('#filter_status').clone(true);

        ht += '<div class="container">';
        ht += '<table>';
        ht += '<tr>';
        ht += '<td class="key">' + data.title2[0] + '</td><td>' + el.find('td a[href]').text() + '</td>';
        ht += '<td class="key">' + data.title2[1] + '</td><td>' + el.find('td.customer').text() + '</td>';
        ht += '</tr>';
        ht += '<tr>';
        ht += '<td class="key">' + data.title2[2] + '</td><td class="nsts"></td>';
        ht += '<td class="key">' + data.title2[3] + '</td><td>' + data.statuses['c' + el.find('.choose-color div').attr('class').substr(1)].name + '</td>';
        ht += '</tr>';
        ht += '<tr>';
        ht += '<td colspan="4"><textarea name="description" id="description"></textarea></td>';
        ht += '</tr>';
        ht += '</table>';
        ht += '<div class="buttons">';
        ht += '<input type="button" class="cancel" value="' + data.title2[4] + '"/>';
        ht += '<input type="button" class="save" value="' + data.title2[5] + '"/>';
        ht += '</div></div></div>';

        $('<div id="overlay"></div>').appendTo(document.body);

        var _fnRemove = function(){
            $('#w-change-status, #overlay').fadeOut(300, function(){
                $(this).remove();
            });
        };

        ht = $(ht);
        $(window).scrollTop(0);
        $(document.body).append(ht);
        sts.attr('onchange', null).attr('id', 'st_status').val(nst);
        ht.find('td.nsts').append(sts);
        $('#overlay').click(_fnRemove);
        ht.find('.cancel').click(_fnRemove);
        ht.find('.save').click(function(){
            if($('#description').val().trim() == ''){
                alert('Error: La nota es obligatoria.');
                ht.find('textarea').focus();
                return;
            }
            var _v = $('#st_status').val();
            $('form[name=adminForm] input[type=checkbox]').attr('checked', false);
            $('form[name=adminForm]').find('input[name=status]').val(_v);
            $('form[name=adminForm]').find('input[name=note]').val($('#description').val());
            el.find('input[name^=cid]').attr('checked', true);
            Joomla.submitform('orders.status');
        });

        ht.css({
            opacity: 0,
            top    : '-300px'
        }).animate({
            opacity: 1,
            top    : '-1px'
        }, 500);

        ht.find('textarea').focus();
    }

    /**
     * Muestra el tooltip al posicionar el mouse sobre el div
     */
    $('.choose-color.action div').mouseenter(function(){
        var _tr = $(this).parents('tr');

        $('.change-status').remove();
        window.clearTimeout(time);

        var el = $(this), offset = el.offset(), _w = 0, _h = 0, id = 0;
        var ht  = '<div class="change-status">';

        ht += '<p>' + data.title + '</p>';
        ht += '<ul>';

        id = el.attr('class').substr(1);
        for(d in data.statuses)
        {
            if(id == data.statuses[d].id){
                var color = parseInt(data.statuses[d].color.substr(1), 16) > 0xFFFFFF / 2 ? '#000' : '#FFF';
                ht += '<li class="c' + data.statuses[d].id + '" style="background: ' + data.statuses[d].color + '; color: ' + color+ ';"><span style="background: ' + data.statuses[d].color + '"></span>' + data.statuses[d].name + '</li>';
            }else
                ht += '<li class="c' + data.statuses[d].id + '"><span style="background: ' + data.statuses[d].color + '"></span>' + data.statuses[d].name + '</li>';
        }

        ht += '</ul>';
        ht += '</div>';

        ht = $(ht);
        $(document.body).append(ht);

        ht.mouseenter(function(){
            _remove = false;
        }).mouseleave(function(){
            _remove = true;
            $('.change-status').remove();
        });

        $('.change-status li:not(.c' + id + ')').click(function(){
            windowStatus(_tr, $(this).attr('class').substr(1));
        });

        _w = ht.outerWidth(true);
        _h = ht.outerHeight(true);

        ht.css({
            opacity: 0,
            top    : (offset.top - (_h / 2)) + 'px',
            left   : (offset.left - _w - 40) + 'px'
        }).animate({
            opacity: 1,
            left   : (offset.left - _w - 15) + 'px'
        }, 400);

    });


    /**
     * Desaparece el tooltip al quitar el mouse del div, y ponerlo en otra parte
     * diferente al tooltip
     */
    $('.choose-color div').mouseleave(function(){
        window.clearTimeout(time);
        var fn = function(){
            if(_remove)
                $('.change-status').remove();
        }
        time = window.setTimeout(fn, 500);
    });
});
