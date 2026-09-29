# API Reference: Trackback.php

**Language**: PHP

**Source**: `system/libraries/Trackback.php`

---

## Classes

### CI_Trackback

**Inherits from**: (none)

#### Methods

##### __construct()


##### send(tb_data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| tb_data | None | - | - |


##### receive()


##### send_error(message = 'Incomplete Information')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| message | None | 'Incomplete Information' | - |


##### send_success()


##### data(item)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| item | None | - | - |


##### process(url, data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| url | None | - | - |
| data | None | - | - |


##### extract_urls(urls)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| urls | None | - | - |


##### validate_url(&$url)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$url | None | - | - |


##### get_id(url)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| url | None | - | - |


##### convert_xml(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### limit_characters(str, n = 500, end_char = '&#8230;')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| n | None | 500 | - |
| end_char | None | '&#8230;' | - |


##### convert_ascii(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |


##### set_error(msg)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |


##### display_errors(open = '<p>', close = '</p>')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| open | None | '<p>' | - |
| close | None | '</p>' | - |




## Functions

### __construct()

**Returns**: (none)



### send(tb_data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| tb_data | None | - | - |

**Returns**: (none)



### receive()

**Returns**: (none)



### send_error(message = 'Incomplete Information')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| message | None | 'Incomplete Information' | - |

**Returns**: (none)



### send_success()

**Returns**: (none)



### data(item)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| item | None | - | - |

**Returns**: (none)



### process(url, data)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| url | None | - | - |
| data | None | - | - |

**Returns**: (none)



### extract_urls(urls)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| urls | None | - | - |

**Returns**: (none)



### validate_url(&$url)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| &$url | None | - | - |

**Returns**: (none)



### get_id(url)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| url | None | - | - |

**Returns**: (none)



### convert_xml(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### limit_characters(str, n = 500, end_char = '&#8230;')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |
| n | None | 500 | - |
| end_char | None | '&#8230;' | - |

**Returns**: (none)



### convert_ascii(str)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| str | None | - | - |

**Returns**: (none)



### set_error(msg)

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| msg | None | - | - |

**Returns**: (none)



### display_errors(open = '<p>', close = '</p>')

**Parameters**:

| Name | Type | Default | Description |
|------|------|---------|-------------|
| open | None | '<p>' | - |
| close | None | '</p>' | - |

**Returns**: (none)


