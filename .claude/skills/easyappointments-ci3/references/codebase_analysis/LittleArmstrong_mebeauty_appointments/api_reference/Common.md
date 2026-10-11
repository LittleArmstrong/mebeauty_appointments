# API Reference: Common.php

**Language**: PHP

**Source**: `system/core/Common.php`

---

## Functions

### is_php(version)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| version | None | - | - |

**Returns**: (none)



### is_really_writable(file)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| file | None | - | - |

**Returns**: (none)



### config_item(item)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| item | None | - | - |

**Returns**: (none)



### is_https()

**Returns**: (none)



### is_cli()

**Returns**: (none)



### show_error(message, status_code = 500, heading = 'An Error Was Encountered')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| message | None | - | - |
| status_code | None | 500 | - |
| heading | None | 'An Error Was Encountered' | - |

**Returns**: (none)



### show_404(page = '', log_error = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| page | None | '' | - |
| log_error | None | TRUE | - |

**Returns**: (none)



### log_message(level, message)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| level | None | - | - |
| message | None | - | - |

**Returns**: (none)



### set_status_header(code = 200, text = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| code | None | 200 | - |
| text | None | '' | - |

**Returns**: (none)



### _error_handler(severity, message, filepath, line)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| severity | None | - | - |
| message | None | - | - |
| filepath | None | - | - |
| line | None | - | - |

**Returns**: (none)



### _exception_handler(exception)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| exception | None | - | - |

**Returns**: (none)



### _shutdown_handler()

**Returns**: (none)



### remove_invisible_characters(str, url_encoded = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| url_encoded | None | TRUE | - |

**Returns**: (none)



### html_escape(var, double_encode = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| var | None | - | - |
| double_encode | None | TRUE | - |

**Returns**: (none)



### _stringify_attributes(attributes, js = FALSE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| attributes | None | - | - |
| js | None | FALSE | - |

**Returns**: (none)



### function_usable(function_name)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| function_name | None | - | - |

**Returns**: (none)


