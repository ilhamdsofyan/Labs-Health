<?php 
	$jsonListGroup = json_encode($listGroup);
?>

<script src="<?= base_url('web/assets/plugins/jquery-nestable/jquery.nestable.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
	var list_group = <?= $jsonListGroup ?>;

	$(document).ready(function(){
	    $('.dd').nestable({ /* config options */ });
	    var dataId;
	    var updateOutput = function(e){
	        var list = e.length ? e : $(e.target),
	            output = list.data('output');
	        if (window.JSON) {
	            var parent_id = $('#nestable_ol').attr('data-id');
	            var list_serialz = list.nestable('serialize');
	            list_serialz[0].parent = parseInt(parent_id);
	            var tmp = window.JSON.stringify(list_serialz);
	            AjaxCall('<?= base_url('rbac/menu/update-menu') ?>', {menus: JSON.parse(tmp)});
	        }else{
	            console.log('JSON browser support');
	        }
	    };
	    $('#nestable').nestable();
	    $('#nestable').on('change', function(event){
	        if (!event.bubbles)
	            updateOutput($('#nestable').data('output', $('#nestable-output')));
	    });
	});

	var AjaxCall = function(url, data){
	    $('#img-ajax-loader').fadeIn();
	    $.ajax({
	        url : url,
	        type : 'POST',
	        data : data,
	        dataType : 'json',
	        success : function(result){
	            $('#img-ajax-loader').hide();
	        }
	    });
	}

	$(document).on('click', '#route-select', function(){
	    if ($(this).text() == 'Select All') {
	        $('.route').prop('checked', true);
	        $(this).text('Deselect All');
	    } else {
	        $('.route').prop('checked', false);
	        $(this).text('Select All');
	    }
	});

	function randomIt(id){
	    var num = Math.floor((Math.random() * 100) + 1);
	    if($('#'+id+'-'+num).length){
	        return randomIt(id);
	    }else{
	        return id+'-'+num;
	    }
	}

	$(document).on('click','#add-group-to-menu',function(){
	    $('.route:checked').each(function() {
	        var id = randomIt('item' + $(this).data('val').replace(/[/]/g, '-'));
	        var label = $(this).data('val');
	        appendMenu(id, label, label);
	        $('.dd').trigger('change');
	    });
	});

	$(document).on('click', '#add-custom-to-menu', function(){
	    var id = randomIt('custom');
	    var url = $(this).parent().parent().parent().find('#url').val();
	    var label = $(this).parent().parent().parent().find('#label').val();
	    appendMenu(id, url, label);
	    $('.dd').trigger('change');
	});

	checkboxGroup();

	function checkboxGroup(no) {
	    var result = '';
	    result += '<div id=\'group\'>';
	    for (var key in list_group) {
	        if (list_group.hasOwnProperty(key)) {
	            result += '<div class=\'checkbox\'>';
	            result += '<label>';
	            result += '<input type=\'checkbox\' name=\'Menu[item][' + no + '][group][]\' value=\'' + list_group[key].id + '\' class=\'form-check-input\' />';
	            result += list_group[key].label;
	            result += '</label>';
	            result += '</div>';
	        }
	    }
	    result += '</div>';
	    return result;
	}

	function appendMenu(id, url, label) {
	    var no = $('li.dd-item').length;
	    $('.dd > .dd-list').append(
	        '<li class=\'dd-item\' data-id=\'' + id + '\'>' +
	            '<button type=\'button\' role=\'button\' class=\'float-end btn btn-success rounded-0 float-end\' aria-hidden=\'true\' data-bs-toggle=\'collapse\' href=\'#item-menu-'+ id +'\' aria-expanded=\'true\' aria-controls=\'item-menu-'+ id +'\'></button>' +
	            '<div class=\'dd-handle\'>' +
	                '<div class=\'card card-collapsable bg-success\'>' +
	                    '<div class=\'handle-head card-header text-white\'>'+ label + '<span class=\'float-end\'><i>(' + url + ')</i></span> </div>' +
	                    '<div class=\'collapse dd-nodrag\' id=\'item-menu-' + id + '\' role=\'tabpanel\' aria-labelledby=\'' + id + '\'>' +
	                            '<div class=\'card card-light\'>' +
	                            '<div class=\'card-body\'>' +
	                                '<div class=\'mb-3\'>' +
	                                    '<label>Menu Label</label>' +
	                                    '<input type=\'text\' class=\'form-control input-sm\' name=\'Menu[item][' + no + '][label]\' value=\'' + label + '\' placeholder=\' Menu Label \'>' +
	                                '</div>' +
	                                '<div class=\'mb-3\'>' +
	                                    '<label>Description</label>'+
	                                    '<input type=\'text\' class=\'form-control input-sm\' name=\'Menu[item][' + no + '][description]\' placeholder=\' Menu  Description \'>' +
	                                '</div>' +
	                                '<div class=\'mb-3\'>' +
	                                    '<label>URL</label>' +
	                                    '<input type=\'text\' class=\'form-control input-sm\' name=\'Menu[item][' + no + '][menu_url]\' value=\'' + url + '\' placeholder=\' URL \'>' +
	                                '</div>' +
	                                '<div class=\'mb-3\'>' +
	                                    '<label>Icon Class</label>' +
	                                    '<input type=\'text\' class=\'form-control input-sm\' name=\'Menu[item][' + no + '][class]\' placeholder=\' Icon Class \'>' +
	                                '</div>' +
	                                '<div class=\'mb-3\'>' +
                                        '<label>Group</label>'+
                                        checkboxGroup(no) +
	                                '</div>' +
	                                '<div class=\'col-md-12\'>' +
	                                    '<a href=\'javascript:void(0)\' aria-hidden=\'true\' data-toggle=\'collapse\' href=\'#' + id + '\' aria-expanded=\'true\' aria-controls=\'' + id + '\' class=\'text-danger remove-menu\' data-id=\'\' >Remove</a>' +
	                                    ' | ' +
	                                    '<a aria-hidden=\'true\' data-toggle=\'collapse\' href=\'#' + id + '\' aria-expanded=\'true\' aria-controls=\'' + id + '\' class=\'text-muted\'>Cancel</a>' +
	                                '</div>' +
	                            '</div>' +
	                        '</div>' +
	                    '</div>' +
	                '</div>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][menu_parent]\' class=\'hasparent\' value=\'\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][dummy_id]\' class=\'dummy-id\' value=\'\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][menu_custom]\' value=\'1\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][menu_id]\' value=\'\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][menu_order]\' class=\'menu-order\' value=\'\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][level]\' class=\'level\' value=\'\'>' +
	            '</div>'+
	            '<ol></ol>'+
	        '</li>');
	}

	function loopChildren(serialmenu, idParent, level) {
	    $.each(serialmenu, function(a,b) {
	        ha = $('[data-id='+b.id+'] > .dd-handle').children('.hasparent').val(idParent);
	        $('[data-id='+b.id+'] > .dd-handle').children('.dummy-id').val(b.id);
	        $('[data-id='+b.id+'] > .dd-handle').children('.menu-order').val(a);
	        $('[data-id='+b.id+'] > .dd-handle').children('.level').val(level);
	        if (b.children) {
	            loopChildren(b.children, b.id, (level+1));
	        }
	    });
	}

	$(document).on('change','.dd',function(){
	    serialmenu = $('.dd').nestable('serialize');
	        return loopChildren(serialmenu, '0',1);
	});

	$(document).on('click','.remove-menu',function(e){
	    e.preventDefault();
	    var conf = confirm('Yakin akan dihapus?');
	    if (conf) {
	        id = $(this).data('id') ? $(this).data('id') : 0;
	        type = $(this).attr('menu-type');
	        child = $(this).parent().parent().parent().parent().parent().parent().parent().children('ol').html();
	        $(child).insertAfter('[data-id=' + id + ']');
	        $(this).parent().parent().parent().parent().parent().parent().parent().remove();
	        populateDeleted(id);
	        $('.dd').trigger('change');
	    }
	});

	$(document).on('click','.btn-save-menu',function(e){
	    $('#formMenu').submit()
	});

	// Default options
	IconPicker.Init({
		// Required: You have to set the path of IconPicker JSON file to "jsonUrl" option. e.g. '/content/plugins/IconPicker/dist/iconpicker-1.5.0.json'
		jsonUrl: '<?= base_url("/web/assets/plugins/fontawesome/iconpicker-1.5.0.json") ?>',
		// Optional: Change the buttons or search placeholder text according to the language.
		searchPlaceholder: 'Search Icon',
		showAllButton: 'Show All',
		cancelButton: 'Cancel',
		noResultsFound: 'No results found.', // v1.5.0 and the next versions
		borderRadius: '20px', // v1.5.0 and the next versions
	});

	IconPicker.Run('#GetIconPicker');

	var delay = (function(){
	    var timer = 0;
	    return function(callback, ms){
	        clearTimeout (timer);
	        timer = setTimeout(callback, ms);
	    };
	})();

	function populateDeleted(id) {
	    var form = $('#formMenu');
	    var field = '<input type=\'hidden\' name=\'Menu[remove][]\' value=\'' + id + '\' />';
	    if (id != '') {
	        form.append(field);
	    }
	}

	$('.search-list-menu[data-target]').keyup(function(){
	    searchRoute($(this).data('target'));
	});

	function searchRoute(target){
	    var $routes = $('#list-routes');
	    $routes.html('');

	    var keyword = $('.search-list-menu').val();
	    $.each(_opts.routes, function(){
	        var r = this;
	        if (r.indexOf(keyword) >= 0) {
	            $('<label class="col-sm-12">'+
	                   '<input type="checkbox" class="route" data-val="'+ r +'" name="route[]" value=""> '+ r +
	                '</label>'
	            ).appendTo($routes);
	        }
	    });
	}
</script>