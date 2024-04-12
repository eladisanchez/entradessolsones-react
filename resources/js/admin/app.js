
//require('./vendor/bootstrap-datepicker.js');
//require('./vendor/jquery.timepicker.js');

$(function() {

	tinymce.init({
    	selector: "textarea:not(.raw)",
    	// plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode tableofcontents typography inlinecss',
        plugins: 'link code',
        menubar: false,
    	toolbar1: "undo redo | bold italic underline strikethrough backcolor forecolor | styleselect formatselect | bullist numlist | link unlink code",
        content_css : "/css/tinymce.css",
        relative_urls : false,
        language_url : '/js/vendor/tinymce/languages/ca.js'
 	});


    // Editor d'espais

    if($("#drawing-table").length) {

        var tableMarkup = "";
        // var rows = 32;
        // var cols = 32;
        var rows = 45;
        var cols = 45;
        var mouseDownState = false;
        var eraseState = false;
        var parells = 1;

        var guardat = [];

        $("#fila").on('change',function() {
            $("#seient").val(1);
        });

        $("#parells").on('change',function() {

            if ($("#parells").is(":checked")) {
                parells = 2;
            } else {
                parells = 1;
            }
            
        });

        $("#check-escenari").on('change',function() {
            if ($("#check-escenari").is(":checked")) {
                $(".escenari").show();
            } else {
                $(".escenari").hide();
            }
        });
        $("#check-escenari").trigger('change');

        var x,y;

        for (x = 1; x <= rows; x++) { // 20
            tableMarkup += "<tr>";
            for (y = 1; y <= cols; y++) { // 20
                tableMarkup += "<td data-x='"+x+"' data-y='"+y+"'>&nbsp;</td>";
            }
            tableMarkup += "</tr>";	
        }

        $("#drawing-table").html(tableMarkup);



        function getArraySeients() {
            var seients = [];
            var i = 0;
            $("#drawing-table td.si").each(function() {
                seients[i] = {
                    s: $(this).attr("data-s"),
                    f: $(this).attr("data-f"),
                    z: $(this).attr("data-z"),
                    x: $(this).attr("data-x"),
                    y: $(this).attr("data-y"),
                };
                i++;
            });
            return(seients);
        }


        var carregaGuardats = function(seients) {
            $("#drawing-table td").html('').removeClass('si');
            $.each(seients,function(i,e) {
                $('#drawing-table td[data-x="'+e.x+'"][data-y="'+e.y+'"]')
                .addClass("si")
                .attr("data-s",e.s)
                .attr("data-f",e.f)
                .attr("data-z",e.z)
                .html('<span class="f">'+e.f+'</span><span class="s">'+e.s+'</span>');
            });
        };
        if (typeof seientsGuardats !== 'undefined') {
            carregaGuardats(seientsGuardats);
            $("#count-seients").html($(".si").length);
        }


        function enrere() {
            if (guardat.length) {
                console.log(guardat[guardat.length-1]);
                carregaGuardats(guardat[guardat.length-1]);
                guardat.splice(-1,1);
            }
        }


        /*
        $("#drawing-table").on('click','td',function() {
            var $el = $(this);
            if ($el.hasClass('si')) {
                $el.removeClass('si');
            } else {
                $el.addClass('si');
                var numfila = $("#fila").val();
                var numseient = parseInt($("#seient").val());
                $el.html('<span class="f">'+numfila+'</span><span class="s">'+numseient+'</span>');
                $el.attr("data-f",numfila);
                $el.attr("data-s",numseient);
                $("#seient").val(numseient+1);
            }
            $("#count-seients").html($(".si").length);
        });
        */
        $("#drawing-table").delegate("td", "mousedown", function() {
            guardat.push(getArraySeients());
            $(this).focus();
            mouseDownState = true;
            var $el = $(this);
            if ($el.hasClass("si")) {
                eraseState = true;
            } else {
                eraseState = false;
            }
            if (eraseState) {
                $el.removeClass('si');
                $el.html('');
            } else {
                $el.addClass('si');
                var numfila = $("#fila").val();
                var numseient = parseInt($("#seient").val());
                var zona = $("#zona").val();
                $el.html('<span class="f">'+numfila+'</span><span class="s">'+numseient+'</span>');
                $el.attr("data-f",numfila);
                $el.attr("data-s",numseient);
                $el.attr("data-z",zona);
                $("#seient").val(numseient+parells);
            }
        }).delegate("td", "mouseenter", function() {
            if (mouseDownState) {
                var $el = $(this);
                if (eraseState) {
                    $el.removeClass('si');
                    $el.html('');
                } else {
                    if (!$el.hasClass('si')) {
                        $el.addClass('si');
                        var numfila = $("#fila").val();
                        var numseient = parseInt($("#seient").val());
                        var zona = $("#zona").val();
                        $el.html('<span class="f">'+numfila+'</span><span class="s">'+numseient+'</span>');
                        $el.attr("data-f",numfila);
                        $el.attr("data-s",numseient);
                        $el.attr("data-z",zona);
                        $("#seient").val(numseient+parells);
                    }
                    
                }
            }
        });
        $("html").bind("mouseup", function() {
            mouseDownState = false;
            $("#localitats").val(JSON.stringify(getArraySeients()));
            $("#count-seients").html($(".si").length);
        });

        // Canviar valors
        $(document).on("keydown",function(event) {

            // Desfer
            if (event.keyCode === 90 && (event.metaKey || event.ctrlKey)) {
                event.preventDefault();
                enrere();
                return false;
            }

            var keys = [37, 39, 38, 40];
            var key = event.keyCode;

            if ($.inArray(key,keys) > -1) {

                switch(key) {
                    case 37:
                    $("#seient").val(parseInt($("#seient").val())-1);
                    break;
                    case 39:
                    $("#seient").val(parseInt($("#seient").val())+1);
                    break; 
                    case 38:
                    $("#fila").val(parseInt($("#fila").val())+1);
                    $("#seient").val(1);
                    break;
                    case 40:
                    $("#fila").val(parseInt($("#fila").val())-1);
                    $("#seient").val(1);
                    break;
                }

                event.preventDefault();
                return false;

            }
            
        });

        $("#envia").on("click",function() {
            alert(JSON.stringify(getArraySeients()));
        });	

        $("#neteja").on('click',function(e) {
            e.preventDefault();
            $("#drawing-table td.si").html('').removeClass("si");
            $("#seient").val(1);
            $("#fila").val(1);
        });

        $("#desfer").on('click',function(e) {
            e.preventDefault();
            enrere();
        });

    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".products-sortable tbody").sortable({
        update: function(event, ui) {
            updateOrder();
        },
        activate: function( event, ui ) {
            console.log(event)
        }
    });
    function updateOrder() {
        var item_order = new Array();
        $('.products-sortable tr').each(function() {
            item_order.push($(this).attr("data-id"));
        });
        var order_string = 'order=' + item_order;
        console.log(order_string);
        $.ajax({
            type: "POST",
            url: "/admin/update-order",
            data: order_string,
            cache: false,
            success: function(data) {}
        });
    }


});