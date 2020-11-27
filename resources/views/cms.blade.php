<!DOCTYPE html>
<html ng-app="truemarkApp">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ config('app.name') }}</title>

<head>
  <link rel="icon" href="{{asset('favicon.png')}}" sizes="16x16" type="image/png">
  <link href="{{asset('admin_js_css/node_modules/angular-ui-switch/angular-ui-switch.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('admin_js_css/node_modules/angularjs-datepicker/src/css/angular-datepicker.css')}}" rel="stylesheet" type="text/css" />
  <script src="{{asset('admin_js_css/truemark_js/jquery-1.10.1.min.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/jquery-migrate-1.2.1.min.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angular/angular.min.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angular-route/angular-route.min.js')}}"></script>
  <script src="{{asset('admin_js_css/ckeditor/ckeditor.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angular-ckeditor-master/angular-ckeditor.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/ng-file-upload/dist/ng-file-upload-shim.min.js')}}"></script>
  <!-- for no html5 browsers support -->
  <script src="{{asset('admin_js_css/node_modules/ng-file-upload/dist/ng-file-upload.min.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angularjs-datepicker/src/js/angular-datepicker.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angular-sanitize/angular-sanitize.min.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angular-ui-switch/angular-ui-switch.min.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/checklist-model/checklist-model.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angular-utils-pagination/dirPagination.js')}}"></script>
  <link rel="stylesheet" href="{{asset('admin_js_css/node_modules/angularjs-color-picker/dist/angularjs-color-picker.min.css')}}" />
  <script src="{{asset('admin_js_css/node_modules/tinycolor2/dist/tinycolor-min.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angularjs-color-picker/dist/angularjs-color-picker.min.js')}}"></script>
  <link rel="stylesheet" href="{{asset('admin_js_css/node_modules/ng-dialog/css/ngDialog.css')}}" />
  <link rel="stylesheet" href="{{asset('admin_js_css/node_modules/ng-dialog/css/ngDialog-theme-default.css')}}" />
  <!--link rel="stylesheet" href="node_modules/ng-dialog/css/ngDialog-theme-flat.css" /-->
  <script src="{{asset('admin_js_css/node_modules/ng-dialog/js/ngDialog.min.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angular-filter-master/dist/angular-filter.min.js')}}"></script>
  <link rel="stylesheet" href="{{asset('admin_js_css/node_modules/ngWYSIWYG/dist/editor.min.css')}}" />
  <script src="{{asset('admin_js_css/node_modules/ngWYSIWYG/dist/wysiwyg.min.js')}}"></script>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="{{asset('admin_js_css/node_modules/bootstrap/dist/css/bootstrap.min.css')}}" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500,600,700,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('admin_js_css/truemark_css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin_js_css/truemark_css/all-skins.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin_js_css/fonts_truemark/fonts.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin_js_css/truemark_css/style.css')}}">
  <!-- Optional theme -->
  <!-- <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous"> -->
  <!-- Latest compiled and minified JavaScript -->
  <script src="{{asset('admin_js_css/node_modules/bootstrap/dist/js/bootstrap.min.js')}}" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <!-- FOR TIME PICKER ------------>
  <script src="{{asset('admin_js_css/node_modules/ui-bootstrap-tpls-1.3.3.min.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/timepicker/dist/datetime-picker.js')}}"></script>
  <!-- FOR TIME PICKER ENDS ------------>
  <script src="{{asset('admin_js_css/truemark_js/app.js')}}"></script>
  <!-- ROUTING ---->
  <script src="{{asset('admin_js_css/truemark_js/routing.js')}}"></script>
  <!-- ROUTING ---->
  <!-- CONTROLLERS ---->
  <script src="{{asset('admin_js_css/truemark_js/controllers/welcome.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/change-pass.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/home-content.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/about-us.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/general-setting.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/contact-us-db.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/subscribe-db.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/team-member.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/list-team-members.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/list-news.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/news.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/list-home-news.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/privacy-policy.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/list-fund.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/fund.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/add-fund-files.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/add-fund-detail.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/add-fund-data-and-pricing.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/add-fund-holdings.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/add-fund-distributor.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/add-performance.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/list-resource.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/resource.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/sitePopup.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/news-disclosure.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/resource-disclosure.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/fund-content-disclosure.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/fund-outcome-content-disclosure.js')}}"></script>
  <script src="{{ asset('admin_js_css/truemark_js/controllers/outcomePeriodValues.js') }}"></script>
  <script src="{{ asset('admin_js_css/truemark_js/controllers/currentOutcomePeriod.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/controllers/interim-fund-page.js') }}"></script>
  <!-- CONTROLLERS ---->
  <!-- FACTORY ---->
  <script src="{{asset('admin_js_css/truemark_js/factory/contactusdbDetail.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/factory/subscribedbDetail.js')}}"></script>
  <script src="{{asset('admin_js_css/truemark_js/factory/TeamMemberDetail.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/factory/NewsDetail.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/factory/FundDetail.js') }}"></script>
  <script src="{{asset('admin_js_css/truemark_js/factory/ResourceDetail.js') }}"></script>
  <!-- FACTORY ---->
  <link rel="stylesheet" href="{{asset('admin_js_css/font-awesome/css/font-awesome.min.css')}}">
  <script language="javascript" type="text/javascript" src="{{asset('admin_js_css/node_modules/Chart.js')}}"></script>
  <script src="{{asset('admin_js_css/node_modules/angular-chart.js/angular-chart.min.js')}}"></script>
  <style>
    [ng\:cloak],
    [ng-cloak],
    [data-ng-cloak],
    [x-ng-cloak],
    .ng-cloak,
    .x-ng-cloak {
      display: none !important;
    }

    .cl-negative {
      color: #ff0000;
    }

    .fade-in-out {
      transition: all linear 0.5s;
      -webkit-transition: all linear 0.5s;
      /* Safari */
      opacity: 1;
    }

    .fade-in-out.ng-hide {
      opacity: 0;
    }

    .print-page-break {
      page-break-after: always;
    }

    thead {
      color: #ffffff;
      background: #a76112;
    }

    .table-striped>tbody>tr.rank0 {
      vertical-align: middle;
      background-color: #e0e7fd;
    }

    .table-striped>tbody>tr.rank1 {
      vertical-align: middle;
      background-color: #ebedf5;
    }

    .table-striped>tbody>tr.rank2 {
      vertical-align: middle;
      background-color: #f5f6f7;
    }
  </style>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.navbar-collapse.in', function(e) {
        if ($(e.target).is('a')) {
          $(this).collapse('hide');
        }
      });
      $(".content-wrapper").css('min-height', $(window).height() - 70);
    });
  </script>
</head>

<body class="layout-top-nav">
  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header" style="width: 100%;">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
            <img class="logo" src="{{asset('logo.svg')}}" />
          </a>
          <ul class="nav navbar-nav navbar-right small" style="float: right!important;">
            <li>
              <a href="javascript: void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-2x" aria-hidden="true"></i> &nbsp;&nbsp;</a>
              <ul class="dropdown-menu dp">
                <li>
                  <a href="#/changePassword" class="dropdown-toggle"><i class="fa fa-lock" aria-hidden="true"></i> &nbsp;&nbsp;Change Password</a>
                </li>
                <li>
                  <a href="{{route('logout')}}" class="dropdown-toggle"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="navbar-collapse collapse">
      <nav class="main-nav d-xl-flex justify-content-center">
        <ul class="nav navbar-nav navbar-left">
          <li><a href="#/homeContent">Home Page</a> </li>
          <li><a href="#/privacyPolicy">Privacy Policy</a></li>
          <li>
            <a href="javascript: void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About Us <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#/aboutUs">Why Cobot/Our Approach/Disclosure</a></li>
              <li><a href="#/listTeam">Team</a></li>
            </ul>
          </li>
          <!-- /*** Master Menu ***/ -->
          <li>
            <a href="javascript: void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">News <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#/newsContentDisclosure">Content/Disclosure</a></li>
              <li><a href="#/listNews">Add/Manage</a></li>
              <li><a href="#/newsHomepagePosition">Homepage Position</a></li>
            </ul>
          </li>
          <li>
            <a href="javascript: void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Resources <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#/resourceContentDisclosure">Content/Disclosure</a></li>
              <li><a href="#/listResources">Add/Manage</a></li>
            </ul>
          </li>
          <!-- <li><a href="#/listFunds">Funds</a></li>  -->
          <li>
            <a href="javascript: void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Fund <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#/fundContentDisclosure">Fund Content</a></li>
              <li><a href="#/structuredOutcomeEtfDisclosure">Structured Outcome Content/Disclosure</a></li>
              <li><a href="#/listFunds">Add/Manage</a></li>
            </ul>
          </li>
          <!-- <li><a href="#/listContactUs">Contact Us DB</a></li> -->
          <li><a href="#/interim-fund-page">Interim Fund Page</a></li>
          <li>
            <a href="javascript: void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">DBs <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#/listContactUs">Contact Us </a></li>
              <li><a href="#/listSubscribe">Subscribe</a></li>
            </ul>
          </li>
          <li><a href="#/generalSetting">General Settings</a></li>
          <li>
            <a href="javascript: void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Site Popup <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#/sitePopup/advisorSite">Advisor Site</a></li>
              <li><a href="#/sitePopup/externalSite">External Site</a></li>
              <li><a href="#/sitePopup/externalNewsSite">External News Site</a></li>
              <li><a href="#/sitePopup/socialFacebookSite">Social Facebook Site</a></li>
              <li><a href="#/sitePopup/socialTwitterSite">Social Twitter Site</a></li>
              <li><a href="#/sitePopup/socialLinkedInSite">Social LinkedIn Site</a></li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
    <!--/.nav-collapse -->
  </header>
  <!-- /*** ng view section ***/ -->
  <div class="content-wrapper" style="min-height: 600px; padding-top: 15px;">
    <div class="container theme-showcase" role="main">
      <section class="content-header">
      </section>
      <div ng-view></div>
    </div>
  </div> <!-- content-wrapper end -->
  <!-- /*** footer  section ****/ -->
  <footer class="main-footer">
    <div class="container text-center">
      &copy; <?php echo date("Y"); ?> <a href="{{url('/')}}" target="_blank"> {{ config('app.name') }}</a>. All rights reserved.
    </div>
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- /.container -->
  </footer>
</body>

</html>