<?php
use Illuminate\Support\Facades\Route;
$ncurrentPath = Route::getFacadeRoot()->current()->uri();
$url = URL::to( "/" );
$setid = 1;
$setts = DB::table( 'settings' )
           ->where( 'id', '=', $setid )
           ->get();
?>
<footer id="footer" class="clearfix">

    <!-- footer-top -->
    <section class="footer-top clearfix">
        <div class="container">
            <div class="row">
                <!-- footer-widget -->
                <div class="col-sm-3">
                    <div class="footer-widget">
                        <h3>Our Links</h3>
                        <ul>
                            <li><a href="<?php echo $url;?>">Home</a></li>
                            <li><a href="http://blog.guarddme.com/about-us/">About Us</a></li>
			    <li><a href="http://blog.guarddme.com/press-media/">Press & Media</a></li>
			    <li><a href="http://blog.guarddme.com/terms-and-conditions/">Term & Conditions</a></li>
                            <li><a href="http://blog.guarddme.com/privacy-policy/">Privacy Policy</a></li>
                            <li><a href="<?php echo $url;?>/contact">Contact Us</a></li>

                        </ul>
                    </div>
                </div><!-- footer-widget -->

                <!-- footer-widget -->
                <div class="col-sm-3">
                    <div class="footer-widget">
                        <h3>About Us</h3>
                        <ul>
                            <li><a href="http://blog.guarddme.com/how-it-works/">How it Works</a></li>
                            <li><a href="http://blog.guarddme.com/guardme-loyalty-scheme/">Loyalty & Affiliates</a></li>
                            <li><a href="http://blog.guarddme.com/all-about-guardme-verification/">Contractor Verification</a></li>
                            <li><a href="http://blog.guarddme.com/guardme-wallet-101/">GuardME Wallet</a></li>
                        </ul>
                    </div>
                </div><!-- footer-widget -->

                <!-- footer-widget -->
                <div class="col-sm-3">
                    <div class="footer-widget social-widget">
                        <h3>Follow us on</h3>
                        <ul>
                            <li><a href="https://www.facebook.com/guardmeapp" target="_blank"><i
                                            class="fa fa-facebook-official"></i>Facebook</a></li>
                            <li><a href="https://twitter.com/GuardMeApp" target="_blank"><i
                                            class="fa fa-twitter-square"></i>Twitter</a></li>
                            <li><a href="https://www.youtube.com/channel/UCb4fl07yFavw6YthwktTPKw" target="_blank"><i
                                            class="fa fa-youtube-play"></i>Google+</a></li>
                            <li><a href="https://www.instagram.com/guardmeapp/" target="_blank"><i
                                            class="fa fa-instagram"></i>Instagram</a></li>


                        </ul>
                    </div>
                </div>
                <!-- footer-widget -->
                <div class="col-sm-3">
                    <div class="footer-widget news-letter">
                        <h3>Newsletter</h3>
                        <p>GuardME is UK's leading SIA Security Marketplace!</p>
                        <!-- form -->
                        <form action="{{route('subscribe.newsletter')}}" id="newsletterformasc" method="POST">
                            {{csrf_field()}}
                            <input type="email" name="email" class="form-control" placeholder="Your email id">
                            <input type="submit" class="btn btn-primary" value="Sign Up">
                        </form><!-- form -->
                    </div>
                </div><!-- footer-widget -->
            </div>
    </section>


    <div class="footer-bottom clearfix text-center">
        <div class="container">

            <p><?php if($setts[0]->site_copyright != ""){ ?><?php echo $setts[0]->site_copyright;
				} else {?>&copy; <?php echo date( 'Y' );?>. All Rights Reserved.<?php } ?></p>
        </div>
    </div><!-- footer-bottom -->


</footer>


<!-- add javascripts -->

<?php if($ncurrentPath == "index" or $ncurrentPath == "/"){?>
<script>


    $(document).ready(function () {
        $(window).scroll(function () {
            if ($(window).width() > 1200) {
                // check if scroll event happened
                if ($(document).scrollTop() > 50) { // check if user scrolled more than 50 from top of the browser window
                    $(".navbar-fixed-top").css("background-color", "#F4F4F4");

                    $(".navbar-fixed-top li a").css("color", "#000000");


                    $(".navbar-fixed-top li.dropdown .open a").css("color", "#000000");
                    // if yes, then change the color of class "navbar-fixed-top" to white (#f8f8f8)
                } else {
                    $(".navbar-fixed-top").css("background-color", "transparent");

                    $(".navbar-fixed-top li a").css("color", "#ffffff");
                    $(".navbar-fixed-top li.dropdown .open a").css("color", "#000000");
                    // if not, change it back to transparent
                }


            }

        });


    });
</script>
<?php } else  {?>
<script>
    $(document).ready(function () {
        $(window).scroll(function () {
            if ($(window).width() > 1200) {
                // check if scroll event happened
                if ($(document).scrollTop() > 50) { // check if user scrolled more than 50 from top of the browser window
                    $(".navbar-fixed-top").css("background-color", "#F4F4F4");
                    $(".navbar-fixed-top li a").css("color", "#000000");
                    // if yes, then change the color of class "navbar-fixed-top" to white (#f8f8f8)
                } else {
                    $(".navbar-fixed-top").css("background-color", "#ffffff");
                    $(".navbar-fixed-top li a").css("color", "#000000");
                    // if not, change it back to transparent
                }
            }

        });
    });
</script>
<?php } ?>
<!-- Only used for Script Tutorial's Demo site. Please ignore and remove. -->
<script type="text/javascript">
    $(document).ready(function () {
        $(".toggle-favourite").on("click", function () {
            var self = $(this);
            if ($(this).hasClass('btn-info')) {
                var btnText = 'Un-favourite it';
                var oldClass = 'btn-info';
                var newClass = 'btn-danger';
            } else {
                oldClass = 'btn-danger';
                newClass = 'btn-info';
                btnText = 'Favourite it';
            }
            $.ajax({
                url: $(this).attr('data-action'),
                type: "POST",
                success: function (data) {
                    self.removeClass(oldClass);
                    self.addClass(newClass);
                    self.addClass('btn');
//                    self.text(btnText);
                },
                error: function (data) {
                    //console.log(data);
                }
            });
        });
        $('#media').carousel({
            pause: true,
            interval: false,
        });
    });


</script>


<script type="text/javascript" src="<?php echo $url;?>/js/jquery.flexisel.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://getaddress.io/js/jquery.getAddress-2.0.7.min.js"></script>


<script src="<?php echo $url;?>/js/jquery.multiselect.js"></script>
<script src="<?php echo $url;?>/js/date-time-picker/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo $url;?>/js/date-time-picker/bootstrap-datetimepicker.uk.js"></script>
<script src="<?php echo $url;?>/js/moment.js"></script>
<script src="<?php echo $url;?>/js/star-rating.min.js"></script>
<script src="<?php echo $url;?>/js/notification.js"></script>
<script>
    class Errors {
        constructor() {
            this.formErrors = {};
        }

        record(errors) {
            this.formErrors = errors;
        }

        load() {
            $(".error-span").addClass('hide');
            $.each(this.formErrors, function (i, v) {
                if (typeof i != 'undefined' && i != 0) {
                    i = i.replace('[]', '');
                    i = i.split('.')[0];
                    if (typeof $('.' + i) != 'undefined') {
                        $('.' + i).siblings('.error-span').html(v);
                        $('.' + i).siblings('.error-span').removeClass('hide');
                    }
                }

            });
            if (typeof $('.error-span:eq(0)').closest('.form-group') != 'undefined') {
                if (typeof $('.error-span:visible:eq(0)').closest('.form-group').offset() != 'undefined')
                    var scrollPosition = $('.error-span:visible:eq(0)').closest('.form-group').offset().top - 70;
                $("html, body").animate({scrollTop: scrollPosition}, 1000);
            }
        }
    }

    $('#langOpt').multiselect({
        columns: 1,
        placeholder: 'Select Services'
    });
    var pgsVal = 0;
    $(".trackprogress").each(function (i, obj) {
        if ($(this).val().length > 0) {
            pgsVal += 4;
        }
    });
    console.log(pgsVal);
    $("#progressbar").progressbar({
        value: pgsVal,
        create: function (event, ui) {
            $(this).find('.ui-widget-header').css({'background-color': '#5cb85c'})
        }
    });
    $('.trackprogress').change(function () {
        var pbVal = 0;
        $(".trackprogress").each(function (i, obj) {
            if ($(this).val().length > 0) {
                pbVal += 4;
            }
        });
        $("#progressbar").progressbar("option", "value", pbVal);
        return false;
    });
    $('#postcode_lookup').getAddress({
        api_key: 'ZTIFqMuvyUy017Bek8SvsA12209',
        input_id: 'address_id',
        input_name: 'address_id',
        input_class: 'form-control validate[required]',
        button_class: 'btn',
        dropdown_class: 'form-control',
//        <!--Or use your own endpoint - api_endpoint:https://your-web-site.com/getAddress, --!>
        output_fields: {
            line_1: '#line1',
            line_2:
                '#line2',
            line_3:
                '#line3',
            post_town:
                '#town',
            county:
                '#county',
            postcode:
                '#postcode'
        }
        ,
        <!--  Optionally register callbacks at specific stages -->
        onLookupSuccess: function (data) {/* Your custom code */
            console.log(data);
            $('#addresslat').val(data.latitude);
            $('#addresslong').val(data.longitude);
            $('#country').val('UK');
        }
        ,
        onLookupError: function () {/* Your custom code */
        }
        ,
        onAddressSelected: function (elem, index) {/* Your custom code */
        }
    })
    ;
    $("select#nationality").change(function () {
        $("select#nationality option:selected").each(function () {
            if ($(this).attr("value") == "229") {
                $("#visa_no_field").hide();
                $("#niutr_no_field").show();
            } else {
                $("#visa_no_field").show();
                $("#niutr_no_field").hide();
            }
        });
    }).change();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery('.date-time-picker').datetimepicker({
        //language:  'uk',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        minuteStep: 5
    });
    $(document).ajaxStart(function () {
        $(".loading").removeClass("hide");

    });
    $(document).ajaxStop(function () {
        $(".loading").addClass("hide");
    });


</script>
<script>
    $('#newsletterformasc').submit(function () {
        event.preventDefault();

        var form_data = $('#newsletterformasc').serialize();
        $.ajax({
            url: "/subscribe-to-neswletter",
            method: "POST",
            data: form_data,
            dataType: 'json',
            success: function (d) {
                alert('Subscribe to neswletter successfully.')
                document.getElementById("newsletterformasc").reset();
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(JSON.parse(xhr.responseText).email)
            }
        });
    });
</script>


<?php /* ?><script src="{{ asset('js/app.js') }}"></script><?php */?>
