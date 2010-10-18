# Overview

## Configuration

Configuration of forms and fields is done using an associative array of options.

Option names that start with an underscore (`_namespace`, `_rules`, etc) are used as
meta information.

Options that do _not_ start with an underscore (`class`, `id`, etc) are HTML attributes.
Whether an HTML attribute is output depends on whether it is valid for the input type
and HTML version. The HTML version is specified using the `_html5` form option.

## Workflow

* create a form object (essential)
* add plugins (optional)
* add form fields (highly recommended)
* validate and/or render the form
