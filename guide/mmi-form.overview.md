# Overview

## Configuration

Configuration of forms and fields is done using an options array.

Option names that start with an underscore are used as meta information by the class being
configured.

Options that do _not_ start with an underscore correspond to HTML attributes. Whether an
HTML attribute is output depends on whether it is valid for the given input type and HTML
version. The HTML version is specified using the `_html5` form option.

## Workflow

* create a form object (essential)
* add plugins (optional)
* add form fields (highly recommended)
* validate and/or render the form
