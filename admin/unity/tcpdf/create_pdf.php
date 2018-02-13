<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>
	<form action="test.php" method="post" id="form_main">
		<table>
			<caption>table title and/or explanatory text</caption>
			
			<tbody>
				<tr>
					<td>ส่วนราชการ</td>
					<td>
						<input type="text" name="data[]" value="ผตส.กอง๗(โทร ๒๓๘๖)">
					</td>
				</tr>
				<tr>
					<td>ที่</td>
					<td>
						<input type="text" name="data[]" value="/๘๖">
					</td>
				</tr>
				<tr>
					<td>วันที่</td>
					<td>
						<input type="text" name="data[]" value="ก.พ.๕๙">
					</td>
				</tr>
				<tr>
					<td>เรื่อง</td>
					<td>
						<input type="text" name="data[]" value="รายงานสรุปผลการปฏิบติงานประจำเดือน ม.ค.๕๙">
					</td>
				</tr>
				<tr>
					<td>เรียน</td>
					<td>
						<input type="text" name="data[]" value="CEO">
					</td>
				</tr>
				<tbody id="contant_tbody">
					<tr>
						<td>เนื้อความที่ 1</td>
						<td>
							<textarea name="contant[0]" class="contant" style="overflow:hidden" rows="5" cols="67" wrap="on" ></textarea>
							<input type="hidden" name="contant_html[0]" value="testaaaa" readonly>
						</td>
					</tr>
					<tr>
						<td>เนื้อความที่ 2</td>
						<td>
							<textarea name="contant[1]" class="contant" style="overflow:hidden" rows="5" cols="67" wrap="on" ></textarea>
							<input type="hidden" name="contant_html[1]" value="testbbbb" readonly>
						</td>
					</tr>
				</tbody>
				<!--<tr>
					<td>เนื้อความที่ 2</td>
					<td>
						<textarea name="contant[]" rows="4" cols="76"></textarea>
					</td>
				</tr>-->
				<tr>
					<td>ยศ</td>
					<td>
						<input type="text" name="data[]" value="CEO">
					</td>
				</tr>
				<tr>
					<td>ชื่อ</td>
					<td>
						<input type="text" name="data[]" value="ฐิติภัทร ขาวค้างพลู">
					</td>
				</tr>
				<tr>
					<td>ตำแหน่ง ทำการแทน</td>
					<td>
						<input type="text" name="data[]" value="CEO">
					</td>
				</tr>
				<tr>
					<td>ตำแหน่ง</td>
					<td>
						<input type="text" name="data[]" value="ประธานกรรมการ">
					</td>
				</tr>
			</tbody>
		</table>

		


		<button type="submit" >Submit Form</button>
		<button type="button" id="submit">Replace Jquery</button>
		<button type="button" id="add_contant">เพิ่มเนื้อหา</button>
	</form>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
	<script>

		$(document).ready(function($) {


			$('textarea').css('resize', 'none');

			$('#submit').click(function(event) {

				replace_n();
				$('#form_main').submit();
				
			});

		});

		function replace_n(){
			i = 0;
			$('.contant').each(function(index, el) {

					
					replace_str = $(this).val();
					replace_str = replace_str.replace(/\n/ig,'<br>');
					alert(replace_str);
					$('input[name="contant_html['+i+']"').val(replace_str);
					

					i++;
			});
		}

	</script>
</body>
</html>