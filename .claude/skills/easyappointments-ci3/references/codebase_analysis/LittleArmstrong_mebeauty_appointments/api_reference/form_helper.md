# API Reference: form_helper.php

**Language**: PHP

**Source**: `system/helpers/form_helper.php`

---

## Functions

### form_open(action = '', attributes = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| action | None | '' | - |
| attributes | None | array( | - |

**Returns**: (none)



### form_open_multipart(action = '', attributes = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| action | None | '' | - |
| attributes | None | array( | - |

**Returns**: (none)



### form_hidden(name, value = '', recursing = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | None | - | - |
| value | None | '' | - |
| recursing | None | FALSE | - |

**Returns**: (none)



### form_input(data = '', value = '', extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| value | None | '' | - |
| extra | None | '' | - |

**Returns**: (none)



### form_password(data = '', value = '', extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| value | None | '' | - |
| extra | None | '' | - |

**Returns**: (none)



### form_upload(data = '', extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| extra | None | '' | - |

**Returns**: (none)



### form_textarea(data = '', value = '', extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| value | None | '' | - |
| extra | None | '' | - |

**Returns**: (none)



### form_multiselect(name = '', options = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| name | None | '' | - |
| options | None | array( | - |

**Returns**: (none)



### form_dropdown(data = '', options = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| options | None | array( | - |

**Returns**: (none)



### form_checkbox(data = '', value = '', checked = FALSE, extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| value | None | '' | - |
| checked | None | FALSE | - |
| extra | None | '' | - |

**Returns**: (none)



### form_radio(data = '', value = '', checked = FALSE, extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| value | None | '' | - |
| checked | None | FALSE | - |
| extra | None | '' | - |

**Returns**: (none)



### form_submit(data = '', value = '', extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| value | None | '' | - |
| extra | None | '' | - |

**Returns**: (none)



### form_reset(data = '', value = '', extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| value | None | '' | - |
| extra | None | '' | - |

**Returns**: (none)



### form_button(data = '', content = '', extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| data | None | '' | - |
| content | None | '' | - |
| extra | None | '' | - |

**Returns**: (none)



### form_label(label_text = '', id = '', attributes = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| label_text | None | '' | - |
| id | None | '' | - |
| attributes | None | array( | - |

**Returns**: (none)



### form_fieldset(legend_text = '', attributes = array()

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| legend_text | None | '' | - |
| attributes | None | array( | - |

**Returns**: (none)



### form_fieldset_close(extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| extra | None | '' | - |

**Returns**: (none)



### form_close(extra = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| extra | None | '' | - |

**Returns**: (none)



### set_value(field, default = '', html_escape = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| default | None | '' | - |
| html_escape | None | TRUE | - |

**Returns**: (none)



### set_select(field, value = '', default = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| value | None | '' | - |
| default | None | FALSE | - |

**Returns**: (none)



### set_checkbox(field, value = '', default = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| value | None | '' | - |
| default | None | FALSE | - |

**Returns**: (none)



### set_radio(field, value = '', default = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | - | - |
| value | None | '' | - |
| default | None | FALSE | - |

**Returns**: (none)



### form_error(field = '', prefix = '', suffix = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| field | None | '' | - |
| prefix | None | '' | - |
| suffix | None | '' | - |

**Returns**: (none)



### validation_errors(prefix = '', suffix = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| prefix | None | '' | - |
| suffix | None | '' | - |

**Returns**: (none)



### _parse_form_attributes(attributes, default)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| attributes | None | - | - |
| default | None | - | - |

**Returns**: (none)



### _attributes_to_string(attributes)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| attributes | None | - | - |

**Returns**: (none)


