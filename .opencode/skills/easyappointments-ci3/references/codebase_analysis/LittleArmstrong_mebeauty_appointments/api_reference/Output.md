# API Reference: Output.php

**Language**: PHP

**Source**: `system/core/Output.php`

---

## Classes

### CI_Output

**Inherits from**: (none)

#### Methods

##### __construct()


##### get_output()


##### set_output(output)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| output | None | - | - |


##### append_output(output)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| output | None | - | - |


##### set_header(header, replace = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| header | None | - | - |
| replace | None | TRUE | - |


##### set_content_type(mime_type, charset = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| mime_type | None | - | - |
| charset | None | NULL | - |


##### get_content_type()


##### get_header(header)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| header | None | - | - |


##### set_status_header(code = 200, text = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| code | None | 200 | - |
| text | None | '' | - |


##### enable_profiler(val = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | TRUE | - |


##### set_profiler_sections(sections)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sections | None | - | - |


##### cache(time)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | - | - |


##### _display(output = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| output | None | NULL | - |


##### _write_cache(output)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| output | None | - | - |


##### _display_cache(&$CFG, &$URI)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$CFG | None | - | - |
| &$URI | None | - | - |


##### delete_cache(uri = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| uri | None | '' | - |


##### set_cache_header(last_modified, expiration)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| last_modified | None | - | - |
| expiration | None | - | - |


##### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### substr(str, start, length = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| start | None | - | - |
| length | None | NULL | - |




## Functions

### __construct()

**Returns**: (none)



### get_output()

**Returns**: (none)



### set_output(output)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| output | None | - | - |

**Returns**: (none)



### append_output(output)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| output | None | - | - |

**Returns**: (none)



### set_header(header, replace = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| header | None | - | - |
| replace | None | TRUE | - |

**Returns**: (none)



### set_content_type(mime_type, charset = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| mime_type | None | - | - |
| charset | None | NULL | - |

**Returns**: (none)



### get_content_type()

**Returns**: (none)



### get_header(header)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| header | None | - | - |

**Returns**: (none)



### set_status_header(code = 200, text = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| code | None | 200 | - |
| text | None | '' | - |

**Returns**: (none)



### enable_profiler(val = TRUE)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| val | None | TRUE | - |

**Returns**: (none)



### set_profiler_sections(sections)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sections | None | - | - |

**Returns**: (none)



### cache(time)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| time | None | - | - |

**Returns**: (none)



### _display(output = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| output | None | NULL | - |

**Returns**: (none)



### _write_cache(output)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| output | None | - | - |

**Returns**: (none)



### _display_cache(&$CFG, &$URI)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$CFG | None | - | - |
| &$URI | None | - | - |

**Returns**: (none)



### delete_cache(uri = '')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| uri | None | '' | - |

**Returns**: (none)



### set_cache_header(last_modified, expiration)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| last_modified | None | - | - |
| expiration | None | - | - |

**Returns**: (none)



### strlen(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### substr(str, start, length = NULL)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| start | None | - | - |
| length | None | NULL | - |

**Returns**: (none)


