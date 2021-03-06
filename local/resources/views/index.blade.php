<?php
use Illuminate\Support\Facades\Route;
$currentPaths = Route::getFacadeRoot()->current()->uri();
$url = URL::to( "/" );
$setid = 1;
$setts = DB::table( 'settings' )
           ->where( 'id', '=', $setid )
           ->get();
?>
        <!DOCTYPE html>
<html lang="en">
<head>


    @include('style')

    <style>
        .banner-job {

            @if(!empty($setts[0]->site_banner))
    background-image: url({{$url}}/local/images/settings/{{$setts[0]->site_banner}});
            @else
    background-image: url({{$url}}/img/banner.jpg);
        @endif











        }
    </style>

    <script>

        function set_loc(val) {
            //$('#loc_id').val(id);
            $('#loc_val').val(val);
        }

        function set_cat(id, val) {
            $('#cat_id').val(id);
            $('#cat_val').val(val);
        }
    </script>

</head>
<body>


<!-- fixed navigation bar -->

@include('header')


<!-- slider -->

<div class="banner-job">

    <div class="container text-center color-grey">
        <h1 class="title">Manned Security Freelance Marketplace.</h1>
        <h3>Looking for security personnel in the UK?</h3>
        <h4>Get access to thousands of vetted SIA security personnel.</h4>
        <div class="banner-form">
            <form method="POST" action="{{ route('post.find.jobs') }}" id="formID">
                {!! csrf_field() !!}
                <input type="text" class="form-control" placeholder="Type City" name="city" value="">

                <div class="dropdown category-dropdown language-dropdown">
                    <a data-toggle="dropdown" href="#">
                        <span class="change-text">
                    {{'Location'}}
                        </span></a>
                </div><!-- category-change -->


                {{--<div class="dropdown category-dropdown language-dropdown">--}}
                {{--<a data-toggle="dropdown" href="#"><span class="change-text" >--}}
                {{--@if(old('loc_val')!=NULL)--}}
                {{--{{old('loc_val')}}--}}
                {{--@else--}}
                {{--{{'Location'}}--}}
                {{--@endif--}}
                {{--</span> <i class="fa fa-angle-down"></i></a>--}}

                {{--<ul class="dropdown-menu category-change language-change loc">--}}
                {{--@foreach($locs as $loc)--}}
                {{--<li><a href="#" onclick="set_loc('{{$loc->city_town}}')">{{$loc->city_town}}</a></li>--}}
                {{--@endforeach--}}
                {{--</ul>   --}}

                {{--<input type="hidden" name="loc_val" value="{{old('loc_val')}}" id="loc_val">                        --}}
                {{--</div><!-- category-change -->--}}
                <button type="submit" class="btn btn-primary" value="Search">Search</button>
            </form>
        </div><!-- banner-form -->

        <!-- banner-socail -->
    </div><!-- container -->
</div><!-- banner-section -->

<script>
    $(document).ready(function () {
        src = "{{ route('searchajax') }}";
        $("#search_text").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: src,
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        response(data);

                    }
                });
            },
            minLength: 1,

        });
    });
</script>


<div class="ashbg">

    <div class="clearfix"></div>


    <div class="clearfix"></div>

</div>

<div class="clearfix"></div>

<div class="page">
    <div class="container">
        <div class="works section job-category-items">

            <div class="col-md-12" align="center"><h1>How GuardME works</h1></div>
            <div class="height30"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="col-md-6">
                            <img src="img/how-it-works.png" class="img-responsive" alt="">
                        </div>

                        <div class="col-md-6">
                            <h3 class="">1. Register or login to your account</h3>
                            <p class="">Update your company information and create your first job. Creating a job is
                                easy and payment is a breeze!</p>
                            <h3 class="">2. Award the job</h3>
                            <p class="">In a short time you’ll receive applications from hundreds of security personnel.
                                Use the filter to choose by location or feedback rating.</p>
                            <h3 class="">3. Sit back and relax!</h3>
                            <p class="">The process is simple. Your contracted personnel will arrive on time. You can
                                also check the GuardME app for tracking information.</p>

                        </div>
                    </div>
                    <div class="col-md-1"></div>

                </div>

            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>

       <div class="section workshop-traning">
				<div class="section-title">
					<h4>Featured Jobs</h4>
					<a href="#" class="btn btn-primary">See all</a>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="workshop">
							<img src="images/user.jpg" alt="Image" class="img-responsive">
							<h3><a href="#">Corner Shop Security Guard</a></h3>
							<h4>Job Duration: 3 Weeks ( Sat, Mon, Fri)</h4>
							<div class="workshop-price">
								<h5>Location: Dewsbury</h5>
								<h5>Pay Per Hour: £15</h5>
							</div>
							<div class="ad-meta">
								<div class="meta-content">
									<span class="dated"><a href="#">7 August 10:10 pm </a></span>
								</div>
								<div class="user-option pull-right">
									<a href="#"><i class="fa fa-map-marker"></i> </a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="workshop">
							<img src="images/user.jpg" alt="Image" class="img-responsive">
							<h3><a href="#">Night Club Door Man Needed</a></h3>
							<h4>Job Duration: 2 Months ( Fri, Sat)</h4>
							<div class="workshop-price">
								<h5>Location: Peckham</h5>
								<h5>Pay Per Hour: £18</h5>
							</div>
							<div class="ad-meta">
								<div class="meta-content">
									<span class="dated"><a href="#">17 August 09:24 am </a></span>
								</div>
								<div class="user-option pull-right">
									<a href="#"><i class="fa fa-map-marker"></i> </a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- workshop-traning -->

        <div class="section video workshop-traning">

            <div class="section-title">
                <h4>Check out these recent posts from our blog...</h4>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-1"></div>


                    <div class="col-md-10">


                        @foreach($posts as $key=>$post)

                            @if($key==0)
                                <a href="{{ $post['link'] }}">
                                    <div class="col-md-8 paddingoff">
                                        <img src="{!! $post['image'] !!}" class="img-responsive big firstsize" alt="">
                                        <div class="titlesection">
                                            <h3>{!! $post['title'] !!}</h3>
                                            <span>{!! $post['category'] !!}</span>
                                        </div>

                                    </div>
                                </a>
                                <div class="height10 visible-xs "></div>

                            @endif

                            @if($key==1)
                                <div class="col-md-4 paddingoff left10">
                                    <a href="{{ $post['link'] }}">
                                        <div class="justmove col-md-12 paddingoff"><img src="{!! $post['image'] !!}"
                                                                                        class="img-responsive" alt="">
                                            <div class="titlesection">
                                                <h3>{!! $post['title'] !!}</h3>
                                                <span>{!! $post['category'] !!}</span>
                                            </div>

                                        </div>
                                    </a>
                                    @endif

                                    @if($key==2)
                                        <div class="height10 hidden-md"></div>
                                        <a href="{{ $post['link'] }}">
                                            <div class="justmove col-md-12 paddingoff"><img src="{!! $post['image'] !!}"
                                                                                            class="img-responsive"
                                                                                            alt="">
                                                <div class="titlesection">
                                                    <h3>{!! $post['title'] !!}</h3>
                                                    <span>{!! $post['category'] !!}</span>
                                                </div>
                                            </div>
                                        </a>
                                </div>
                            @endif






                        @endforeach

                    </div>


                </div>
            </div>


        </div>
    </div>
</div>

</div>
</div>
<div class="clearfix"></div>


<div class="video">
    <div class="clearfix"></div>

</div>


<!-- download -->
<section id="download" class="clearfix parallax-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>Download on App Store</h2>
            </div>
        </div><!-- row -->

        <!-- row -->
        <div class="row">
            <!-- download-app -->
            <div class="col-sm-4">
                <a href="https://play.google.com/store/apps/details?id=com.guarddme.com" class="download-app">
                    <img src="images/icon/16.png" alt="Image" class="img-responsive">
                    <span class="pull-left">
                            <span>available on</span>
                            <strong>Google Play</strong>
                        </span>
                </a>
            </div><!-- download-app -->
            <!-- download-app -->
            <div class="col-sm-4">
                <a href="#" class="download-app">
                    <img src="images/icon/17.png" alt="Image" class="img-responsive">
                    <span class="pull-left">
                            <span>available on</span>
                            <strong>App Store</strong>
                        </span>
                </a>
            </div><!-- download-app -->
            <!-- download-app -->
            <div class="col-sm-4">
                <a href="#" class="download-app">
                    <img src="images/icon/18.png" alt="Image" class="img-responsive">
                    <span class="pull-left">
                            <span>available on</span>
                            <strong>Windows Store</strong>
                        </span>
                </a>
            </div><!-- download-app -->
        </div><!-- row -->
    </div><!-- contaioner -->
</section><!-- download -->


@include('footer')
</body>
</html>
