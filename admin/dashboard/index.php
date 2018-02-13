<?php include_once('../unity/main_core.php');?>





<!DOCTYPE html>
<html lang="en">
<head>
   <?php include_once('../unity/tag_head.php'); ?>

    <!-- lib -->
   <?php include_once('../unity/lib_heightchart.php');?>
   <!-- lib -->

   <style>
        .chart {
            min-width: 100%;
            max-width: 100%;
            height: 400px;
            margin: 0 auto
        }
        .ui-status{margin:2px;font-size:14px;display: inline-block;color:#000}

       .ui-status.s-10{color:#f878f2}

       .ui-status.s-20{color:#51c6ea}
       .ui-status.s-25{color:#2f80e7}

       .ui-status.s-30{color:#43d967}
       .ui-status.s-35{color:#27c24c}
   </style>
</head>

<body >
   <div class="wrapper">
      <!-- top navbar-->
      <header class="topnavbar-wrapper">
         <!-- START Top Navbar-->
         <?php include_once('../unity/navigation.php');?>
         <!-- END Top Navbar-->
      </header>
      <!-- sidebar-->
     
      <?php include_once('../unity/menu.php');?>
      
      <!-- Main section-->
      <section>
         <!-- Page content-->
         <div class="content-wrapper" id="content-wrapper">
            <div class="content-heading">
               <!-- START Language list-->
               <div class="pull-right">
                 
               </div>
               <!-- END Language list    -->
               <font id="title_page"></font>
               <small data-localize="dashboard.WELCOME"></small>
            </div>
			
			<div class="row dashboard_manager" id="">
        
                <div class="col-lg-3 col-sm-6">
                    <!-- START widget-->
                    <div class="panel widget bg-primary">
                        <div class="row row-table">
                        <div class="col-xs-4 text-center bg-primary-dark pv-lg">
                            <em class="fa fa-money  fa-3x"></em>
                        </div>
                        <div class="col-xs-8 pv-lg">
                            <div class="h2 mt0" id = "sumtotal">0</div>
                            <div class="text-uppercase">ยอดขาย (บาท) เดือนปัจจุบัน</div>
                        </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-sm-6">
                    <!-- START widget-->
                    <div class="panel widget bg-success">
                        <div class="row row-table">
                        <div class="col-xs-4 text-center bg-success-light pv-lg">
                            <em class="icon-people fa-3x"></em>
                        </div>
                        <div class="col-xs-8 pv-lg">
                            <div class="h2 mt0" id = "sumqty">0</div>
                            <div class="text-uppercase">ยอดขาย (ที่นั่ง) เดือนปัจจุบัน</div>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <!-- START widget-->
                    <div class="panel widget bg-info">
                        <div class="row row-table">
                        <div class="col-xs-4 text-center bg-info-light pv-lg">
                            <em class="fa fa-list-alt fa-3x"></em>
                        </div>
                        <div class="col-xs-8 pv-lg">
                            <div class="h2 mt0" id = "sumperiod">0</div>
                            <div class="text-uppercase" >จำนวน Period ที่เปิดอยู่</div>
                        </div>
                        </div>
                    </div>
                </div>

                    
                <div class="col-lg-3 col-sm-6">
                    <!-- START date widget-->
                    <div class="panel widget">
                        <div class="row row-table">
                        <div class="col-xs-4 text-center bg-green pv-lg">
                            <!-- See formats: https://docs.angularjs.org/api/ng/filter/date-->
                            <div data-now="" data-format="MMMM" class="text-sm"></div>
                            <br>
                            <div data-now="" data-format="D" class="h2 mt0"></div>
                        </div>
                        <div class="col-xs-8 pv-lg">
                            <div data-now="" data-format="dddd" class="text-uppercase"></div>
                            <br>
                            <div data-now="" data-format="h:mm" class="h2 mt0"></div>
                            <div data-now="" data-format="a" class="text-muted text-sm"></div>
                        </div>
                        </div>
                    </div>
                    <!-- END date widget    -->
                </div>

			</div>

            <div class="row ">

                <div class="col-lg-12 col-sm-12 dashboard_manager">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="chart" id="chart_sale_all_year"></div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="chart" id="chart_top_agency"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="chart" id="chart_top_sale"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="chart" id="chart_top_series"></div>
                        </div>
                    </div>

                </div>
                
                

            </div>

                                  <!-- TABLE AREA -->
            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <!-- START table-responsive-->
                <div class="panel-body" id="interface_table">
                </div>
            
                <div class="panel-body" id="interface_pagination">
                    
                
                </div>

            </div>


             <!-- BTN AREA -->
            


            <hr>

        
        
         </div>
      </section>
     
     <?php include_once('../unity/footer.php');?>


   </div>
   


   <?php include_once('../unity/tag_script.php');?>

    <!-- HEIGHTCHART-->
    <script src="../unity/Highcharts-5.0.7/code/highcharts.js"></script>
    <script src="../unity/Highcharts-5.0.7/code/highcharts-more.js"></script>



    <script>
        var url_search  = 'action_dashboard.php';
        var data_search;
        var page       = 0;
        var page_limit = 99;
        $(document).ready(function(){
            check_dashboard();
            get_data_graph();
            get_sum();
            search_page();
        })
        function search_page(){
            data_search = {
                offset      : page,
                method      : 2

            };

            GET_LIST_TABLE(data_search,url_search);

        }
        function get_sum(){
           
          $.post('action_dashboard.php',
            {
                method      : 99,
            },function(data,textStatus,xhr){
                data_j = JSON.parse(data);
                $('#sumtotal').html(data_j.sumtotal);
                $('#sumqty').html(data_j.sumqty);
                $('#sumperiod').html(data_j.sumperiod);
            })

         }
        function check_dashboard(){
            dashboard_manager = '<?php echo $_SESSION["login"]["menu_1"];?>';
            if(dashboard_manager == '0'){
                $('.dashboard_manager').remove();
            }
        }
        function get_data_graph(){
            

            $.post('action_dashboard.php',
            {
                method : 1
            },function(data,textStatus,xhr){
                    data_j = JSON.parse(data);

                    create_chart_column(data_j.obj_sale);
                    create_chart_column(data_j.obj_agency);
                    create_chart_column(data_j.obj_series);
                    create_chart_line(data_j.sale_all_year);

            });

        }

        function create_chart_column(obj){

            id              = obj.id;
            title           = obj.title;
            data            = obj.data;
            tooltip_format  = obj.tooltip_format;
            color           = obj.color;


            Highcharts.chart(id, {
                colors: [color],
                chart: {
                    type: 'column'
                },
                title: {
                    text: title
                },
                subtitle: {
                    text: ''
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    type: 'category',
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat : tooltip_format
                },
                series: [{
                    name: 'Population',
                    data: data
                    
                }]
            });


        }



        function create_chart_line(obj){

            id          =   obj.id;
            title       =   obj.title;
            categories  =   obj.categories;
            data        =   obj.data;



            Highcharts.chart(id, {
                chart: {
                    type: 'line'
                },
                title: {
                    text: title
                },
                credits: {
                    enabled: false
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: categories
                },
                yAxis: {
                    title: {
                        text: ''
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    }
                },
                series: data




            });


        }
        
    </script>

</body>

</html>