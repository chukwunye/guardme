<!DOCTYPE html>
<html lang="en">
<head>

    @include('admin.title')

    @include('admin.style')
    <style>
        .dataTables_filter {
            display: none;
        }

        .fa-base {
            display: none;
        }
        .send-message {
            position: fixed;
            right: 44px;
            bottom: 10px;
            z-index: 999999999999;
            background-color: green;
            color: white;
            padding: 12px;
        }
        .send-message a{
            color:white;
        }
        .send-message:hover {
            background-color:black;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- <div class="main_container"> -->
    <div class="sidebar" data-background-color="white" data-active-color="danger">
        <div class="sidebar-wrapper">
            @include('admin.sitename');

            <!-- <div class="clearfix"></div> -->

            <!-- menu profile quick info -->
        @include('admin.welcomeuser')
        <!-- /menu profile quick info -->

            <!-- <br /> -->
            <!-- sidebar menu -->
        @include('admin.menu')

        <!-- /sidebar menu -->

            <!-- /menu footer buttons -->

            <!-- /menu footer buttons -->
        </div>
    </div>
    <div class="main-panel">
        <!-- top navigation -->
    @include('admin.top')

	<?php $url = URL::to( "/" ); ?>

    <!-- /top navigation -->
    <div class="send-message">
    <a href="javascript:void(0);"> Send Message
    <i class="fa fa-envelope"></i></a>
                       </div>
        <!-- page content -->
        <div class="content">
            <!-- top tiles -->

            <style>
                div.dataTables_wrapper div.dataTables_filter input {
                    border: 1px solid #000;
                }
            </style>


            <div class="container-fluid">
                <div class="row">
                    <div class="card" style="padding:15px;">
                        <div class="header">
                            <h4 class="title">Users</h4>
                            <!-- <p class="category">Here is a subtitle for this table</p> -->
                        </div>
                        <!-- <div class="x_title">
                          <h2>Users</h2> -->
                        <!-- <ul class="nav navbar-right panel_toolbox">

                           <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                          </li>
                        </ul> -->
                        <!-- <div class="clearfix"></div> -->

                        <!-- </div> -->
                        <!-- <div align="right"> -->
                       
						<?php if(config( 'global.demosite' ) == "yes"){?>
                        <span class="disabletxt">( <?php echo config( 'global.demotxt' );?> )</span>
                        <a href="#" class="btn btn-primary btndisable">Add User</a>
						<?php } else { ?>
                        <a href="<?php echo $url;?>/admin/adduser" class="btn btn-primary">Add User</a>
						<?php } ?>
                        <div class="content">
                            <form class="form-inline">
                                <div class="form-group">

                                    <label class="col-md-3 col-lg-3" class="control-label">Location:</label>
                                    <input type="text" class="form-control" name="location" id="location_filter">
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="control-label">Gender:</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="">Pick an option...</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                </div>
                            </form>
                            <br>
                            <form class="form-inline">
                                <div class="col-md-3 col-lg-3 ">
                                    Registration date range :
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="control-label">Max</label>
                                    <input type="date" class="form-control daterangepicker" id="date_filter_max"
                                           name="reg_date_max">
                                </div>
                                <div class="form-group">-</div>
                                <div class="form-group">
                                    <label for="gender" class="control-label">Min</label>
                                    <input type="date" class="form-control daterangepicker" id="date_filter_min"
                                           name="reg_date_min">
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="control-label"> </label>
                                    <input type="button" class="btn btn-info" id="date_reset" value="Reset">
                                </div>
                            </form>

                        </div>

                        <div class="content table-responsive table-full-width">
                            <table id="datatable-asdsd" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                 <th></th>
                                    <th>Sno</th>
                                    <th class="hidden">gender</th>
                                    <th class="hidden">location</th>
                                    <th class="hidden">Registration date</th>
                                    <th>Photo</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>User Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$i = 1;
								foreach ($users as $user) {

								$sta = $user->admin;
								if ( $sta == 1 ) {
									$viewst = "Admin";
								} else if ( $sta == 2 ) {
									$viewst = "Freelancer";
								} else if ( $sta == 3 ) {
									$viewst = "Licensed
                                      Partner";
								} else if ( $sta == 0 ) {
									$viewst = "Employer";
								}?>
                                <tr>
                                    <td>
                                      <span> <input type="checkbox" name="selecteduser" class="selected-user" value="{{$user->id}}"></span>
                                    </td>
                                    <td>
                                        <?php echo $i;?>

                                    </td>
                                    <td class="hidden"> @if($user->gender=='male')
                                            1
                                        @else
                                            2
                                        @endif
                                    </td>
                                    <td class="hidden">
                                        {{$user->postcode }}
                                        {{$user->houseno }}
                                        {{$user->houseno }}
                                        {{$user->line1 }}
                                        {{$user->line2 }}
                                        {{$user->line3 }}
                                        {{$user->line4 }}
                                        {{$user->locality }}
                                        {{$user->citytown }}
                                        {{$user->country }}
                                    </td>
                                    <td class="hidden">{{date("Ymd",strtotime($user->created_at) )}}</td>
									<?php
									$userphoto = "/userphoto/";
									$path = '/local/images' . $userphoto . $user->photo;
									if($user->photo != ""){
									?>
                                    <td><img src="<?php echo $url . $path;?>" class="thumb" width="70"></td>
									<?php } else { ?>
                                    <td><img src="<?php echo $url . '/local/images/nophoto.jpg';?>" class="thumb"
                                             width="70"></td>
									<?php } ?>
                                    <td><?php echo $user->name;?></td>
                                    <td><?php echo $user->email;?>
                                        
                                         @php 
                                          $color = $user->verified == 0 ? "danger" : "success";
                                          $icon = $user->verified == 0 ? "fa-window-close" : "fa-check";

                                        @endphp               
                                        <a href="<?php echo $url;?>/admin/email-status/{{ $user->id }}"
                                                       class="btn btn-{{$color}} btn-sm pull-right"
                                                       onclick="return confirm('Are you sure you want to change status ?')"><i class="fa {{$icon}}" aria-hidden="true"></i></a>
                                    </td>
                                    <td><?php echo $user->phone;?>
                                        
                                        @php 
                                          if(! empty($user->phone)) {
                                          $color = $user->phone_verified == 0 ? "danger" : "success";
                                            $icon = $user->phone_verified == 0 ? "fa-window-close" : "fa-check";


                                        @endphp               
                                        <a href="<?php echo $url;?>/admin/phone-status/{{ $user->id }}"
                                                       class="btn btn-{{$color}} btn-sm pull-right"
                                                       onclick="return confirm('Are you sure you want to change status ?')"><i class="fa {{$icon}}"></i></a>
                                         <?php } ?>              
                                    </td>
                                    <td><?php echo $viewst;?></td>
                                    <td>
										<?php if(config( 'global.demosite' ) == "yes"){?>
                                        <a href="#" class="btn btn-success btndisable">Edit</a> <span
                                                class="disabletxt">( <?php echo config( 'global.demotxt' );?> )</span>
										<?php } else { ?>

                                        <a href="<?php echo $url;?>/admin/edituser/{{ $user->id }}"
                                           class="btn btn-success">Edit</a>
										<?php } ?>
										<?php if(config( 'global.demosite' ) == "yes"){?>
                                        <a href="#" class="btn btn-danger btndisable">Delete</a> <span
                                                class="disabletxt">( <?php echo config( 'global.demotxt' );?> )</span>
										<?php } else { ?>

                                        @if($sta!=1)<a href="<?php echo $url;?>/admin/users/{{ $user->id }}"
                                                       class="btn btn-danger"
                                                       onclick="return confirm('Are you sure you want to delete this?')">Delete</a> @endif
                                       
                                                                         

										<?php } ?>
                                    </td>
                                </tr>
								<?php $i ++; } ?>
                                </tbody>
                            </table>
                           
                        </div>
                    </div>
                </div>
                


            </div>
            <!-- /page content -->
        </div>
        @include('admin.footer')
    </div>
    <div id="myModel" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     <form id="sendmessageform" action="{{$url}}/admin/message" method="post">
      {{csrf_field()}}
      <div class="modal-header">
        <h5 class="modal-title">Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <textarea name="message" class="form-control" id="" cols="30" rows="10" placeholder="Enter message here" require></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Send Message</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
<script src="/js/date-time-picker/bootstrap-datetimepicker.min.js"></script>
<script src="/js/date-time-picker/bootstrap-datetimepicker.uk.js"></script>
<script src="{{asset('/js/moment.js')}}"></script>

<script>
    //    User Filtering
    $(document).ready(function () {
        var selected_user;
        var table = $('#datatable-asdsd').DataTable();

        $('#gender').on('change', function () {
            table
                .columns(1)
                .search(this.value)
                .draw();
        });
        $('#location_filter').on('keyup', function () {
            table
                .columns(2)
                .search(this.value)
                .draw();
        });
        $('#date_reset').click(function () {
            $('#date_filter_max').val('')
            $('#date_filter_min').val('')
            table.draw();
        })

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = moment($('#date_filter_max').val()).format('YYYYMMDD');
                var max = moment($('#date_filter_min').val()).format('YYYYMMDD');
                var age = parseFloat(data[3]) || 0; // use data for the age column

                if (( isNaN(min) && isNaN(max) ) ||
                    ( isNaN(min) && age <= max ) ||
                    ( min <= age && isNaN(max) ) ||
                    ( min <= age && age <= max )) {
                    return true;
                }
                return false;
            }
        );

        $('#date_filter_max,#date_filter_min').on('change', function () {
            table.draw();
        });

        $(".send-message").click(function(e){

            e.preventDefault();
            userid = [];
            $("input:checkbox[name=selecteduser]:checked").each(function(){
                userid.push($(this).val());
            });

            if(userid.length == 0) {
                alert("Please select user");
                return;
            }

            $("#sendmessageform")[0].reset();
            selected_user = userid;
            $("#myModel").modal('show');
             
 
        });
        $("#sendmessageform").submit(function(e) {
            e.preventDefault();
            data = $(this).serialize();
            data += "&user=" + JSON.stringify(selected_user);
           var request = $.ajax(
              {  url:"{{$url}}/admin/message",
                data: data,
                method:"POST",
              
            }
            );
          request.done(function(msg){
              $("#myModel").modal('hide');
               alert("Message send successfully");
          }); 

        });
        //   End User  Filtering
    });


</script>
</body>
</html>
