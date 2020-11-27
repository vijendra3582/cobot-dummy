<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <title>{{config('app.name')}}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td>
<table width="600" border="0" cellspacing="0" cellpadding="12" align="center" style="border:1px solid #ccc; font-family: \'Helvetica Neue\', Helvetica, Arial, \'sans-serif\';">
<tbody>
<tr>
<td align="center" bgcolor="#f5f7f4" style="padding: 10px 0;"><img width="100" style=" width: 100px; margin:0; padding:0; border:none; display:block;" border="0" src="{{asset('/logo.png')}}" alt="{{config('app.name')}}"/></td>
</tr>
<tr>
<td bgcolor="#898d8e" style="color: #fff; text-align: center; font-size: 16px; font-weight: 700;">New query recieved from website as below:</td>
</tr>
<tr>
<td style="padding: 20px; font-size: 14px; color: #333; line-height: 20px; border-right: 0px solid #849f92; border-bottom: 0px solid #08509e;">


<strong>Name:</strong> {{$data_mail['name']}}<br>
<strong>Email:</strong> {{$data_mail['email']}}<br>

@if(!empty($data_mail['phone']))
<strong>Phone:</strong> {{$data_mail['phone']}}<br>
@endif

@if(!empty($data_mail['investor_type']))
<strong>Investor Type:</strong> {{$data_mail['investor_type']}}<br>
@endif

<strong>Message:</strong> {{$data_mail['message']}}<br>

</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table> 
</body>
</html>