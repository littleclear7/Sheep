<html>
<head>
    <title>Information</title>
    <meta charset="utf-8"/>
    <link href="../css/result.css" rel="stylesheet">
</head>

<body>
<h1>Your informations:<br /></h1>
<?php
    $username=$_GET['username'];
    $password=$_GET['password'];

    if(!$username||!$password)
    {
        echo "You have not entered username or password. Please go back and enter both of them.";
        exit;
    }
    if (preg_match("/^.*((union)|(select)|and|or)+.*$/",$username))
    {
        $username=preg_replace('/(union)|(select)|and|or/','',$username);
    }

    while (preg_match("/^.*((drop)|update)+.*$/i",$username))
    {
        $username=preg_replace('/(drop|update)/i','',$username);
    }

    $config = fopen("../../configure",'r');
    $config_username =  ltrim(rtrim(fgets($config)));
    $config_password = ltrim(rtrim(fgets($config)));
    $config_username = substr($config_username,strpos($config_username,':')+1);
    $config_password = substr($config_password,strpos($config_password,':')+1);
                

                
    @ $db=new mysqli('localhost',$config_username,$config_password,'Sheep');


    if (mysqli_connect_errno())
    {
        echo "Error:Could not connect to database,please try again later";
    }

    $query="select * from users where username='".$username."' and password='".$password."'";
    $result=$db->query($query);
    $num_result=$result->num_rows;

    if (!$num_result){
        echo "Your username or password is wrong.Please go back and enter again.";
        exit;
    }

    echo " <table border=\"1\" width=\"4\">
                    <tr>
                        <th>USERNAME</th>
                        <th>PASSWORD</th>
                    </tr>";

    for ($i=0;$i<$num_result;$i++){
        $result_info=$result->fetch_assoc();
        echo "<tr>
                    <td>".$result_info['username']."</td>
                    <td>".$result_info['password']."</td>
                    </tr>";
    }

    echo "</table>";
    $result->free();
    $db->close();

    if($num_result >1 ){
        echo '<h1>sql injection success</h1>';
        echo "<a href='../html/login_filt_i.html'>下一关</a>";
        echo "<p align='left' ><font face='楷体'> &#160&#160&#160&#160提示：下一关我们将采用大小写不敏感的方式过滤关键字,但关键字</font><font style='font-weight: bold' face='楷体' >只过滤一次</font>。</p>";
    }
?>
</body>
</html>
