 jQuery(document).ready(function($){
    $(".sabox-slider").slider({
        value: saboxTopmargin,
        min: 0,
        max: 100,
        step: 1,
        slide: function (event, ui) {
            $(".sabox-amount").val( ui.value + " px" );
            $("#sab_box_margin_top").val(ui.value);
        }
    });
    $(".sabox-amount").val($(".sabox-slider").slider( "value" ) + " px" );
});

 jQuery(document).ready(function($){
    $(".sabox-slider2").slider({
        value: saboxBottommargin,
        min: 0,
        max: 100,
        step: 1,
        slide: function (event, ui) {
            $(".sabox-amount2").val( ui.value + " px" );
            $("#sab_box_margin_bottom").val(ui.value);
        }
    });
    $(".sabox-amount2").val($(".sabox-slider2").slider( "value" ) + " px" );
});

 jQuery(document).ready(function($){
    $(".sabox-slider3").slider({
        value: saboxIconsize,
        min: 11,
        max: 50,
        step: 1,
        slide: function (event, ui) {
            $(".sabox-amount3").val( ui.value + " px" );
            $("#sab_box_icon_size").val(ui.value);
        }
    });
    $(".sabox-amount3").val($(".sabox-slider3").slider( "value" ) + " px" );
});

 jQuery(document).ready(function($){
    $(".sabox-slider4").slider({
        value: saboxNamesize,
        min: 10,
        max: 50,
        step: 1,
        slide: function (event, ui) {
            $(".sabox-amount4").val( ui.value + " px" );
            $("#sab_box_name_size").val(ui.value);
        }
    });
    $(".sabox-amount4").val($(".sabox-slider4").slider( "value" ) + " px" );
});

 jQuery(document).ready(function($){
    $(".sabox-slider5").slider({
        value: saboxDescsize,
        min: 10,
        max: 50,
        step: 1,
        slide: function (event, ui) {
            $(".sabox-amount5").val( ui.value + " px" );
            $("#sab_box_desc_size").val(ui.value);
        }
    });
    $(".sabox-amount5").val($(".sabox-slider5").slider( "value" ) + " px" );
});

 jQuery(document).ready(function($){
    $(".sabox-slider6").slider({
        value: saboxWebsize,
        min: 10,
        max: 50,
        step: 1,
        slide: function (event, ui) {
            $(".sabox-amount6").val( ui.value + " px" );
            $("#sab_box_web_size").val(ui.value);
        }
    });
    $(".sabox-amount6").val($(".sabox-slider6").slider( "value" ) + " px" );
});