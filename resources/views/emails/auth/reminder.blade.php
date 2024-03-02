<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Recuperar contrasenya</h2>

		<div>
			Per reiniciar la contrasenya visita aquest enllaç: {{ URL::to('password/reset', array($token)) }}.
		</div>
	</body>
</html>