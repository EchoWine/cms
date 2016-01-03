<?php
	
	

	# Initialization	
	$Model = new CredentialModel();

	$Model -> setFields([
		new ID('id'),
		new Username('user'),
		new Password('pass'),
		new Mail('mail')
	]);

	$Model -> setPrimary('id');

	$Controller = new CredentialController($Model);

	$View = new CredentialView($Model,$Controller);

	# Url item
	$page_obj = 'credential';

	# Label item
	$label = 'Credential';

	include Item::getPathApp();

	# Add left menu
	$View -> setNav();

	$Credential = (object)[
		'nav' => (object)[
			'label' => $label,
			'url' => 'index.php'.$Controller -> getUrlMainPage(),
		]
	];

?>