<?php 

session_start();

if(!isset($_SESSION['usuario']))
	header ('Location: index.php?erro=1');

require_once('db.class.php');

$id_usuario = $_SESSION['id_usuario'];

$usuario = $_POST['usuario'];
$email = $_POST['email'];
$senha = md5($_POST['senha']);
$confirmarSenha = md5($_POST['confirmarSenha']);

$objDb = new db();
$link = $objDb->conecta_mysql();

$usuario_existe = false;
$email_existe = false;
$senha_diferentes = false;

//verifica se as senhas são iguais
if($senha != $confirmarSenha)
	$senha_diferentes = true;

	//verifica se o usuário já existe
$sql = "select * from usuarios where usuario = '$usuario' and id != $id_usuario ";
if ($resultado_id  = mysqli_query($link,$sql)) {
		# code...

	$dados_usuario = mysqli_fetch_array($resultado_id);

	if (isset($dados_usuario['usuario'])) {
		$usuario_existe = true;
	}

}else{
	echo "Erro ao tentar localizar o registro de usuario";
}
	//verifica se o email já existe
$sql = "select * from usuarios where email = '$email' and id != $id_usuario ";
if ($resultado_id  = mysqli_query($link,$sql)) {
		# code...

	$dados_usuario = mysqli_fetch_array($resultado_id);

	if (isset($dados_usuario['email'])) {
		$email_existe = true;
	}

}else{
	echo "Erro ao tentar localizar o registro de email";
}

if($usuario_existe || $email_existe || $senha_diferentes){

	$retorno_get = '';

	if ($usuario_existe)
		$retorno_get = "erro_usuario=1&";

	if ($email_existe)
		$retorno_get = "erro_email=1&";
	if ($senha_diferentes)
		$retorno_get = "erro_senha=1&";

	header('Location: editar.php?'.$retorno_get);
	die();
}

$sql = "UPDATE usuarios SET usuario = '$usuario', email = '$email', senha= '$senha' WHERE id = $id_usuario";

//executar a query
if(mysqli_query($link, $sql)){
	header('Location: home.php?novaconf=1');
}

?>