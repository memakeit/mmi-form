# Custom Filters

## HTML Purifier

The [HTML Purifier](http://htmlpurifier.org/) filter provides a simple means
of protecting against [cross-site scripting](http://en.wikipedia.org/wiki/Cross-site_scripting)
or XSS attacks.

### Dependencies

* shadowhand's [purifier](http://github.com/shadowhand/purifier) module

### Configuration

The operation of the HTML Purifier filter is controller via its configuration options.

When adding the filter, if a string parameter is specified, it is used as the
`HTML.Allowed` setting.

	'_filters'	=> array
	(
		'MMI_Form_Filter_HTML::purify' => array('b,i')
	),

An associative array can be used to specify multiple options.

	'_filters'	=> array
	(
		'MMI_Form_Filter_HTML::purify' => array
		(
			array
			(
				'AutoFormat.AutoParagraph' => TRUE,
				'HTML.Allowed' => 'a[href],b,i,p',
			)
		),
	),

See the [configuration documentation](http://htmlpurifier.org/live/configdoc/plain.html)
for a full list of options.

### Test Controllers

A test controller is located in `classes/controller/mmi/form/test/filter/purify`.
