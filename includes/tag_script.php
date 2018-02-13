
<!-- SWEET ALERT-->
<script src="../includes/js/sweetalert/dist/sweetalert.min.js"></script>


<!-- JQUERY-->
<script src="../admin/unity/vendor/jquery/dist/jquery.js"></script>
<!-- BOOTSTRAP-->
<script src="../admin/unity/vendor/bootstrap/dist/js/bootstrap.js"></script>
<!-- <script src="../includes/js/bootstrap.min.js"></script> -->

<!-- STORAGE API-->
<script src="../admin/unity/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
<!-- JQUERY EASING-->
<script src="../admin/unity/vendor/jquery.easing/js/jquery.easing.js"></script>
<!-- SLIMSCROLL-->
<script src="../admin/unity/vendor/slimScroll/jquery.slimscroll.min.js"></script>
<!-- CLASSY LOADER-->
<script src="../admin/unity/vendor/jquery-classyloader/js/jquery.classyloader.min.js"></script>
<!-- DATATABLES-->
<script src="../admin/unity/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="../admin/unity/vendor/datatables-colvis/js/dataTables.colVis.js"></script>
<script src="../admin/unity/vendor/datatables/media/js/dataTables.bootstrap.js"></script>


<!-- SELECT2-->
<script src="../includes/js/select2/dist/js/select2.js"></script>

<!-- CHOSEN-->
<script src="../includes/js/chosen_v1.2.0/chosen.jquery.min.js"></script>
<script src="../includes/js/jquery-ui.min.js"></script>

<script src="../includes/js/jquery.blockUI.js"></script>
<script src="../includes/js/idangerous.swiper.min.js"></script>
<script src="../includes/js/jquery.viewportchecker.min.js"></script>
<script src="../includes/js/isotope.pkgd.min.js"></script>
<script src="../includes/js/jquery.mousewheel.min.js"></script>
<script src="../includes/js/all.js"></script>



<!-- FILESTYLE-->
<script src="../admin/unity/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js"></script>

<!--<script src="../includes/js/bootstrap-filestyle.min.js"></script> -->


<script>

    $(document).ready(function(){
        ACTIVE_MENU();
        CREATE_INPUT_DATE_SEARCH();
         $('select').chosen();

       $(":file").filestyle();
        
        $('body').on('click','.btn-number',function(e){
            e.preventDefault();
            
            fieldName = $(this).attr('data-field');
            type      = $(this).attr('data-type');
            var input = $("input[name='"+fieldName+"']");
            var currentVal = parseInt(input.val());
            if (!isNaN(currentVal)) {
                if(type == 'minus') {
                    
                    if(currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    } 
                    if(parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if(type == 'plus') {

                    if(currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if(parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        })

        $('body').on('focusin','.input-number',function(){
            $(this).data('oldValue', $(this).val());
        })

        $('body').on('change','.input-number',function(){

            minValue =  parseInt($(this).attr('min'));
            maxValue =  parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());
            
            name = $(this).attr('name');
            if(valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
            } else {
                
                swal('กรุณากรอกข้อมูลจำนวนที่นั่งขั้นต่ำ 1 ที่นั่ง','','warning');
                $(this).val($(this).data('oldValue'));
            }
            if(valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
            } else {
                swal('กรุณากรอกข้อมูลจำนวนที่นั่งไม่เกิน 1,000 ที่นั่ง','','warning');
                $(this).val($(this).data('oldValue'));
            }
                
        });
      
        $('body').on('keydown','.input-number',function(e){
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) || 
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                        // let it happen, don't do anything
                        return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
        });



    })
    
    function CREATE_INPUT_DATE_SEARCH(){
        $('.date_search').datepicker({
            dateFormat: 'dd/mm/yy',
          //  format          : 'dd/mm/yyyy'
           locale          : 'th',
            
            
        });
         $('.date').datepicker({
            dateFormat: 'dd/mm/yy',
          //  format          : 'dd/mm/yyyy'
           locale          : 'th',
            
            
        });
        
    }

    function ACTIVE_MENU(){
       var folder =  window.location.pathname.replace(/[\\\/][^\\\/]*$/, '');

       folder = folder.replace('/' ,'');
       $('#'+folder).addClass('active');
    }
    
    function GET_LIST_TABLE(DATA,URL){
        $('#content-wrapper').addClass('whirl double-up');
        
        $.post(URL,DATA,function(data,status,xhr){
            var DATA_J = JSON.parse(data);

            console.log(DATA_J);
            $('#interface_table').html(DATA_J.interface_table);
            $('#interface_pagination').html(DATA_J.paginate_list);

            setTimeout(function() {
                $('#content-wrapper').removeClass('whirl double-up');
                
            }, 500);

        })
    }
     function GET_LIST_TABLE_NO_LIMIT(DATA,URL){
        $('#content-wrapper').addClass('whirl double-up');
        
        $.post(URL,DATA,function(data,status,xhr){
            var DATA_J = JSON.parse(data);

            console.log(DATA_J);
            $('#interface_table').html(DATA_J.interface_table);
           // $('#interface_pagination').html(DATA_J.paginate_list);

            setTimeout(function() {
                $('#content-wrapper').removeClass('whirl double-up');
                
            }, 500);

        })
    }

    function PAGE_SELECT(page_number){
        page = parseInt(page_number);
        search_page('next_or_back');
    }

    function IS_CHECKED(element){
        if(element.is(':checked')){
            return element.val();
        }
        else{
            return 9999999;
        }
    }

    function SWEET_ALERT_INSER_UPDATE(status,type_method){

        if(parseInt(status) == 200){
            type = 'success';
            
            if(type_method == 1){
                text = 'เพิ่มข้อมูลสำเร็จ';
            }
            else if(type_method == 2){
                 text = 'แก้ไขข้อมูลสำเร็จ';
            }
            else if(type_method == 3){
                 text = 'อนุมัติข้อมูลสำเร็จ';
            }
             else if(type_method == 4){
                 text = 'ยกเลิกรายการข้อมูลสำเร็จ';
            }
        }
        else{

            type = 'warning';
            if(type_method == 1){
                text = 'เพิ่มข้อมูลไม่สำเร็จ กรุณาลองใหม่ภายหลัง';
            }
            else if(type_method == 2){
                text = 'แก้ไขข้อมูลไม่สำเร็จ กรุณาลองใหม่ภายหลัง';
            }
            else if(type_method == 3){
                text = 'อนุมัติข้อมูลไม่สำเร็จ กรุณาลองใหม่ภายหลัง';
            }
            else if(type_method == 4){
                text = 'ยกเลิกรายการไม่สำเร็จ กรุณาลองใหม่ภายหลัง';
            }
            
        }

        sweetAlert(text, '', type);
        
    }



    function SWEET_ALERT_CONFIRM(title,text,fn){
        swal({
            title                   : title,
            text                    : text,
            type                    : "warning",
            showCancelButton        : true,
            confirmButtonClass      : "btn-primary",
            confirmButtonText       : "ยืนยัน",
            cancelButtonText        : "ยกเลิก",
            closeOnConfirm          : true
        },
        function(){

            setTimeout(function(event){
                
                eval(fn);
                
            },200);
        });
    }



    function CREATE_INPUT_DATE(){
         $('.date').datetimepicker({
            
            format          : 'D/M/YYYY',
            locale          : 'th',
            
             
        });
    }

    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    function html_status_book(status){
        if(parseInt(status) == 0){ //จอง
            html = '<div class="label bg-green-light">จอง</div>';
        }
        else if(parseInt(status) == 5){//รอที่นั่ง
            html = '<div class="label bg-yellow">รอที่นั่ง</div>';
        }
        else if(parseInt(status) == 10){//แจ้ง invoice
            html = '<div class="label bg-pink-light">แจ้ง Invoice</div>';
        }
        else if(parseInt(status) == 20){//ชำระเงินมัดจำไม่ครบ
            //html = '<div class="label bg-primary-light">มัดจำบางส่วน</div>';
            html = '<div class="label bg-primary-light">ชำระ มัดจำ(Deposite)บางส่วน</div>';
        }
        else if(parseInt(status) == 25){//ชำระเงินมัดจำครบ
    //		html = '<div class="label bg-primary-dark">มัดจำเต็มจำนวน</div>';
            html = '<div class="label bg-primary-dark">ชำระ มัดจำ(Deposite)เต็มจำนวน</div>';
        }
        else if(parseInt(status) == 30){//ชำระเงินเต็มจำนวนไม่ครบ
    //		html = '<div class="label bg-success-light">Full payment บางส่วน</div>';
            html = '<div class="label bg-success-light">ชำระ เต็มจำนวน(Full payment)บางส่วน</div>';
        }
        else if(parseInt(status) == 35){//ชำระเงินเต็มจำนวนครบ
    //		html = '<div class="label bg-success">Full payment เต็มจำนวน</div>';
            html = '<div class="label bg-success">ชำระ เต็มจำนวน(Full payment)เต็มจำนวน</div>';
        }
        else if(parseInt(status) == 40 ){//ยกเลิกการจอง
            html = '<div class="label bg-danger-dark">ยกเลิกการจอง</div>';
        }
        else{
            html = '';
        }
        return html;
    }
    
    function block_ui(){
        $.blockUI({ css: { 
            border: 'none', 
            padding: '5px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff',
        } }); 
 
    }

    function unblock_ui(){
        $.unblockUI();
    }



</script> 