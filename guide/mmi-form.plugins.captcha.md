# CAPTCHA Plugin

The form method `add_captcha($driver, $options)` is the easiest way to add a
[CAPTCHA](http://en.wikipedia.org/wiki/CAPTCHA) to a form. A driver for the
[Google reCAPTCHA](http://www.google.com/recaptcha) is included.

The following PHP specifies a white theme and Spanish as the UI language.

	$form->add_captcha('recaptcha', array
	(
		'_lang' => 'es',
		'_theme' => 'white'
	));

It generates the following HTML.

	<script type="text/javascript">
	//<![CDATA[
		var RecaptchaOptions = { theme: 'white', lang: 'es' };
	//]]>
	</script>
	<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=XXXXXXXXXX"></script>
	<noscript>
		<iframe src="http://www.google.com/recaptcha/api/noscript?k=XXXXXXXXXX" height="300" width="500" frameborder="0"></iframe>
		<br/><textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
	</noscript>

## Configuration

The configuration file for the reCAPTCHA plugin is named `mmi-form-plugin-recaptcha.php`.

The most important reCAPTCHA options are:

* `_lang` the UI language
* `_private_key` overrides the private key in the config file
* `_public_key` overrides the public key in the config file
* `_theme` valid values are blackglass, clean, custom, red, and white
* `_use_ssl` submit the data using SSL?
