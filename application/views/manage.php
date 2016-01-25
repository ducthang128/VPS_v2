<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BES | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="/bes/manage/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/bes/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/bes/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="/bes/manage/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <link rel="stylesheet" href="/bes/manage/dist/css/daterangepicker-bs3.css">
  <!-- calendar -->
  <link rel="stylesheet" href="/bes/manage/dist/css/fullcalendar.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="/bes/manage/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/bes/manage/dist/css/skins/_all-skins.min.css">
  <!-- HTML5 Light Box Preview -->
  <link rel="stylesheet" href="/bes/manage/dist/css/lightbox.css">
  <link rel="stylesheet" href="/bes/manage/plugins/ionslider/ion.rangeSlider.css">
  <link rel="stylesheet" href="/bes/manage/plugins/ionslider/normalize.css">
  <link rel="stylesheet" href="/bes/manage/plugins/ionslider/ion.rangeSlider.skinNice.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="stylesheet" href="/bes/manage/dist/css/file_upload.css" type="text/css" />
  <script type="text/javascript" src="/bes/manage/dist/js/script_upload.js"></script>
<!-- jQuery 2.1.4 -->
<script src="/bes/manage/plugins/jQuery/jQuery-2.1.4.min.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="/manage" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>BES</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>BES</b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/bes/manage/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php if (isset($user_info['first_name']) && isset($user_info['last_name'])){echo $user_info['first_name'].' '.$user_info['last_name'];} ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="/bes/manage/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php
                    if (isset($user_info['first_name']) && isset($user_info['last_name']))
                    {
                        $full_name = ( $user_info['first_name'] == '' && $user_info['last_name'] == '' ) ? 'Chưa Nhập Tên' : $user_info['first_name'].' '.$user_info['last_name'];
                        echo $full_name.' - ';
                        if (isset($auth_role)){echo $auth_role;}
                    }
                  ?>
                  <small><?php if (isset($auth_email)){echo $auth_email;} ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Nội dung</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Ngân sách</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Báo Cáo</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Hồ sơ</a>
                </div>
                <div class="pull-right">
                  <a href="/userauth/logout" class="btn btn-default btn-flat">Thoát</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="/bes/manage/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>
          <?php
          if (isset($auth_user_name))
          {
            echo $auth_user_name. ' - ' .$auth_role;
            echo '</br></br><a href="/userauth/logout"><i class="fa fa-circle text-success"></i> Online </a><a href="/userauth/logout"> (Thoát)</a>';
          }
          else
          {
            echo 'Khách hàng mới';
          }
          ?>
          </p>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>

      <!-- /.search form -->

<!-- sidebar menu: : style can be found in sidebar.less -->
      <?php if(isset($menu_panel) && $menu_panel != '') {echo $menu_panel;} ?>
    </section>
<!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <strong>BUS ENTERTAINMENT SYSTEM</strong>
        <small>Beta Version 1.0</small>
      </h1>
      <ol class="breadcrumb">
        <?php echo $breadcrumb; ?>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php
        if (isset($contents))
        {
            echo $contents;
        }
        ?>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Beta Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2015-2016 <a href="http://bes.saigonsmartsolutions.com.vn">Saigon Smart Solutions</a>.</strong> All rights
    reserved.
  </footer>


</div>
<!-- ./wrapper -->
<!-- Bootstrap 3.3.5 -->
<script src="/bes/manage/bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="/bes/manage/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/bes/manage/dist/js/app.min.js"></script>
<!-- Calendar -->
<script src="/bes/manage/dist/js/moment.min.js"></script>
<script src="/bes/manage/dist/js/fullcalendar.min.js"></script>
<script src="/bes/manage/dist/js/lang-all.js"></script>
<script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });

      });
    }

    ini_events($('#external-events div.external-event'));

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
   /* $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'agendaWeek,basicDay'
      },
      buttonText: {
        today: 'Hôm nay',
        month: 'Tháng',
        week: 'Tuần',
        day: 'Ngày'
      },
      //Random default events
      //"#f56954" //red, "#f39c12" //yellow, "#0073b7", //Blue, "#00c0ef", //Info (aqua), "#00a65a", //Success (green), "#3c8dbc" //Primary (light-blue)
      events: [
        {
          title: 'Clip 1',
          start: new Date(y, m, d, 10, 0, 15),
          end: new Date(y, m, d, 10, 0, 35),
          url: '/manage/',
          backgroundColor: "#f56954", //red
          borderColor: "#f56954" //red
        },
        {
          title: 'Clip 2',
          start: new Date(y, m, d, 10, 0, 35),
          end: new Date(y, m, d, 10, 0, 60),
          url: '/manage/',
          backgroundColor: "#f39c12", //yellow
          borderColor: "#f39c12" //yellow
        },
        {
          title: 'Clip 3',
          start: new Date(y, m, d, 10, 1, 0),
          end: new Date(y, m, d, 10, 1, 35),
          url: '/manage/',
          backgroundColor: "#0073b7", //Blue
          borderColor: "#0073b7" //Blue
        },
        {
          title: 'Clip 4',
          start: new Date(y, m, d, 10, 1, 55),
          end: new Date(y, m, d, 10, 2, 25),
          url: '/manage/',
          backgroundColor: "#00c0ef", //Info (aqua)
          borderColor: "#00c0ef" //Info (aqua)
        },
        {
          title: 'Clip 5',
          start: new Date(y, m, d, 10, 2, 25),
          end: new Date(y, m, d, 10, 2, 55),
          url: '/manage/',
          backgroundColor: "#00a65a", //Success (green)
          borderColor: "#00a65a" //Success (green)
        },
        {
          title: 'Clip 6',
          start: new Date(y, m, d, 10, 0, 15),
          end: new Date(y, m, d, 10, 0, 35),
          url: '/manage/',
          backgroundColor: "#f56954", //red
          borderColor: "#f56954" //red
        },
        {
          title: 'Clip 7',
          start: new Date(y, m, d, 10, 0, 35),
          end: new Date(y, m, d, 10, 0, 60),
          url: '/manage/',
          backgroundColor: "#f39c12", //yellow
          borderColor: "#f39c12" //yellow
        },
        {
          title: 'Clip 8',
          start: new Date(y, m, d, 10, 1, 0),
          end: new Date(y, m, d, 10, 1, 35),
          url: '/manage/',
          backgroundColor: "#0073b7", //Blue
          borderColor: "#0073b7" //Blue
        },
        {
          title: 'Clip 9',
          start: new Date(y, m, d, 10, 1, 55),
          end: new Date(y, m, d, 10, 2, 25),
          url: '/manage/',
          backgroundColor: "#00c0ef", //Info (aqua)
          borderColor: "#00c0ef" //Info (aqua)
        },
        {
          title: 'Clip 10',
          start: new Date(y, m, d, 10, 2, 25),
          end: new Date(y, m, d, 10, 2, 55),
          url: '/manage/',
          backgroundColor: "#00a65a", //Success (green)
          borderColor: "#00a65a" //Success (green)
        },
        {
          title: 'Clip 11',
          start: new Date(y, m, d, 10, 2, 55),
          end: new Date(y, m, d, 10, 3, 20),
          url: '/manage/',
          backgroundColor: "#3c8dbc", //Primary (light-blue)
          borderColor: "#3c8dbc" //Primary (light-blue)
        }
      ],
      defaultView: 'agendaWeek',
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar !!!
      drop: function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject');

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);

        // assign it the date that was reported
        copiedEventObject.start = date;
        copiedEventObject.allDay = allDay;
        copiedEventObject.backgroundColor = $(this).css("background-color");
        copiedEventObject.borderColor = $(this).css("border-color");

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove();
        }

      }
    });
*/

     $('#calendar').fullCalendar({
			header: {
			    left: 'prev,next today',
			    center: 'title',
			    right: 'agendaWeek,agendaDay'
		    },
		    defaultDate: new Date(),
		    editable: false,
		    slotDuration: '00:02:00',
		    scrollTime: new Date(),
		    defaultView: 'agendaDay',
		    lang: 'vi',
		    eventLimit: true, // allow "more" link when too many events
			 eventSources: [

            // your event source
            {
                url: '/manage/clipbydate',
                type: 'POST',
                
                error: function() {
                    alert('there was an error while fetching events!');
                },
                color: 'yellow',   // a non-ajax option
                textColor: 'black' // a non-ajax option
            }

        // any other sources...

            ],loading: function (bool) {
               //alert('events are being rendered'); // Add your script to show loading
            },
            eventAfterAllRender: function (view) {
               // alert('all events are rendered'); // remove your loading 
            },
            eventRender: function (event, element) {
                //element.attr('href', "javascript:html5Lightbox.showLightbox(2, '/adsclips/dBz9aSlMTjRGK5ZC.mp4', 'Gioi thieu SAMCO CITY CNG - 00:02:01.6', 720, 405, '/adsclips/dBz9aSlMTjRGK5ZC.mp4');");
            },
            eventClick: function(event) {
                if (event.id) {
                    html5Lightbox.showLightbox(2, '/adsclips/'+event.unique_name, event.title, 720, 405, '/adsclips/'+event.unique_name);
                    return false;
                }
            }
		});   
    /* ADDING EVENTS */
    var currColor = "#3c8dbc"; //Red by default
    //Color chooser button
    var colorChooser = $("#color-chooser-btn");
    $("#color-chooser > li > a").click(function (e) {
      e.preventDefault();
      //Save color
      currColor = $(this).css("color");
      //Add color effect to button
      $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
    });
    $("#add-new-event").click(function (e) {
      e.preventDefault();
      //Get value and make sure it is not null
      var val = $("#new-event").val();
      if (val.length == 0) {
        return;
      }

      //Create events
      var event = $("<div />");
      event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
      event.html(val);
      $('#external-events').prepend(event);

      //Add draggable funtionality
      ini_events(event);

      //Remove event from text input
      $("#new-event").val("");
    });
  });
</script>
<!-- Sparkline -->
<script src="/bes/manage/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="/bes/manage/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/bes/manage/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="/bes/manage/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="/bes/manage/plugins/chartjs/Chart.min.js"></script>
<!-- HTML 5 Video Previewer -->
<script src="/bes/manage/dist/js/html5lightbox.js"></script>
</body>
</html>
