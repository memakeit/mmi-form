# Chechbox and Radio Button Groups

When the input type is `checkbox` or `radio` and the `_choices` meta option is an array,
a group of checkbox or radio buttons is created.

## Configuration

Configuration for checkbox and radio button groups is more complex.

Groups support the same meta options as other fields. Options such as `_before`,
`_after`, `_label`, and `_order` pertain to the group.

To configure the group items, use the `_item` array to set the `_before`, `_after`,
`_label`, and `_order` options at the item level.

## Test Controllers

A test controller is located in `classes/controller/mmi/form/test/field/group`.
