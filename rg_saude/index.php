<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>WebService</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

</head>
<body>
	<div class="container">
      <div class="header clearfix">
        <nav>
          <ul class="nav nav-pills pull-right">
          	<li role="presentation" class="active"><img src="http://www.austa.com.br/lib/img/austa_thumb-ac.png" alt="" height="40" style="margin-top: 7px"></li>
            <!-- <li role="presentation" class="active"><a href="#">Home</a></li>
            <li role="presentation"><a href="#">About</a></li>
            <li role="presentation"><a href="#">Contact</a></li> -->
          </ul>
        </nav>
        <h3 class="text-muted">WebService RG Saude</h3>
      </div>

      <div class="jumbotron">
        <h1>RGSOAP</h1>
        <p class="lead">Para acessar o webservice solicite seu usuário e senha. Clique no botão abaixo para acessar o WSDL.</p>
        <p class="text-center"><a class="btn btn-lg btn-success" href="server.php?wsdl" role="button">WSDL</a></p>
      </div>

      <div class="row marketing">
        <div class="col-lg-6">
          <h2>Métodos</h2>
          <p>Serviços do webservice:</p>

          
        </div>

        <div class="col-lg-6">
          <h4>getToken</h4>
          <p>Informe o usuário e senha e receba o token de acesso para consumir os outros serviços.</p>

        
        </div>
      </div>

      <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> AustaClínicas</p>
      </footer>

    </div> <!-- /container -->


	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>