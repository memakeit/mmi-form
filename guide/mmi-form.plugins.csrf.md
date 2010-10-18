# CSRF Plugin

The form method `add_csrf($id, $namespace)` is the easiest way to add
[cross-site request forgery](http://en.wikipedia.org/wiki/Cross-site_request_forgery)
protection to a form.

The following PHP

	$form->add_csrf('csrf', 'mmi');

generates a hidden form field used for CSRF detection.

	<div><input type="hidden" id="mmi_csrf" name="mmi_csrf" value="security4cb87d8ba5da0" class="fld" /></div>

## Configuration

There is no separate configuration file for the CSRF plugin.

The most important options are:

* `_namespace` used to generate the hidden form field id
* `id` used to generate the hidden form field id
