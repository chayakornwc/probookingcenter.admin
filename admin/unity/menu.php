 <aside class="aside">
    <!-- START Sidebar (left)-->
    <div class="aside-inner">
    <nav data-sidebar-anyclick-close="" class="sidebar">
        <!-- START sidebar nav-->
        <ul class="nav">

            <!-- Iterates over all sidebar items-->
            <li class="nav-heading ">
                <span data-localize="sidebar.heading.HEADER">Menu</span>
            </li>
            <li class="li-menu"  menu="dashboard">
                <a href="../dashboard" title="Dashboard">
                    <em class="fa fa-line-chart"></em>
                    <span data-localize="sidebar.nav.WIDGETS">Dashboard</span>
                </a>
            </li>


            <?php

                if($_SESSION['login']['menu_2'] == 1){

                    echo '<li class="li-menu"  menu="noti_booking">
                            <a href="../noti_booking" title="แจ้งเตือนการจอง">
                                <em class="icon-bell"></em>
                                <span data-localize="sidebar.nav.WIDGETS">แจ้งเตือนการจอง</span>
                            </a>
                        </li>';

                }

                if($_SESSION['login']['menu_3'] == 1){
                    echo '<li class="li-menu"  menu="booking">
                            <a href="../booking" title="จัดการการจองทัวร์">
                                <em class="icon-notebook"></em>
                                <span data-localize="sidebar.nav.WIDGETS">จัดการการจองทัวร์</span>
                            </a>
                        </li>';
                }
                 if($_SESSION['login']['menu_15'] == 1){
                    echo '<li class="li-menu"  menu="manage_booking_detail">
                                <a href="../manage_booking_detail" title="รายละเอียดการ Booking">
                                    <em class="icon-notebook"></em>
                                    <span data-localize="sidebar.nav.WIDGETS">รายละเอียดการ Booking</span>
                                </a>
                            </li>';
                }
               if($_SESSION['login']['menu_11'] == 1){
                    echo '<li class="li-menu"  menu="manage_payment">
                                <a href="../manage_payment" title="แจ้งการชำระเงิน">
                                    <em class="fa fa-money "></em>
                                    <span data-localize="sidebar.nav.WIDGETS">แจ้งการชำระเงิน</span>
                                </a>
                            </li>';
                }

                if($_SESSION['login']['menu_4'] == 1){
                    echo '<li class="li-menu"  menu="manage_period">
                            <a href="../manage_period" title="พีเรียด">
                                <em class="icon-calendar"></em>
                                <span data-localize="sidebar.nav.WIDGETS">ซีรี่ย์ ทัวร์</span>
                            </a>
                        </li>';
                }
                
                if($_SESSION['login']['menu_14'] == 1){
                    echo '<li class="li-menu"  menu="manage_travel">
                                <a href="../manage_travel" title="จัดการทัวร์">
                                    <em class="icon-directions"></em>
                                    <span data-localize="sidebar.nav.WIDGETS">จัดการทัวร์</span>
                                </a>
                            </li>';
                }
               


                

                

                if($_SESSION['login']['menu_5'] == 1){
                    echo '<li class="li-menu"  menu="manage_review">
                            <a href="../manage_review" title="รีวิวทัวร์">
                                <em class="icon-social-twitter"></em>
                                <span data-localize="sidebar.nav.WIDGETS">รีวิวทัวร์</span>
                            </a>
                        </li>';
                }

                if($_SESSION['login']['menu_6'] == 1){
                    echo '<li class="li-menu"  menu="manage_contact">
                            <a href="../manage_contact" title="จัดการ Contact US">
                                <em class="icon-speech"></em>
                                <span data-localize="sidebar.nav.WIDGETS">จัดการ Contact US</span>
                            </a>
                        </li>';
                }



                
                

                

                if($_SESSION['login']['menu_7'] == 1){
                    echo '<li class="li-menu"  menu="manage_user">
                            <a href="../manage_user" title="จัดการผู้ใช้งาน">
                                <em class="icon-user"></em>
                                <span data-localize="sidebar.nav.WIDGETS">จัดการผู้ใช้งาน</span>
                            </a>
                        </li>';
                }

                if($_SESSION['login']['menu_8'] == 1){
                    echo '<li class="li-menu"  menu="manage_agency">
                                <a href="../manage_agency" title="จัดการเอเจนซี่">
                                    <em class="icon-people"></em>
                                    <span data-localize="sidebar.nav.WIDGETS">จัดการเอเจนซี่</span>
                                </a>
                            </li>';
                }

                if($_SESSION['login']['menu_9'] == 1){
                    echo '<li class="li-menu"  menu="report">
                            <a href="../report" title="รายงาน">
                                <em class="icon-book-open"></em>
                                <span data-localize="sidebar.nav.WIDGETS">รายงาน</span>
                            </a>
                        </li>';
                }

                if($_SESSION['login']['menu_10'] == 1){
                    echo '<li class="li-menu"  menu="setting">
                            <a href="../setting" title="ตั้งค่า">
                                <em class="icon-settings"></em>
                                <span data-localize="sidebar.nav.WIDGETS">ตั้งค่า</span>
                            </a>
                        </li>';
                }

            ?>

        </ul>
        <!-- END sidebar nav-->
    </nav>
    </div>
    <!-- END Sidebar (left)-->
</aside>