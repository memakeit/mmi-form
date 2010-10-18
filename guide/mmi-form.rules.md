# Custom Rules

## Min, Max, and Step

The `date`, `datetime`, `datetime-local`, and `week` input types support server-side
validation of the `step` attribute _only if the value can be converted to a timestamp_.
The UNIX timestamp range is from 13 Dec 1901 20:45:54 UTC to 19 Jan 2038 03:14:07 UTC
(see [Year 2038 Problem](http://en.wikipedia.org/wiki/Year_2038_problem)). Dates outside
this range support `min` and `max` server-side validation (using `DateTime` objects). However
`step` validation is _not implemented_.

Since the `month` and `time` input types do not use timestamps for calculations,
they support server-side validation of the `step` attribute for all values.

The `numeric` and `range` input types support server-side validation of the `min`, `max`, and
`step` attributes for all values.

## Min and Max Items

For checkbox groups and multi-select fields, the number of items chosen can be validated
by specifying `min` and `max` rules.

The `MMI_Form_Rule_MinMax_Items` class converts the `min` and `max` rules into custom
callbacks.
